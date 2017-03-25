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
use BIT\Core\FormValidators\BalanceValidator;
use BIT\Core\FormValidators\FloatValidator;
use BIT\Core\InputFilter;
use BIT\Models\Entity\User;

/**
 * Class ReplenishForm форма вывода средств
 * @package BIT\Forms
 * @property string $account счет источника пополнения
 * @property float $amount сумма пополнения
 */
class WithdrawalForm extends Form
{
    /** @var User */
    protected $user;

    /**
     * WithdrawalForm constructor.
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
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
                    'accountPrefix' => 'w',
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
                [
                    'class' => BalanceValidator::class,
                    'account' => $this->user->getAccountId(),
                ],
            ],
        ]);
    }

    private function addElements()
    {
        $this->add('account', [
            'class' => Text::class,
            'label' => 'Куда вывести',
        ]);

        $this->add('amount', [
            'class' => Text::class,
            'label' => 'Сумма',
        ]);

        $this->add('submit', [
            'class' => Button::class,
            'label' => 'Вывести',
            'type' => 'submit',
        ]);
    }
}