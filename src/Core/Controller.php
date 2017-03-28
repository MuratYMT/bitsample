<?php

namespace BIT\Core;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 17:09
 */
class Controller
{
    protected $serviceLocator;

    /**
     * Controller constructor.
     * @param ServiceLocator $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}