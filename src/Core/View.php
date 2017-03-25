<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:50
 */

namespace BIT\Core;

class View
{
    private $__template;
    private $__params;

    /**
     * View constructor.
     * @param array $params параметры для передачи в шаблон
     */
    public function __construct($params = [])
    {
        $this->__params = $params;
    }

    private $__file;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $this->__file = $this->resolveTemplate();
        extract($this->__params, EXTR_OVERWRITE);
        ob_start();
        /** @noinspection PhpIncludeInspection */
        include $this->__file;
        return ob_get_clean();
    }

    /**
     * определяет какой файл является шаблоном
     * @return string
     * @throws \ErrorException
     */
    public function resolveTemplate()
    {
        if ($this->__template === null) {
            $controller = App::getController();
            $refl = new \ReflectionObject($controller);
            $folder = self::getViewsFolder() . '/' . Helper::camel2id(Helper::rTrimWord($refl->getShortName(), 'Controller'));
            $file = Helper::camel2id(Helper::rTrimWord(App::getAction(), 'Action')) . '.php';
            return $folder . '/' . $file;
        }

        throw new \ErrorException();
    }

    /**
     * папка где хранятся файлы шаблонов
     * @return string
     */
    private static function getViewsFolder()
    {
        $conf = App::$config;
        if (isset($conf['view']['viewFolder'])) {
            return $conf['view']['viewFolder'];
        }

        return __DIR__ . '/../Views';
    }
}
