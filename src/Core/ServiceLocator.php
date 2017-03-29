<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 15:19
 */

namespace BIT\Core;

use BIT\Core\Services\Factory\AbstractFactory;

class ServiceLocator
{
    /** @var array */
    private $config;

    private $services = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getService($name)
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (isset($this->config[$name])) {
            $config = $this->config[$name];
            if (isset($config['class'])) {
                $class = $config['class'];
                unset($config['class']);

                $factoryClass = AbstractFactory::class;
                if (isset($config['factory'])) {
                    $factoryClass = $config['factory'];
                    unset($config['factory']);
                }

                /** @var AbstractFactory $factory */
                $factory = new $factoryClass();
                $this->services[$name] = $factory->createService($this, $class, $config);
                return $this->services[$name];
            }
        }
        throw new \Exception("Undefined service $name");
    }
}