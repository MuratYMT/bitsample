<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 28.03.2017
 * Time: 12:17
 */

namespace BIT\Models\Entity;

use BIT\Core\AbstractEntity;

class Account extends AbstractEntity
{

    public $id;
    public $balance;

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
}