<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 22:00
 */

namespace BIT\Core\FormHelpers;

use BIT\Core\Form\Elements\Button;
use BIT\Core\Helper;
use BIT\Forms\AbstractForm;

/**
 * Class FormElement хелпер отрисовки элементов форм
 * @package BIT\Core\FormHelpers
 */
class FormElement
{
    /** @var  AbstractForm */
    protected $form;
    /** @var  string */
    protected $element;
    /** @var  array */
    protected $options = [];

    public function __construct($form, $element, array $options = [])
    {
        $this->init($form, $element, $options);
    }

    /**
     * @param AbstractForm $form
     * @param string $element
     * @param array $options опции
     */
    protected function init($form, $element, $options)
    {
        $this->form = $form;
        $this->element = $element;
        $this->options = $options;

    }

    public function __toString()
    {
        return $this->render();
    }

    protected function render()
    {
        switch ($this->form->getElement($this->element)->type()) {
            case 'text':
                return $this->textInput('text');
            case 'password':
                return $this->textInput('password');
            case 'button':
                return $this->button();
            default:
                return '';
        }
    }

    protected function button()
    {
        /** @var Button $formElement */
        $formElement = $this->form->getElement($this->element);
        $options = $this->getOptions();
        $options['type'] = $formElement->type;
        $options['class'] = 'btn btn-default';
        return '<button ' . Helper::renderTagAttributes($options) . '>' . $formElement->label . '</button>';
    }

    protected function textInput($type)
    {
        $options = $this->getOptions();
        $options['type'] = $type;
        $options['value'] = htmlentities($this->form->getElement($this->element)->value);
        $result = '<div class="form-group">
            <label for="' . $options['id'] . '">' . $this->form->getElement($this->element)->label . '</label>
            <input ' . Helper::renderTagAttributes($options) . '>';
        if ($this->form->hasError($this->element)) {
            $result .= '<ul class="help-block help-block-error">';
            foreach ($this->form->getError($this->element) as $error) {
                $result .= '<li>' . $error . '</li>';
            }
            $result .= '</ul>';
        }
        $result .= '</div>';
        return $result;
    }

    protected function getOptions()
    {
        $options = $this->options;
        $options['id'] = Helper::getInputId($this->form, $this->element);
        $options['name'] = Helper::getInputName($this->form, $this->element);
        return $options;
    }
}