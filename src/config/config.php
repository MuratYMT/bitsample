<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 17:49
 */

use BIT\Core\Services\Connection;
use BIT\Core\Services\EntityManager;
use BIT\Core\Services\Factory\ConnectionFactory;
use BIT\Core\Services\Factory\EntityManagerFactory;
use BIT\Core\Services\Factory\RequestFactory;
use BIT\Core\Services\Factory\UserFactory;
use BIT\Core\Services\Factory\ViewFactory;
use BIT\Core\Services\Request;
use BIT\Core\Services\Session;
use BIT\Core\Services\User;
use BIT\Core\Services\View;
use BIT\Models\Services\AccountManager;
use BIT\Models\Services\OperationManager;
use BIT\Models\Services\UserManager;

return [
    'view' => [
        'class' => View::class,
        'factory' => ViewFactory::class,
        'viewFolder' => __DIR__ . '/../Views',
    ],
    'connection' => [
        'class' => Connection::class,
        'factory' => ConnectionFactory::class,
        'dsn' => 'mysql:host=localhost;dbname=bit',
        'username' => 'root',
        'password' => 'root',
    ],
    'request' => [
        'class' => Request::class,
        'factory' => RequestFactory::class,
        'controllerPath' => __DIR__ . '/../Controller',
        'controllerNamespace' => 'BIT\\Controller',
    ],
    'session' => [
        'class' => Session::class,
    ],
    'user' => [
        'class' => User::class,
        'factory' => UserFactory::class,
        'identityManagerClass' => UserManager::class
    ],
    'entitymanager' => [
        'class' => EntityManager::class,
        'factory' => EntityManagerFactory::class,
        'managers' => [
            AccountManager::class,
            OperationManager::class,
            UserManager::class,
        ]
    ]
];