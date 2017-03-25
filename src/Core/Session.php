<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 10:08
 */

namespace BIT\Core;

class Session
{
    public function __construct()
    {
        $this->open();
        register_shutdown_function([$this, 'close']);
    }

    /**
     * старт сесии
     */
    public function open()
    {
        session_start();

        if (isset($_SESSION['__destroyed'])) {
            if ($_SESSION['__destroyed'] < time() - 3600) {
                //с последнего обращения прошло больше часа разлогиниваем
                session_unset();
            }
        } else {
            //попытка фиксации сесссии ?
            $this->regenerateId();
        }

        $_SESSION['__destroyed'] = time();
    }

    /**
     * регенерирует ид сессии
     */
    public function regenerateId()
    {
        session_regenerate_id(true);
    }

    /**
     * записать данные в хранилище закрыть сесиию
     */
    public function close()
    {
        session_write_close();
    }

    /**
     * устанавливает переменную сессии
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * удалаяетп еременную
     * @param string $name
     */
    public function del($name)
    {
        if (isset($_SESSION[$name]) || array_key_exists($name, $_SESSION)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * получает переменную сессии
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name]) || array_key_exists($name, $_SESSION)) {
            return $_SESSION[$name];
        }
        return $default;
    }

    /**
     * добавляет флеш сообщение
     * @param $value
     */
    public function addFlash($value)
    {
        $_SESSION['__FLASH'][] = $value;
    }

    /**
     * извлекает накопленные флеш сообщения
     * @return array
     */
    public function getFlash()
    {
        if (isset($_SESSION['__FLASH'])) {
            $flash = $_SESSION['__FLASH'];
            unset($_SESSION['__FLASH']);
            return $flash;
        }
        return [];
    }

    /**
     * уничтожает сесиию
     */
    public function destroy()
    {
        $this->close();
        session_unset();
        session_destroy();
        $this->open();
    }
}
