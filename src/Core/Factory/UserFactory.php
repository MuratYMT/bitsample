<?php
use BIT\Core\AbstractFactory;
use BIT\Core\ServiceLocator;

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
     * @return mixed
     */
    public function createService($serviceLocator, $class, $config = [])
    {
        $config['session'] = $serviceLocator->getService('session');
        return parent::createService($serviceLocator, $class, $config);
    }
}