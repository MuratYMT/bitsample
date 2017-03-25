<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:32
 */

namespace BIT\Core;

abstract class AbstractFilter
{
    /**
     * метод фильтрации значения
     * @param mixed $value
     * @return mixed
     */
    abstract public function filter($value);
}