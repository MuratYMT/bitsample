<?php

namespace BIT\Core;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 16:47
 */
class App
{
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

    public static $config;

    public static function run($config = [])
    {
        self::$config = $config;
        $action = '/';
        if (isset($_SERVER['REQUEST_URI'])) {
            $action = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }

        $action = mb_strtolower($action);
        $actionParts = explode('/', $action);
        if (!is_array($actionParts)) {
            throw new \Exception();
        }
        $controller = array_shift($actionParts);
        if (empty($controller)) {
            $controller = self::DEFAULT_CONTROLLER;
        }
        $controllerAction = count($actionParts) > 0 ? array_shift($actionParts) : self::DEFAULT_ACTION;
        if (empty($controllerAction)) {
            $controllerAction = self::DEFAULT_ACTION;
        }

        self::$_controller = self::checkController($controller);

        list($handler, $params) = self::checkAction(self::$_controller, $controllerAction);
        $result = call_user_func_array($handler, $params);
        echo $result;
    }

    /** @var  View */
    private static $_view;

    /**
     * рендер
     * @return View
     */
    public static function getView()
    {
        if (self::$_view === null) {
            self::$_view = new View();
        }
        return self::$_view;
    }

    private static $_connection;

    /**
     * @return Connection
     */
    public static function getConnection()
    {
        if (self::$_connection === null) {
            self::$_connection = new Connection();
        }
        return self::$_connection;
    }

    private static $_request;

    /**
     * @return Request
     */
    public static function getRequest()
    {
        if (self::$_request === null) {
            self::$_request = new Request();
        }
        return self::$_request;
    }

    /** @var Session */
    private static $_session;

    /**
     * @return Session
     */
    public static function getSession()
    {
        if (self::$_session === null) {
            self::$_session = new Session();
        }
        return self::$_session;
    }

    /** @var User */
    private static $_user;

    /**
     * @return User
     */
    public static function getUser()
    {
        if (self::$_user === null) {
            self::$_user = new User();
        }
        return self::$_user;
    }

    /**
     * выполняет проверку наличия экшена в контроллере
     * @param Controller $controller проверяемы контроллер
     * @param string $action требуемый экшн
     * @return array [$handler, $requestValues]
     * @throws \Exception
     */
    private static function checkAction($controller, $action)
    {
        $action = Helper::id2camel($action) . 'Action';
        $refl = new \ReflectionObject($controller);
        if ($refl->hasMethod($action)) {
            $method = $refl->getMethod($action);
            $params = $method->getParameters();
            //подготавливаем переменные для передачи в обработчик действия
            $reqval = [];
            foreach ($params as $arg) {
                if (array_key_exists($arg->getName(), $_GET)) {
                    $reqval[] = $_GET[$arg->getName()];
                } elseif ($arg->isDefaultValueAvailable()) {
                    $reqval[] = $arg->getDefaultValue();
                } else {
                    $reqval[] = null;
                }
            }
            self::$_action = $method->getName();
            return [[$controller, self::$_action], $reqval];
        }

        throw new \Exception('Undefined action');
    }

    /** @var Controller */
    private static $_controller;
    /** @var string */
    private static $_action;

    /**
     * @param string $controller
     * @return Controller
     * @throws \Exception
     */
    private static function checkController($controller)
    {
        $controllerDir = __DIR__ . '/../Controller';
        $controller = Helper::id2camel($controller);
        $controllerFile = $controllerDir . '/' . $controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            /** @noinspection PhpIncludeInspection */
            $class = Helper::rTrimWord(__NAMESPACE__, '\\Core') . '\\Controller\\' . $controller . 'Controller';
            if (class_exists($class) && is_subclass_of($class, Controller::class)) {
                return new $class;
            }
        }

        throw new \Exception('Undefined controller');
    }

    /**
     * текущий контроллер
     * @return Controller
     */
    public static function getController()
    {
        return self::$_controller;
    }

    /**
     * текущий экшен
     * @return string
     */
    public static function getAction()
    {
        return self::$_action;
    }
}