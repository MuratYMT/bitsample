<?php
namespace BIT\Core\FormValidators;
use BIT\Core\AbstractValidator;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:39
 */

/**
 * проверка на длину строки
 * Class StringLength
 * @package BIT\Core\FormValidators
 */
class StringLength extends AbstractValidator
{

    public $max;
    public $min;

    public function validate($value)
    {
        $errors = null;
        if ($this->max !== null && mb_strlen($value) > $this->max) {
            $errors[] = 'Длина слишком большая';
        }
        if ($this->min !== null && mb_strlen($value) < $this->min) {
            $errors[] = 'Длина слишком маленькая';
        }
        return $errors;
    }
}