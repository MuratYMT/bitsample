<?php
namespace BIT\Forms;

use BIT\Core\Form\Elements\Button;
use BIT\Core\Form\Elements\Password;
use BIT\Core\Form\Elements\Text;
use BIT\Core\Form\Filters\StringTrim;
use BIT\Core\Form\Filters\TagFilter;
use BIT\Core\Form\Filters\ToScalar;
use BIT\Core\Form\Validators\StringLength;
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
class LoginAbstractForm extends AbstractForm
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