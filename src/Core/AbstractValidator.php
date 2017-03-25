<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:42
 */

namespace BIT\Core;

abstract class AbstractValidator
{
    /**
     * метод валидации значения
     * @param mixed $value
     * @return null|array
     */
    abstract public function validate($value);
}