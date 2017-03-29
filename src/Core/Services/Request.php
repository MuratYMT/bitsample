<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:08
 */

namespace BIT\Core\Services;

use BIT\Core\Controller;
use BIT\Core\Helper;
use BIT\Core\ServiceLocator;

/**
 * Class Request обработка входных данных
 * @package BIT\Core
 */
class Request
{
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

    public $controllerPath;
    public $controllerNamespace;

    /** @var Session */
    protected $session;
    /** @var ServiceLocator */
    protected $serviceLocator;

    public function __construct(ServiceLocator $serviceLocator, Session $session)
    {
        $this->serviceLocator = $serviceLocator;
        $this->session = $session;
    }

    /**
     * каким методом пришел запрос
     * @return string
     */
    public function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * POST запрос?
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * GET запрос?
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * данные из POST запроса
     * @param string $name если задано то ищет определенный параметр в запросе если нет то выдает весь массив параметров
     * @return array|null
     */
    public function post($name = null)
    {
        if ($this->isPost()) {
            if ($name === null) {
                return $_POST;
            }
            if (isset($_POST[$name]) || array_key_exists($name, $_POST)) {
                return $_POST[$name];
            }
        }
        return null;
    }

    private $_csrtToken;

    /**
     * csrf токен текущего запроса
     * @return string
     */
    public function getCsrfToken()
    {
        if ($this->_csrtToken === null) {
            if (null === ($token = $this->loadCsrfToken())) {
                $token = $this->generateCsrfToken();
            }

            $mask = Helper::generateRandomString(16);
            $this->_csrtToken = $mask . md5($mask . $token);
        }
        return $this->_csrtToken;
    }

    /**
     * проверка токена на валидность
     * @param $token
     * @return bool
     */
    public function validateCsrfToken($token)
    {
        if (!is_string($token)) {
            return false;
        }
        $trueToken = $this->loadCsrfToken();
        $mask = mb_substr($token, 0, 16);
        $hash = mb_substr($token, 16);
        return $hash === md5($mask . $trueToken);
    }

    /**
     * определяет обработчика запроса и параметры требуемые для передачи в экшен
     * @return array|bool
     */
    public function resolve()
    {
        $action = '/';
        if (isset($_SERVER['REQUEST_URI'])) {
            $action = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }

        $action = mb_strtolower($action);
        $actionParts = explode('/', $action);
        if (!is_array($actionParts)) {
            return false;
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

        return $this->checkAction($this->_controller, $controllerAction);
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
        $controller = Helper::id2camel($controller);
        $controllerFile = $this->controllerPath . '/' . $controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            /** @noinspection PhpIncludeInspection */
            $class = $this->controllerNamespace . '\\' . $controller . 'Controller';
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

    /**
     * токен сессии
     * @return string
     */
    private function loadCsrfToken()
    {
        return $this->session->get('__CSRFTOKEN');
    }

    /**
     * генерирует случайный токен
     * @return string
     */
    private function generateCsrfToken()
    {
        $token = Helper::generateRandomString(16);
        $this->session->set('__CSRFTOKEN', $token);
        return $token;
    }
}