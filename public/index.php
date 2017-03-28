<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 17:54
 */
use BIT\Core\App;
use BIT\Core\ServiceLocator;

$appDir = dirname(__DIR__);
require $appDir . '/vendor/autoload.php';
$config = require $appDir . '/src/config/config.php';

App::run(new ServiceLocator($config));

