<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:30
 */

namespace BIT\Core\Form\Validators;

/**
 * Class FloatValidator проверка того что введеное значение может быть числом
 * @package BIT\Core\Form\Validators
 */
class FloatValidator extends AbstractValidator
{
    public $max;
    public $min;

    /**
     * метод валидации значения
     * @param mixed $value
     * @return null|array
     */
    public function validate($value)
    {
        $result = null;
        if (!preg_match('/^\-{0,1}\d{1,10}\.{0,1}\d{0,4}$/', $value)) {
            $result[] = 'Неверный формат числа';
        }

        if ($this->max !== null && $value > $this->max) {
            $result[] = 'Число слишком большой';
        }
        if ($this->min !== null && $value < $this->min) {
            $result[] = 'Число слишком маленькое';
        }
        return $result;
    }
}