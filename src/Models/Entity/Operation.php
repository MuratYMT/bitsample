<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 11:36
 */

namespace BIT\Models\Entity;

use BIT\Core\AbstractEntity;

/**
 * Class Operation операции
 * @package BIT\Models\Entity
 */
class Operation extends AbstractEntity
{
    public $operationId;
    public $debId;
    public $credId;
    public $amount;
    public $dateOperation;
    public $description;

    /** Имя параметра первичного ключа */
    public function getId()
    {
        return 'operationId';
    }

    /** Значение параметра первичного ключа */
    public function getIdValue()
    {
        return $this->operationId;
    }
}