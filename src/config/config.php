<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 17:49
 */

use BIT\Core\Connection;
use BIT\Core\Request;
use BIT\Core\User;

return [
    'view' => [
        'viewFolder' => __DIR__ . '/../Views',
    ],
    'connection' => [
        'class' => Connection::class,
        'dsn' => 'mysql:host=localhost;dbname=bit',
        'username' => 'root',
        'password' => 'root',
    ],
    'request' => [
        'class' => Request::class,
        'factory' => RequestFactory::class,
    ],
    'user' => [
        'class' => User::class,
        'factory' => UserFactory::class,
    ],
];