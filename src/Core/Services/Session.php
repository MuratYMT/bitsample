<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 10:08
 */

namespace BIT\Core\Services;

class Session
{
    private $session;

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
        $this->session = $_SESSION;


        if (isset($this->session['__destroyed'])) {
            if ($this->session['__destroyed'] < time() - 3600) {
                //с последнего обращения прошло больше часа разлогиниваем
                session_unset();
            }
        } else {
            //попытка фиксации сесссии ?
            $this->regenerateId();
        }

        $this->session['__destroyed'] = time();
        session_write_close();
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
        ini_set('session.use_cookies', false);
        ini_set('session.cache_limiter', null);
        session_start();
        $_SESSION = $this->session;
        session_write_close();
    }

    /**
     * устанавливает переменную сессии
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $this->session[$name] = $value;
    }

    /**
     * удалаяетп еременную
     * @param string $name
     */
    public function del($name)
    {
        if (isset($this->session[$name]) || array_key_exists($name, $this->session)) {
            unset($this->session[$name]);
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
        if (isset($this->session[$name]) || array_key_exists($name, $this->session)) {
            return $this->session[$name];
        }
        return $default;
    }

    /**
     * добавляет флеш сообщение
     * @param $value
     */
    public function addFlash($value)
    {
        $this->session['__FLASH'][] = $value;
    }

    /**
     * извлекает накопленные флеш сообщения
     * @return array
     */
    public function getFlash()
    {
        if (isset($this->session['__FLASH'])) {
            $flash = $this->session['__FLASH'];
            unset($this->session['__FLASH']);
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
