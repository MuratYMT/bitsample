<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 18:50
 */

namespace BIT\Core\Services;

use BIT\Core\Helper;

class View
{
    /** @var Request */
    protected $request;
    private $__template;
    private $__params;

    public $viewFolder;

    private $__file;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function render(array $params = [])
    {
        $this->__file = $this->resolveTemplate();
        $this->__params = $params;
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
            $controller = $this->request->getController();
            $refl = new \ReflectionObject($controller);
            $folder = $this->viewFolder . '/' . Helper::camel2id(Helper::rTrimWord($refl->getShortName(), 'Controller'));
            $file = Helper::camel2id(Helper::rTrimWord($this->request->getAction(), 'Action')) . '.php';
            return $folder . '/' . $file;
        }

        throw new \ErrorException();
    }
}
