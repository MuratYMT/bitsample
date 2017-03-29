<?php

namespace BIT\Core;

use BIT\Forms\AbstractForm;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 17:06
 */
class Helper
{
    public static function camel2id($name, $separator = '-')
    {
        $regex = '/[A-Z]/';
        if ($separator === '_') {
            return trim(strtolower(preg_replace($regex, '_\0', $name)), '_');
        } else {
            return trim(strtolower(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name))), $separator);
        }
    }

    public static function id2camel($id, $separator = '-')
    {
        return str_replace(' ', '', ucwords(implode(' ', explode($separator, $id))));
    }

    /**
     * создает и инициализирует объект
     * @param string $class имя класса создаваемого объекта
     * @param array $config key-value массив значений атрибутов объекта
     * @return mixed
     */
    public static function createObject($class, array $config = [])
    {
        $obj = new $class;
        self::configureObject($obj, $config);
        return $obj;
    }

    /**
     * конфигурирует объект
     * @param mixed $object
     * @param array $config
     */
    public static function configureObject($object, array $config = [])
    {
        foreach ($config as $param => $value) {
            $object->$param = $value;
        }
    }

    /**
     * функция проверяет $value если не массив то делает $value элементом массива
     * @param mixed $value
     * @return mixed[]
     */
    public static function asArray($value)
    {
        /** @noinspection ArrayCastingEquivalentInspection */
        return is_array($value) ? $value : [$value];
    }

    /**
     * удаляет $word с начала строки $str
     * @param string $str строка оригинал
     * @param string $word что надо удалить
     * @return string
     */
    public static function lTrimWord($str, $word)
    {
        return preg_replace('/^' . preg_quote($word, '/') . '/u', '', $str);
    }

    /**
     * удаляет $word с конца строки $str
     * @param string $str строка оригинал
     * @param string $word что надо удалить
     * @return string
     */
    public static function rTrimWord($str, $word)
    {
        return preg_replace('/' . preg_quote($word, '/') . '$/u', '', $str);
    }

    /**
     * генерирует имя элемента формы
     * @param AbstractForm $form форма
     * @param string $element элемент
     * @return string
     */
    public static function getInputName($form, $element)
    {
        $refl = new \ReflectionObject($form);
        return $refl->getShortName() . '[' . $element . ']';
    }

    public static function getInputId($form, $element)
    {
        $refl = new \ReflectionObject($form);
        return self::camel2id($refl->getShortName() . '-' . $element);
    }

    /**
     * отрисоовывает в строку атрибуты тега
     * @param array $attributes key-value массив аттрибутов
     * @return string
     */
    public static function renderTagAttributes($attributes)
    {
        $result = [];
        foreach ($attributes as $name => $value) {
            $result[] = $name . '="' . $value . '"';
        }
        return implode(' ', $result);
    }

    /**
     * выполняет редирект
     * @param string $url
     */
    public static function redirect($url)
    {
        header('HTTP/1.1 302 Found');
        header('Location: ' . $url);
        exit();
    }

    /**
     * генерирует рандомную строку
     * @param int $length требуемая длина
     * @return string
     */
    public static function generateRandomString($length)
    {
        $key = random_bytes($length);
        return strtr(substr(base64_encode($key), 0, $length), '+/', '_-');
    }
}