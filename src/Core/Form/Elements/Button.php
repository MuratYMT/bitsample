<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 22:20
 */

namespace BIT\Core\Form\Elements;

class Button extends AbstractFormElement
{
    public $type = 'submit';

    /**
     * тип элемента формы
     * @return string
     */
    public function type()
    {
        return 'button';
    }
}