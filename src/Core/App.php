<?php

namespace BIT\Core;

use BIT\Core\Services\Request;
use BIT\Core\Services\View;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 16:47
 */
class App
{
    /** @var ServiceLocator */
    private $serviceLocator;

    private function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    protected function go()
    {
        /** @var Request $request */
        $request = $this->serviceLocator->getService('request');
        $resolve = $request->resolve();
        if ($resolve === false) {
            throw new \Exception('Not found');
        }
        list($handler, $params) = $resolve;
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