<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:03
 */

namespace BIT\Models\Services;

use BIT\Core\AbstractEntityManager;

/**
 * Class AccountManager менеджер счетов
 * @package BIT\Models\Services
 */
class AccountManager extends AbstractEntityManager
{
    /**
     * определение баланса стеча
     * @param $account
     * @return float
     */
    public static function getBalance($account)
    {
        $operations = OperationManager::findAccountOperation($account);
        $result = 0.0;
        foreach ($operations as $operation) {
            if ($operation->debId === $account) {
                $result += $operation->amount;
            } else {
                $result -= $operation->amount;
            }
        }
        return $result;
    }

    public static function getEntityTable()
    {
        return 'accounts';
    }
}