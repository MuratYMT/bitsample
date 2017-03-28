<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 10:25
 */

namespace BIT\Core;

use BIT\Models\Services\UserManager;

class User
{
    /** @var Session */
    public $session;

    private $userId = 0;

    /** @var  \BIT\Models\Entity\User */
    private $identity;

    public function __construct()
    {
        $this->init();
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
    }

    public function init()
    {
        $userId = (int)$this->session->get('__USERID', 0);

        if ($userId !== 0) {
            /**
             * @TODO выкинуть код поиска пользователя из класса для слабой связанности
             */
            $identity = UserManager::findOne($userId);
            if ($identity === null) {
                $this->userId = 0;
            } else {
                $this->userId = $userId;
                $this->identity = $identity;
            }
        }
    }

    /**
     * авторизация пользователя
     * @param \BIT\Models\Entity\User $identity
     */
    public function login($identity)
    {
        $this->identity = $identity;
        $this->userId = $identity->id;
        $this->session->set('__USERID', $this->userId);
        $this->session->regenerateId();
    }

    /**
     * логаут
     */
    public function logout()
    {
        $this->userId = 0;
        $this->identity = null;
        $this->session->del('__USERID');
        $this->session->regenerateId();
    }

    /**
     * текущий пользователь гость?
     * @return bool
     */
    public function isGuest()
    {
        return $this->userId === 0;
    }

    /**
     * Сущность текущего пользавателя
     * @return \BIT\Models\Entity\User|null null если гость
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}