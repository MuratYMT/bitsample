<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 11:41
 */

namespace BIT\Models\Services;

use BIT\Core\AbstractEntityManager;
use BIT\Core\App;
use BIT\Core\Helper;
use BIT\Models\Entity\Operation;
use BIT\Models\Entity\User;

/**
 * Class OperationManager менеджер операций
 * @package BIT\Models\Services
 */
class OperationManager extends AbstractEntityManager
{
    /** удерживаемая коммисия */
    const FEE = 0.01;
    const FEE_ACCOUNT = 'f1';

    /**
     * найти все операции по этому счету
     * @param string $accounId номер счета
     * @return Operation[]
     * @internal param User $user
     */
    public static function findAccountOperation($accounId)
    {
        $connection = App::getConnection();
        $rows = $connection->query('SELECT `operationId`, `debId`, `credId`, `amount`, `dateOperation`, `description`
        FROM `operations` 
        WHERE `debId` = :id or `credId` = :id
        ORDER BY `operationId` DESC ', ['id' => $accounId]);
        if (count($rows) === 0) {
            return [];
        }
        $result = [];
        foreach ($rows as $row) {
            /** @var User $obj */
            $obj = Helper::createObject(Operation::class, $row);
            $obj->setIsNew(false);
            $result[$obj->getIdValue()] = $obj;
        }
        return $result;
    }

    /**
     * найти операции по счету пользователя
     * @param User $user
     * @return Operation[]
     */
    public static function findUserOperation($user)
    {
        return self::findAccountOperation($user->getAccountId());
    }

    /**
     * пополнение
     * @param User $user
     * @param float $amount
     * @param string $kredAccount
     * @return bool
     */
    public static function replenish($user, $amount, $kredAccount)
    {
        $operation = new Operation();
        $operation->debId = $user->getAccountId();
        $operation->credId = $kredAccount;
        $operation->amount = $amount;
        $operation->dateOperation = (new \DateTime())->format('Y-m-d H:i:s');
        $operation->description = 'Пополение счета пользователя ' . $user->login . ' через ' . $kredAccount;

        return self::save($operation);
    }

    /**
     * вывод
     * @param User $user
     * @param double $amount
     * @param string $debAccount
     * @return bool|int -1 если средств недостаточно
     */
    public static function withdrawal($user, $amount, $debAccount)
    {
        $connection = App::getConnection();
        $connection->beginTransaction();
        try {
            $date = (new \DateTime())->format('Y-m-d H:i:s');
            //проверяем соответствие суммы балансу счета
            if (AccountManager::getBalance($user->getAccountId()) < $amount) {
                $connection->rollback();
                return false;
            }
            $fee = $amount * self::FEE;

            //списываем сумму со счета
            $operation = new Operation();
            $operation->debId = $debAccount;
            $operation->credId = $user->getAccountId();
            $operation->amount = $amount - $fee;
            $operation->dateOperation = $date;
            $operation->description = 'Вывод средств со счета пользователя ' . $user->login . ' на ' . $debAccount;
            self::save($operation);

            //списывааем комиссию
            $feeOperation = new Operation();
            $feeOperation->debId = self::FEE_ACCOUNT;
            $feeOperation->credId = $user->getAccountId();
            $feeOperation->amount = $fee;
            $feeOperation->dateOperation = $date;
            $feeOperation->description = 'Коммисия за вывод средств со счета пользователя ' . $user->login . ' на ' . $debAccount
                . '. Референс операции REF' . $operation->operationId;
            self::save($feeOperation);

            $connection->commit();
            return true;
        } catch (\Exception $e) {
            $connection->rollback();
            return false;
        }
    }

    public static function getEntityTable()
    {
        return 'operations';
    }
}