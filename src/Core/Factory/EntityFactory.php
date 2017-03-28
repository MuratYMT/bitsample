<?php
use BIT\Core\AbstractFactory;

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 17:23
 */
class EntityFactory extends AbstractFactory
{
    /**
     * @inheritdoc
     */
    public function createService($serviceLocator, $class, $config = [])
    {
        $config['connection'] = $serviceLocator->getService('connection');
        return parent::createService($serviceLocator, $class, $config);
    }
}