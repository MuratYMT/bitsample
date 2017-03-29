<?php

namespace BIT\Core\Services\Factory;

use BIT\Core\Helper;

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
    public function createService($serviceLocator, $class, array $config = [])
    {
        $obj = new $class($serviceLocator, $serviceLocator->getService('session'));
        Helper::configureObject($obj, $config);
        return $obj;
    }
}