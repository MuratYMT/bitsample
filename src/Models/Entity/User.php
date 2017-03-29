<?php

namespace BIT\Models\Entity;

use BIT\Models\AbstractEntity;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 20:01
 */

/**
 * Class User пользователь
 * @package BIT\Models\Entity
 */
class User extends AbstractEntity
{
    public $id;
    public $login;
    public $password;

    /** Имя параметра первичного ключа */
    public function getId()
    {
        return 'id';
    }

    /** Значение параметра первичного ключа */
    public function getIdValue()
    {
        return $this->id;
    }

    /**
     * номер счета пользователя
     * @return string
     */
    public function getAccountId()
    {
        return 'u' . $this->id;
    }
}
