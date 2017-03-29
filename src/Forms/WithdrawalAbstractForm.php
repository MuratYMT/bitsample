<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:19
 */

namespace BIT\Forms;

use BIT\Core\Form\Elements\Button;
use BIT\Core\Form\Elements\Text;
use BIT\Core\Form\Filters\StringTrim;
use BIT\Core\Form\Filters\TagFilter;
use BIT\Core\Form\Filters\ToDouble;
use BIT\Core\Form\Filters\ToScalar;
use BIT\Core\Form\Validators\AccountValidator;
use BIT\Core\Form\Validators\BalanceValidator;
use BIT\Core\Form\Validators\FloatValidator;
use BIT\Core\InputFilter;
use BIT\Models\Entity\User;

/**
 * Class ReplenishForm форма вывода средств
 * @package BIT\Forms
 * @property string $account счет источника пополнения
 * @property float $amount сумма пополнения
 */
class WithdrawalAbstractForm extends AbstractForm
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