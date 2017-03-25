<?php
namespace BIT\Forms;

use BIT\Core\Form;
use BIT\Core\FormElements\Button;
use BIT\Core\FormElements\Password;
use BIT\Core\FormElements\Text;
use BIT\Core\FormFilters\StringTrim;
use BIT\Core\FormFilters\TagFilter;
use BIT\Core\FormFilters\ToScalar;
use BIT\Core\FormValidators\StringLength;
use BIT\Core\InputFilter;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 19:51
 */

/**
 * Class LoginForm форма входа
 * @package BIT\Forms
 * @property string $login
 * @property string $password
 */
class LoginForm extends Form
{
    public function __construct()
    {
        $this->addElements();
        $this->addInputFilters();
    }

    private function addInputFilters()
    {
        $this->inputFilter = new InputFilter($this);
        $this->inputFilter->addValidator('login', [
            'required' => true,
            'filters' => [
                ['class' => ToScalar::class],
                ['class' => StringTrim::class],
                ['class' => TagFilter::class],
            ],
            'validators' => [
                [
                    'class' => StringLength::class,
                    'min' => 3,
                    'max' => 64
                ]
            ]
        ]);
        $this->inputFilter->addValidator('password', [
            'required' => true,
            'filters' => [
                ['class' => ToScalar::class],
                ['class' => StringTrim::class],
                ['class' => TagFilter::class],
            ],
            'validators' => [
                [
                    'class' => StringLength::class,
                    'min' => 6,
                    'max' => 64
                ]
            ]
        ]);
    }

    private function addElements()
    {
        $this->add('login', [
            'class' => Text::class,
            'label' => 'Логин',
        ]);

        $this->add('password', [
            'class' => Password::class,
            'label' => 'Пароль',
        ]);

        $this->add('submit', [
            'class' => Button::class,
            'label' => 'Войти',
            'type' => 'submit'
        ]);
    }
}