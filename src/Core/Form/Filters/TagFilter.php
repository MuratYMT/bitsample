<?php
namespace BIT\Core\Form\Filters;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:31
 */

/**
 * вырезание тегов
 * Class TagFilter
 * @package BIT\Core\Form\Filters
 */
class TagFilter extends AbstractFilter
{

    /**
     * метод фильтрации значения
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {
        return strip_tags($value);
    }
}