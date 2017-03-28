<?php
use BIT\Core\AbstractFactory;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 17:36
 */
class RequestFactory extends AbstractFactory
{
    /**
     * @inheritdoc
     */
    public function createService($serviceLocator, $class, $config = [])
    {
        $config['session'] = $serviceLocator->getService('session');
        return parent::createService($serviceLocator, $class, $config);
    }
}