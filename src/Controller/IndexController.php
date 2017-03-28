<?php

namespace BIT\Controller;

use BIT\Core\Alert;
use BIT\Core\Controller;
use BIT\Core\Helper;
use BIT\Core\Request;
use BIT\Core\ServiceLocator;
use BIT\Core\User;
use BIT\Core\View;
use BIT\Forms\LoginForm;
use BIT\Forms\ReplenishForm;
use BIT\Forms\WithdrawalForm;
use BIT\Models\Services\AccountManager;
use BIT\Models\Services\OperationManager;
use BIT\Models\Services\UserManager;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:08
 */
class IndexController extends Controller
{
    /** @var Request */
    public $request;
    /** @var User */
    public $user;

    public function __construct(ServiceLocator $serviceLocator)
    {
        parent::__construct($serviceLocator);
        $this->request = $this->serviceLocator->getService('request');
        $this->user = $this->serviceLocator->getService('user');
    }

    /**
     * вход в аккаунт
     * @return View
     */
    public function indexAction()
    {
        $form = new LoginForm();
        if ($this->request->isPost() && $form->load($this->request->post()) && $form->isValid()) {
            $user = UserManager::findByLogin($form->login);
            if ($user === null || !$user->validatePassword($form->password)) {
                Alert::show('Неверный пользователь или пароль');
            } else {
                $this->user->login($user);
                Alert::show('Успешная авторизация');
                Helper::redirect('/index/account');
            }
        }
        return new View(['form' => $form]);
    }

    /**
     * Выписка по счету
     * @return View
     */
    public function accountAction()
    {
        if ($this->user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }
        $user = $this->user->getIdentity();
        $operations = OperationManager::findUserOperation($user);

        return new View(['operations' => $operations, 'user' => $user, 'account' => AccountManager::findOne($user->getAccountId())]);
    }

    /**
     * пополнение счета
     * @return View
     */
    public function replenishAction()
    {
        if ($this->user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        $form = new ReplenishForm();
        if ($this->request->isPost() && $form->load($this->request->post()) && $form->isValid()) {
            if (OperationManager::replenish($this->user->getIdentity(), $form->amount, $form->account)) {
                Alert::show('Сумма ' . $form->amount . ' зачислена на счет');
                Helper::redirect('/index/account');
            } else {
                Alert::show('Ошибка пополнения счета');
            }
        }

        return new View(['form' => $form]);
    }

    /**
     * вывод средств
     * @return View
     */
    public function withdrawalAction()
    {
        if ($this->user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        $form = new WithdrawalForm($this->user->getIdentity());
        if ($this->request->isPost() && $form->load($this->request->post()) && $form->isValid()) {
            if (OperationManager::withdrawal($this->user->getIdentity(), $form->amount, $form->account)) {
                Alert::show('Сумма ' . $form->amount . ' выведена со счета');
                Helper::redirect('/index/account');
            } else {
                Alert::show('Ошибка выведения со счета');
            }
        }

        return new View(['form' => $form]);
    }
}
