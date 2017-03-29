<?php

namespace BIT\Core\Form\Filters;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:30
 */

/**
 * обрезка пустых символов
 * Class StringTrim
 */
class StringTrim extends AbstractFilter
{

    /**
     * метод фильтрации значения
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (!is_string($value)) {
            return null;
        }
        return trim($value);
    }
}