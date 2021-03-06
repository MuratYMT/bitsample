<?php
namespace BIT\Core\Form\Elements;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 19:48
 */
/**
 * Базовый класс для элементов формы
 * Class AbstractFormElement
 * @package BIT\Core\Form\Elements
 */
abstract class AbstractFormElement
{
    /** @var string заголовок */
    public $label;
    /** @var string имя */
    public $name;
/** @var  mixed значение */
    public $value;

    /**
     * тип элемента формы
     * @return string
     */
    abstract public function type();
}