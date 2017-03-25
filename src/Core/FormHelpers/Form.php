<?php

namespace BIT\Core\FormHelpers;

use BIT\Core\App;
use BIT\Core\Helper;

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
     * @param \BIT\Core\Form $form
     * @param array $options
     * @return string
     */
    public static function begin($form, $options = [])
    {
        $options['method'] = $form->getMethod();

        $result = '<form ' . Helper::renderTagAttributes($options) . '>';
        if (strtolower($form->getMethod()) === 'post') {
            $result .= '<input name="__CSRF" type="hidden" value="' . App::getRequest()->getCsrfToken() . '">';
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