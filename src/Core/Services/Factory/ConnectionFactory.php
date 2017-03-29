<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 28.03.2017
 * Time: 20:05
 */

namespace BIT\Core\Services\Factory;

use BIT\Core\Helper;
use BIT\Core\Services\Connection;

class ConnectionFactory extends AbstractFactory
{
    /**
     * @inheritdoc
     */
    public function createService($serviceLocator, $class, array $config = [])
    {
        /** @var Connection $connection */
        $connection = new $class();
        Helper::configureObject($connection, $config);
        $connection->connect();
        return $connection;
    }
}