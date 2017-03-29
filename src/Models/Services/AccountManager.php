<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:03
 */

namespace BIT\Models\Services;

use BIT\Core\Helper;
use BIT\Models\AbstractEntityManager;
use BIT\Models\Entity\Account;

/**
 * Class AccountManager менеджер счетов
 * @package BIT\Models\Services
 */
class AccountManager extends AbstractEntityManager
{
    /**
     * найти счет
     * @param string $id номер счета
     * @param bool $lock заблокировать счет
     * @return Account|null
     */
    public function findOne($id, $lock)
    {
        $sql = 'SELECT `id`, `balance` 
        FROM `accounts` 
        WHERE id = :id';

        if ($lock) {
            $sql .= ' FOR UPDATE';
        }

        $rows = $this->connection->query($sql, ['id' => $id]);
        if (count($rows) === 0) {
            return null;
        }
        $row = reset($rows);
        /** @var Account $obj */
        $obj = Helper::createObject(Account::class, $row);
        $obj->setIsNew(false);
        return $obj;
    }

    public static function getEntityTable()
    {
        return 'accounts';
    }
}