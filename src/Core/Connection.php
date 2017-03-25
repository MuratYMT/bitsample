<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 20:05
 */

namespace BIT\Core;

class Connection
{
    /** @var  \PDO */
    private $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $conf = App::$config;
        if (!isset($conf['connection'])) {
            throw new \Exception();
        }
        $conf = $conf['connection'];
        $this->pdo = new \PDO($conf['dsn'], $conf['username'], $conf['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAME'utf8'");
    }

    /**
     * последняя вставленная запись
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollBack();
    }

    /**
     * SELECT запрос
     * @param string $sql
     * @param array $params key-value массив параметров
     * @return array|bool
     */
    public function query($sql, $params)
    {
        $statement = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $res = $statement->execute();
        if ($res) {
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * DML запрос
     * @param string $sql
     * @param array $params key-value массив параметров
     * @return false|int false если запрос обломался
     */
    public function execute($sql, $params)
    {
        $statement = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $statement->bindValue($name, $value);
        }
        $res = $statement->execute();
        if ($res) {
            return $statement->rowCount();
        }
        return false;
    }
}