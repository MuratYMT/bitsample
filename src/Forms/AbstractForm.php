<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:13
 */

namespace BIT\Forms;

use BIT\Core\Alert;
use BIT\Core\App;
use BIT\Core\Form\Elements\AbstractFormElement;
use BIT\Core\Helper;
use BIT\Core\InputFilter;
use BIT\Core\Services\Request;

/**
 * Class Form базовый класс для форм
 * @package BIT\Core
 */
class AbstractForm
{
    /** @var string метод формы */
    protected $method = 'post';
    /**
     * @var array ошибки валидации
     */
    private $errors = [];

    /** @var  InputFilter фильтры и валидаторы вводимых значений */
    protected $inputFilter;

    /** @var AbstractFormElement[] элементы формы */
    protected $elements = [];

    /** @var string экшн формы */
    protected $action;

    /**
     * добавить элемент формы
     * @param string $name имя элементы
     * @param array $config конфигурация элемента
     */
    public function add($name, $config)
    {
        $class = $config['class'];
        unset($config['class']);
        $this->elements[$name] = Helper::createObject($class, $config);
    }

    /**
     * загрузка данных
     * @param array $data исходные данные
     * @param string $scope имя формы
     * @return bool
     */
    public function load($data, $scope = null)
    {
        if ($scope === null) {
            $refl = new \ReflectionObject($this);
            $scope = $refl->getShortName();
        }

        if (!isset($data[$scope])) {
            return false;
        }

        $this->setElements($data[$scope]);
        return true;
    }

    /**
     * устанавливает значения элементов
     * @param $data
     */
    protected function setElements($data)
    {
        $elements = array_keys($this->elements);
        foreach ($elements as $element) {
            if (array_key_exists($element, $data)) {
                $this->$element = $data[$element];
            }
        }
    }

    /**
     * @param $name
     * @return AbstractFormElement
     */
    public function getElement($name)
    {
        if (isset($this->$name)) {
            return $this->elements[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (isset($this->elements[$name])) {
            $element = $this->elements[$name];
            $element->value = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->elements[$name]);
    }

    public function __get($name)
    {
        if (isset($this->elements[$name])) {
            return $this->elements[$name]->value;
        }
        throw new \Exception('Undefined form element ' . static::class . '::' . $name);
    }

    /**
     * проверка правильности заполнения формы
     * @return bool
     */
    public function isValid()
    {
        $this->clearErrors();

        if ($this->inputFilter === null) {
            return true;
        }

        if (!$this->validateCsrf()) {
            Alert::show('Срабатывание защиты CSFR');
            return false;
        }

        $this->inputFilter->validateAttributes();

        return !$this->hasError();
    }

    /**
     * проверка CSRF токена
     * @return bool
     */
    private function validateCsrf()
    {
        $request = App::serviceLocator()->getService('request');
        if ($request->isGet()) {
            return true;
        }

        if (null === ($csrf = $request->post('__CSRF'))) {
            return false;
        }

        return $request->validateCsrfToken($csrf);
    }

    /**
     * очистка ошибок валидации
     */
    public function clearErrors()
    {
        $this->errors = [];
    }

    /**
     * есть ли ошибки валидации формы
     * @param string $element
     * @return bool
     */
    public function hasError($element = null)
    {
        if ($element === null) {
            return count($this->errors) > 0;
        }

        return isset($this->errors[$element]);
    }

    /**
     * ошибки валидации формы
     * @param string $element если не указано то выдает все ошибки
     * @return array
     */
    public function getError($element = null)
    {
        if ($element === null && $this->hasError()) {
            return $this->errors;
        } elseif ($this->hasError($element)) {
            return $this->errors[$element];
        }
        return [];
    }

    /**
     * добавляет ошибки валидации
     * @param string $attribute
     * @param string[]|string $errors
     */
    public function addErrors($attribute, $errors)
    {
        foreach (Helper::asArray($errors) as $error) {
            $this->errors[$attribute][] = $error;
        }
    }

    // ------------------------------------ геттеры и сеттеры ------------------------------------

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        /** @var Request $request */
        $request = App::serviceLocator()->getService('request');

        if ($this->action === null) {
            return '/' . Helper::camel2id(Helper::rTrimWord($request->getAction(), 'Action'));
        }
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action)
    {
        $this->action = $action;
    }
}