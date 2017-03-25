<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 11:26
 */

namespace BIT\Core;

/**
 * Class Alert флеш сообщения
 * @package BIT\Core
 */
class Alert
{
    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $flashes = App::getSession()->getFlash();

        $result = '';
        foreach ($flashes as $flash) {
            $result .= '<p class="bg-info">' . $flash . '</p>';
        }
        return $result;
    }

    /**
     * добавить флеш сообщение
     * @param $alert
     */
    public static function show($alert)
    {
        App::getSession()->addFlash($alert);
    }
}