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

    /** @var ServiceLocator */
    private $serviceLocator;

    private function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    protected function go()
    {
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

        $this->_controller = $this->checkController($controller);

        list($handler, $params) = $this->checkAction($this->_controller, $controllerAction);
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

    /**
     * выполняет проверку наличия экшена в контроллере
     * @param Controller $controller проверяемы контроллер
     * @param string $action требуемый экшн
     * @return array [$handler, $requestValues]
     * @throws \Exception
     */
    private function checkAction($controller, $action)
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
            $this->_action = $method->getName();
            return [[$controller, $this->_action], $reqval];
        }

        throw new \Exception('Undefined action');
    }

    /** @var Controller */
    private $_controller;
    /** @var string */
    private $_action;

    /**
     * @param string $controller
     * @return Controller
     * @throws \Exception
     */
    private function checkController($controller)
    {
        $controllerDir = __DIR__ . '/../Controller';
        $controller = Helper::id2camel($controller);
        $controllerFile = $controllerDir . '/' . $controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            /** @noinspection PhpIncludeInspection */
            $class = Helper::rTrimWord(__NAMESPACE__, '\\Core') . '\\Controller\\' . $controller . 'Controller';
            if (class_exists($class) && is_subclass_of($class, Controller::class)) {
                return new $class($this->serviceLocator);
            }
        }

        throw new \Exception('Undefined controller');
    }

    /**
     * текущий контроллер
     * @return Controller
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * текущий экшен
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /** @var App */
    private static $_app;

    /**
     * @return ServiceLocator
     */
    public static function serviceLocator()
    {
        return self::$_app->serviceLocator;
    }

    /**
     * запуск приложения на выполнение
     * @param ServiceLocator $serviceLocator
     */
    public static function run($serviceLocator)
    {
        if (self::$_app === null) {
            self::$_app = new self($serviceLocator);
            self::$_app->go();
        }
    }
}