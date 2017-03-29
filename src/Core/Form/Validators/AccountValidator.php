<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:22
 */

namespace BIT\Core\Form\Validators;

/**
 * Class AccountValidator заглушка для проверки существования счета.
 * @package BIT\Core\Form\Validators
 */
class AccountValidator extends AbstractValidator
{
    /** @var string префикс счета */
    public $accountPrefix = 'u';

    /**
     * метод валидации значения
     * @param mixed $value
     * @return null|array
     */
    public function validate($value)
    {
        //заглушка валидации
        if (!preg_match('/^' . $this->accountPrefix . '(1|2|3)$/', $value)) {
            return ['Неверный счет'];
        }
        return null;
    }
}