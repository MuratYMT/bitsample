<?php

namespace BIT\Controller;

use BIT\Core\Alert;
use BIT\Core\Controller;
use BIT\Core\Helper;
use BIT\Core\Services\EntityManager;
use BIT\Forms\LoginAbstractForm;
use BIT\Forms\ReplenishAbstractForm;
use BIT\Forms\WithdrawalAbstractForm;
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
     * @return \BIT\Core\Services\View
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user->isGuest()) {
            Alert::show('Вы уже зашли');
            Helper::redirect('/index/account');
        }

        /** @var UserManager $userManager */
        $userManager = $this->getEntityManager(UserManager::class);
        $form = new LoginAbstractForm();
        $request = $this->getRequest();
        if ($request->isPost() && $form->load($request->post()) && $form->isValid()) {
            $userEntity = $userManager->findByLogin($form->login);
            if ($userEntity === null || !$userManager->validatePassword($userEntity, $form->password)) {
                Alert::show('Неверный пользователь или пароль');
            } else {
                $user->login($userEntity);
                Alert::show('Успешная авторизация');
                Helper::redirect('/index/account');
            }
        }
        return $this->getView()->render(['form' => $form]);
    }

    /**
     * Выписка по счету
     * @return \BIT\Core\Services\View
     */
    public function accountAction()
    {
        $user = $this->getUser();
        if ($user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        /** @var OperationManager $operationManager */
        $operationManager = $this->getEntityManager(OperationManager::class);
        /** @var AccountManager $accountManager */
        $accountManager = $this->getEntityManager(AccountManager::class);

        $user = $user->getIdentity();

        $operations = $operationManager->findUserOperation($user);

        return $this->getView()->render([
            'operations' => $operations,
            'user' => $user,
            'account' => $accountManager->findOne($user->getAccountId(), false)
        ]);
    }

    /**
     * пополнение счета
     * @return \BIT\Core\Services\View
     */
    public function replenishAction()
    {
        $user = $this->getUser();
        if ($user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        /** @var OperationManager $operationManager */
        $operationManager = $this->getEntityManager(OperationManager::class);

        $form = new ReplenishAbstractForm();
        $request = $this->getRequest();
        if ($request->isPost() && $form->load($request->post()) && $form->isValid()) {
            if ($operationManager->replenish($user->getIdentity(), $form->amount, $form->account)) {
                Alert::show('Сумма ' . $form->amount . ' зачислена на счет');
                Helper::redirect('/index/account');
            } else {
                Alert::show('Ошибка пополнения счета');
            }
        }

        return $this->getView()->render(['form' => $form]);
    }

    /**
     * вывод средств
     * @return \BIT\Core\Services\View
     */
    public function withdrawalAction()
    {
        $user = $this->getUser();
        if ($user->isGuest()) {
            Alert::show('Необходимо авторизоваться');
            Helper::redirect('/');
        }

        /** @var OperationManager $operationManager */
        $operationManager = $this->getEntityManager(OperationManager::class);

        $form = new WithdrawalAbstractForm($user->getIdentity());
        $request = $this->getRequest();
        if ($request->isPost() && $form->load($request->post()) && $form->isValid()) {
            if ($operationManager->withdrawal($user->getIdentity(), $form->amount, $form->account)) {
                Alert::show('Сумма ' . $form->amount . ' выведена со счета');
                Helper::redirect('/index/account');
            } else {
                Alert::show('Ошибка выведения со счета');
            }
        }

        return $this->getView()->render(['form' => $form]);
    }

    /**
     * @param $class
     * @return \BIT\Models\AbstractEntityManager
     */
    private function getEntityManager($class)
    {
        /** @var EntityManager $entytyManager */
        $entytyManager = $this->serviceLocator->getService('entitymanager');
        return $entytyManager->getManager($class);
    }

    /**
     * @return \BIT\Core\Services\User
     */
    private function getUser()
    {
        return $this->serviceLocator->getService('user');
    }

    /**
     * @return \BIT\Core\Services\Request
     */
    private function getRequest()
    {
        return $this->serviceLocator->getService('request');
    }
}
