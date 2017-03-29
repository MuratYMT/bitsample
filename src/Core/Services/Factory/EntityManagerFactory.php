<?php

namespace BIT\Core\Services\Factory;

use BIT\Core\Helper;
use BIT\Core\Services\EntityManager;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 28.03.2017
 * Time: 19:27
 */
class EntityManagerFactory extends AbstractFactory
{
    /**
     * @inheritdoc
     */
    public function createService($serviceLocator, $class, array $config = [])
    {
        /** @var EntityManager $entityManager */
        $entityManager = new $class($serviceLocator->getService('connection'), $serviceLocator);
        Helper::configureObject($entityManager, $config);
        return $entityManager;
    }
}