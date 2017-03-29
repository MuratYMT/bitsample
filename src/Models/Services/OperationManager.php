<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 11:41
 */

namespace BIT\Models\Services;

use BIT\Core\Helper;
use BIT\Models\AbstractEntityManager;
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
    public function findAccountOperation($accounId)
    {
        $rows = $this->connection->query('SELECT `operationId`, `debId`, `credId`, `amount`, `dateOperation`, `description`
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
    public function findUserOperation($user)
    {
        return $this->findAccountOperation($user->getAccountId());
    }

    /**
     * пополнение
     * @param User $user
     * @param float $amount
     * @param string $kredAccount
     * @return bool
     */
    public function replenish($user, $amount, $kredAccount)
    {
        /** @var AccountManager $accountManager */
        $accountManager = $this->entityManager->getManager(AccountManager::class);
        $this->connection->beginTransaction();
        try {
            $userAccount = $accountManager->findOne($user->getAccountId(), true);
            $replenishAccount = $accountManager->findOne($kredAccount, true);
            //изменяем баланс счетов
            $userAccount->balance += $amount;
            $accountManager->save($userAccount);
            $replenishAccount->balance -= $amount;
            $accountManager->save($replenishAccount);
            //записываем операцию пополнения
            $operation = new Operation();
            $operation->debId = $user->getAccountId();
            $operation->credId = $kredAccount;
            $operation->amount = $amount;
            $operation->dateOperation = time();
            $operation->description = 'Пополение счета пользователя ' . $user->login . ' через ' . $kredAccount;

            $this->save($operation);
            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollback();
            return false;
        }
    }

    /**
     * вывод
     * @param User $user
     * @param double $amount
     * @param string $debAccount
     * @return bool|int -1 если средств недостаточно
     */
    public function withdrawal($user, $amount, $debAccount)
    {
        /** @var AccountManager $accountManager */
        $accountManager = $this->entityManager->getManager(AccountManager::class);
        $this->connection->beginTransaction();
        try {
            $fee = $amount * self::FEE;

            $date = time();
            $userAccount = $accountManager->findOne($user->getAccountId(), true);
            //проверяем соответствие суммы балансу счета с блокировкой изменения счета
            if ($userAccount->balance < $amount) {
                $this->connection->rollback();
                return false;
            }

            $withdrawalAccount = $accountManager->findOne($debAccount, true);
            $feeAccount = $accountManager->findOne(self::FEE_ACCOUNT, true);
            //изменяем баланс счетов
            $userAccount->balance -= $amount;
            $accountManager->save($userAccount);
            $withdrawalAccount->balance += $amount - $fee;
            $accountManager->save($withdrawalAccount);
            $feeAccount->balance += $fee;
            $accountManager->save($feeAccount);

            //списываем сумму со счета
            $operation = new Operation();
            $operation->debId = $debAccount;
            $operation->credId = $user->getAccountId();
            $operation->amount = $amount - $fee;
            $operation->dateOperation = $date;
            $operation->description = 'Вывод средств со счета пользователя ' . $user->login . ' на ' . $debAccount;
            $this->save($operation);

            //списывааем комиссию
            $feeOperation = new Operation();
            $feeOperation->debId = self::FEE_ACCOUNT;
            $feeOperation->credId = $user->getAccountId();
            $feeOperation->amount = $fee;
            $feeOperation->dateOperation = $date;
            $feeOperation->description = 'Коммисия за вывод средств со счета пользователя ' . $user->login . ' на ' . $debAccount
                . '. Референс операции REF' . $operation->operationId;
            $this->save($feeOperation);

            $this->connection->commit();
            return true;
        } catch (\Exception $e) {
            $this->connection->rollback();
            return false;
        }
    }

    public static function getEntityTable()
    {
        return 'operations';
    }
}