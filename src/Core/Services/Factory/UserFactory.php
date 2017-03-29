<?php

namespace BIT\Core\Services\Factory;

use BIT\Core\Helper;
use BIT\Core\ServiceLocator;
use BIT\Core\Services\User;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 17:09
 */
class UserFactory extends AbstractFactory
{
    /**
     * @param ServiceLocator $serviceLocator
     * @param string $class класс сервиса
     * @param array $config конфигурация сервиса
     * @return User
     */
    public function createService($serviceLocator, $class, array $config = [])
    {
        /** @var \BIT\Core\Services\User $user */
        $user = new $class($serviceLocator->getService('entitymanager'), $serviceLocator->getService('session'));
        Helper::configureObject($user, $config);
        $user->init();
        return $user;
    }
}