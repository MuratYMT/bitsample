<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 28.03.2017
 * Time: 22:06
 */

namespace BIT\Core\Services\Factory;

use BIT\Core\Helper;
use BIT\Core\ServiceLocator;
use BIT\Core\Services\View;

class ViewFactory extends AbstractFactory
{
    /**
     * @param ServiceLocator $serviceLocator
     * @param string $class класс сервиса
     * @param array $config конфигурация сервиса
     * @return View
     */
    public function createService($serviceLocator, $class, array $config = [])
    {
        /** @var \BIT\Core\Services\View $view */
        $view = new $class($serviceLocator->getService('request'));
        Helper::configureObject($view, $config);
        return $view;
    }
}