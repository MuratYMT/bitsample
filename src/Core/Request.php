<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:08
 */

namespace BIT\Core;

/**
 * Class Request обработка входных данных
 * @package BIT\Core
 */
class Request
{
    /**
     * каким методом пришел запрос
     * @return string
     */
    public function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }

        return 'GET';
    }

    /**
     * POST запрос?
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * GET запрос?
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * данные из POST запроса
     * @param string $name если задано то ищет определенный параметр в запросе если нет то выдает весь массив параметров
     * @return array|null
     */
    public function post($name = null)
    {
        if ($this->isPost()) {
            if ($name === null) {
                return $_POST;
            }
            if (isset($_POST[$name]) || array_key_exists($name, $_POST)) {
                return $_POST[$name];
            }
        }
        return null;
    }

    private $_csrtToken;

    /**
     * csrf токен текущего запроса
     * @return string
     */
    public function getCsrfToken()
    {
        if ($this->_csrtToken === null) {
            if (null === ($token = $this->loadCsrfToken())) {
                $token = $this->generateCsrfToken();
            }

            $mask = Helper::generateRandomString(16);
            $this->_csrtToken = $mask . md5($mask . $token);
        }
        return $this->_csrtToken;
    }

    /**
     * проверка токена на валидность
     * @param $token
     * @return bool
     */
    public function validateCsrfToken($token)
    {
        if (!is_string($token)) {
            return false;
        }
        $trueToken = $this->loadCsrfToken();
        $mask = mb_substr($token, 0, 16);
        $hash = mb_substr($token, 16);
        return $hash === md5($mask . $trueToken);
    }

    /**
     * токен сессии
     * @return string
     */
    private function loadCsrfToken()
    {
        return App::getSession()->get('__CSRFTOKEN');
    }

    /**
     * генерирует случайный токен
     * @return string
     */
    private function generateCsrfToken()
    {
        $token = Helper::generateRandomString(16);
        App::getSession()->set('__CSRFTOKEN', $token);
        return $token;
    }
}