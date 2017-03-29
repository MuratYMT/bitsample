<?php

namespace BIT\Core\FormHelpers;

use BIT\Core\App;
use BIT\Core\Helper;
use BIT\Core\Services\Request;

/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 21:48
 */

/**
 * Class Form хелпер отрисовки форм
 * @package BIT\Core\FormHelpers
 */
class Form
{
    /**
     * начало формы
     * @param \BIT\Forms\AbstractForm $form
     * @param array $options
     * @return string
     */
    public static function begin($form, array $options = [])
    {
        /** @var Request $request */
        $request = App::serviceLocator()->getService('request');

        $options['method'] = $form->getMethod();

        $result = '<form ' . Helper::renderTagAttributes($options) . '>';
        if (strtolower($form->getMethod()) === 'post') {
            $result .= '<input name="__CSRF" type="hidden" value="' . $request->getCsrfToken() . '">';
        }
        return $result;
    }

    /**
     * конец формы
     * @return string
     */
    public static function end()
    {
        return '</form>';
    }
}