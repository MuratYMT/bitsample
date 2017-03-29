<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:09
 */

namespace BIT\Models;

use BIT\Core\Services\Connection;
use BIT\Core\Services\EntityManager;

abstract class AbstractEntityManager
{
    /** @var \BIT\Core\Services\Connection */
    protected $connection;

    /** @var \BIT\Core\Services\EntityManager */
    protected $entityManager;

    public function __construct(Connection $connection, EntityManager $entityManager)
    {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

    abstract public static function getEntityTable();

    /**
     * @param AbstractEntity $entity
     * @return bool
     */
    public function save($entity)
    {
        $refl = new \ReflectionObject($entity);
        $attributes = $refl->getProperties(\ReflectionProperty::IS_PUBLIC);
        $params = [];
        foreach ($attributes as $attribute) {
            $name = $attribute->name;
            $params[$name] = $entity->$name;
        }
        $placeholders = [];
        if ($entity->isIsNew()) {
            foreach ($params as $name => $value) {
                if ($name === $entity->getId() && empty($entity->getIdValue())) {
                    unset($params[$entity->getId()]);
                    continue;
                }
                $placeholders["`$name`"] = ":$name";
            }

            $sql = 'INSERT INTO `' . static::getEntityTable() . '` (' . implode(', ', array_keys($placeholders)) . ') VALUES (' . implode(', ',
                    $placeholders) . ')';
        } else {
            foreach ($params as $name => $value) {
                if ($name !== $entity->getId()) {
                    $placeholders[] = "`$name` = :$name";
                }
            }
            $sql = 'UPDATE `' . static::getEntityTable() . '` 
            SET ' . implode(', ', $placeholders) . '
            WHERE ' . $entity->getId() . '= :' . $entity->getId();
        }

        $result = $this->connection->execute($sql, $params);
        if ($result !== false) {
            if ($entity->isIsNew()) {
                $pk = $entity->getId();
                $entity->$pk = $this->connection->lastInsertId();
                $entity->setIsNew(false);
            }

            return true;
        }
        return false;
    }
}
