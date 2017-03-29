<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 28.03.2017
 * Time: 19:25
 */

namespace BIT\Core\Services;

use BIT\Core\ServiceLocator;
use BIT\Models\AbstractEntityManager;

class EntityManager
{
    /** @var  Connection */
    protected $connection;

    /** @var array классы менеджеров сущностей */
    public $managers;

    /** @var ServiceLocator */
    protected $serviceLocator;

    /** @var AbstractEntityManager[] */
    public $obj;

    public function __construct(Connection $connection, ServiceLocator $serviceLocator)
    {
        $this->connection = $connection;
        $this->serviceLocator = $serviceLocator;
    }

    public function getManager($class)
    {
        if (isset($this->obj[$class])) {
            return $this->obj[$class];
        }

        if (in_array($class, $this->managers, true)) {
            return new $class($this->connection, $this);
        }

        throw new \Exception("Undefined entity manager $class");
    }
}