<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 16:01
 */

namespace BIT\Core;

class AbstractFactory
{
    /**
     * @param ServiceLocator $serviceLocator
     * @param string $class класс сервиса
     * @param array $config конфигурация сервиса
     * @return mixed
     */
    public function createService($serviceLocator, $class, $config = [])
    {
        return Helper::createObject($class, $config);
    }
}