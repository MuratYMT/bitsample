<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:27
 */

namespace BIT\Core\Form\Filters;

/**
 * Class ToFloat
 * @package BIT\Core\Form\Filters
 * преобразует введенное значение в Double Precision
 */
class ToDouble extends AbstractFilter
{

    /**
     * метод фильтрации значения
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        return (double)$value;
    }
}