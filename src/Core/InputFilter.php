<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:17
 */

namespace BIT\Core;

use BIT\Core\Form\Filters\AbstractFilter;
use BIT\Core\Form\Validators\AbstractValidator;
use BIT\Forms\AbstractForm;

class InputFilter
{
    /**
     * Обязательно должно быть заполнено
     */
    const REQUIRED = 'required';
    const FILTERS = 'filters';
    const VALIDATORS = 'validators';
    private $validators = [];
    /**
     * @var AbstractForm
     */
    private $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function addValidator($attribute, $config)
    {
        $this->validators[$attribute] = $config;
    }

    /**
     * выполние валидации формы
     */
    public function validateAttributes()
    {
        $form = $this->form;
        foreach ($this->validators as $attribute => $conf) {
            //применяем фтльтры
            if (array_key_exists(self::FILTERS, $conf)) {
                $filters = $conf[self::FILTERS];
                unset($conf[self::FILTERS]);
                foreach ($filters as $filter) {
                    $class = array_shift($filter);
                    /** @var AbstractFilter $filter */
                    $filter = Helper::createObject($class, $filter);
                    $form->$attribute = $filter->filter($form->$attribute);
                }
            }
            //выполняем валидацию
            if (array_key_exists(self::VALIDATORS, $conf)) {
                $filters = $conf[self::VALIDATORS];
                unset($conf[self::VALIDATORS]);
                foreach ($filters as $validator) {
                    $class = array_shift($validator);
                    /** @var AbstractValidator $validator */
                    $validator = Helper::createObject($class, $validator);
                    $result = $validator->validate($form->$attribute);
                    if ($result !== null) {
                        $form->addErrors($attribute, $result);
                    }
                }
            }
            //необходимость заполнения. идет в конце
            if (array_key_exists(self::REQUIRED, $conf)) {
                $required = $conf[self::REQUIRED];
                unset($conf[self::REQUIRED]);
                if ($required && empty($form->$attribute)) {
                    $form->addErrors($attribute, 'Значение должно быть заполнено');
                }
            }
        }
    }
}
