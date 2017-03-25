<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:02
 */

namespace BIT\Core\FormValidators;

use BIT\Core\AbstractValidator;
use BIT\Models\Services\AccountManager;

/**
 * Class BalanceValidator проверка остатка на балансе. если значение больше остатка генерируется ошибка формыы
 * @package BIT\Core\FormValidators
 */
class BalanceValidator extends AbstractValidator
{
    /** @var  string */
    public $account;

    /**
     * метод валидации значения
     * @param mixed $value
     * @return null|array
     */
    public function validate($value)
    {
        $balance = AccountManager::getBalance($this->account);
        if ($balance < $value) {
            return ['Суума превышает остаток счета'];
        }
        return null;
    }
}