<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 20:51
 */

namespace BIT\Core;

abstract class AbstractEntity
{
    /** Имя параметра первичного ключа */
    abstract public function getId();

    /** Значение параметра первичного ключа */
    abstract public function getIdValue();

    // ------------------------------------------- геттеры и сеттеры ----------------------------------------

    protected $isNew = true;

    /**
     * @return bool
     */
    public function isIsNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param bool $isNew
     */
    public function setIsNew(bool $isNew)
    {
        $this->isNew = $isNew;
    }
}