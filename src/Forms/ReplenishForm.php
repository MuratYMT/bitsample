<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:19
 */

namespace BIT\Forms;

use BIT\Core\Form;
use BIT\Core\FormElements\Button;
use BIT\Core\FormElements\Text;
use BIT\Core\FormFilters\StringTrim;
use BIT\Core\FormFilters\TagFilter;
use BIT\Core\FormFilters\ToDouble;
use BIT\Core\FormFilters\ToScalar;
use BIT\Core\FormValidators\AccountValidator;
use BIT\Core\FormValidators\FloatValidator;
use BIT\Core\InputFilter;

/**
 * Class ReplenishForm форма пополнения счета
 * @package BIT\Forms
 * @property string $account счет источника пополнения
 * @property float $amount сумма пополнения
 */
class ReplenishForm extends Form
{
    public function __construct()
    {
        $this->addElements();
        $this->addInputFilters();
    }

    private function addInputFilters()
    {
        $this->inputFilter = new InputFilter($this);
        $this->inputFilter->addValidator('account', [
            'required' => true,
            'filters' => [
                ['class' => ToScalar::class],
                ['class' => StringTrim::class],
                ['class' => TagFilter::class],
            ],
            'validators' => [
                [
                    'class' => AccountValidator::class,
                    'accountPrefix' => 'r',
                ],
            ],
        ]);
        $this->inputFilter->addValidator('amount', [
            'required' => true,
            'filters' => [
                ['class' => ToScalar::class],
                ['class' => StringTrim::class],
                ['class' => TagFilter::class],
                ['class' => ToDouble::class],
            ],
            'validators' => [
                [
                    'class' => FloatValidator::class,
                    'min' => 0,
                    'max' => 1000,
                ],
            ],
        ]);
    }

    private function addElements()
    {
        $this->add('account', [
            'class' => Text::class,
            'label' => 'Источник пополнения',
        ]);

        $this->add('amount', [
            'class' => Text::class,
            'label' => 'Сумма',
        ]);

        $this->add('submit', [
            'class' => Button::class,
            'label' => 'Пополнить',
            'type' => 'submit',
        ]);
    }
}