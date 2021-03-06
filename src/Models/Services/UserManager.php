<?php

namespace BIT\Models\Services;

use BIT\Core\Helper;
use BIT\Models\AbstractEntityManager;
use BIT\Models\Entity\User;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 20:05
 */

/**
 * Class UserManager менеджер пользователей
 * @package BIT\Models\Services
 */
class UserManager extends AbstractEntityManager
{
    /**
     * поиск пользователя по id
     * @param int $userId
     * @return User|null
     */
    public function findOne($userId)
    {
        $rows = $this->connection->query('SELECT id, login, password 
        FROM `users` 
        WHERE id = :id', ['id' => $userId]);
        if (count($rows) === 0) {
            return null;
        }
        $row = reset($rows);
        /** @var User $obj */
        $obj = Helper::createObject(User::class, $row);
        $obj->setIsNew(false);
        return $obj;
    }

    /**
     * поиск пользователя по логину
     * @param string $login
     * @return User|null
     */
    public function findByLogin($login)
    {
        $rows = $this->connection->query('SELECT id, login, password 
        FROM `users` 
        WHERE login = :login', ['login' => $login]);
        if (count($rows) === 0) {
            return null;
        }
        $row = reset($rows);
        $obj = Helper::createObject(User::class, $row);
        $obj->setIsNew(false);
        return $obj;
    }

    /**
     * хеширование введенного пароля
     * генерируешь
     * @param User $user
     * @param string $password
     */
    public static function setPassword($user, $password)
    {
        $user->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * проверка введеного пароля
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function validatePassword($user, $password)
    {
        return password_verify($password, $user->password);
    }

    public static function getEntityTable()
    {
        return 'users';
    }
}