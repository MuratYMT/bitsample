<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 15:09
 */

namespace BIT\Core;

abstract class AbstractEntityManager
{
    abstract public static function getEntityTable();

    /**
     * @param AbstractEntity $entity
     * @return bool
     */
    public static function save($entity)
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
            WHERE ' . $entity->getId() . '= :' . $entity->getIdValue();
        }

        $connection = App::getConnection();
        $result = $connection->execute($sql, $params);
        if ($result !== false) {
            if ($entity->isIsNew()) {
                $pk = $entity->getId();
                $entity->$pk = $connection->lastInsertId();
                $entity->setIsNew(false);
            }

            return true;
        }
        return false;
    }
}
