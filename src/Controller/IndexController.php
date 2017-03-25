<?php

namespace BIT\Controller;

use BIT\Core\Alert;
use BIT\Core\App;
use BIT\Core\Controller;
use BIT\Core\Helper;
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

    /**
     * вход в аккаунт
     * @return View
     */
    public function indexAction()
    {
        $form = new LoginForm();
        if (App::getRequest()->isPost() && $form->load(App::getRequest()->post()) && $form->isValid()) {
            $user = UserManager::findByLogin($form->login);
            if ($user === null || !$user->validatePassword($form->password)) {
                Alert::show('Неверный пользователь или пароль');
            } else {
                App::getUser()->login($user);
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
        if (App::getUser()->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }
        $user = App::getUser()->getIdentity();
        $operations = OperationManager::findUserOperation($user);

        return new View(['operations' => $operations, 'user' => $user, 'balance' => AccountManager::getBalance($user->getAccountId())]);
    }

    /**
     * пополнение счета
     * @return View
     */
    public function replenishAction()
    {
        if (App::getUser()->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        $form = new ReplenishForm();
        if (App::getRequest()->isPost() && $form->load(App::getRequest()->post()) && $form->isValid()) {
            if (OperationManager::replenish(App::getUser()->getIdentity(), $form->amount, $form->account)) {
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
        if (App::getUser()->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        $form = new WithdrawalForm(App::getUser()->getIdentity());
        if (App::getRequest()->isPost() && $form->load(App::getRequest()->post()) && $form->isValid()) {
            if (OperationManager::withdrawal(App::getUser()->getIdentity(), $form->amount, $form->account)) {
                Alert::show('Сумма ' . $form->amount . ' выведена со счета');
                Helper::redirect('/index/account');
            } else {
                Alert::show('Ошибка выведения со счета');
            }
        }

        return new View(['form' => $form]);
    }
}
