<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 24.03.2017
 * Time: 20:10
 */

namespace BIT\Core\Form\Filters;

/**
 * Class ToScalar
 * @package BIT\Core\Form\Filters
 * Если введеное значение не скалярное значение то заменить на NULL. если фильтр используется то должен идти первым в стеке
 */
class ToScalar extends AbstractFilter
{
    /**
     * метод фильтрации значения
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        if (!is_string($value)){
            return null;
        }
        return $value;
    }
}