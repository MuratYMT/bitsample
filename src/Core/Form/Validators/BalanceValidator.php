<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:02
 */

namespace BIT\Core\Form\Validators;

use BIT\Core\App;
use BIT\Core\Services\EntityManager;
use BIT\Models\Services\AccountManager;

/**
 * Class BalanceValidator проверка остатка на балансе. если значение больше остатка генерируется ошибка формыы
 * @package BIT\Core\Form\Validators
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
        /** @var EntityManager $entityManager */
        $entityManager = App::serviceLocator()->getService('entitymanager');
        /** @var AccountManager $accountManager */
        $accountManager = $entityManager->getManager(AccountManager::class);
        $account = $accountManager->findOne($this->account, false);
        if ($account->balance < $value) {
            return ['Суума превышает остаток счета'];
        }
        return null;
    }
}