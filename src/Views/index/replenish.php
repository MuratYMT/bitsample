<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 12:14
 */
use BIT\Core\Alert;
use BIT\Core\FormHelpers\Form;
use BIT\Core\FormHelpers\FormElement;
use BIT\Forms\ReplenishAbstractForm;

/**
 * @var $form ReplenishAbstractForm
 */

?>

<h1>Пополнить счет</h1>
<?= new Alert() ?>
<?= Form::begin($form) ?>
<?= new FormElement($form, 'account') ?>
<?= new FormElement($form, 'amount') ?>
<?= new FormElement($form, 'submit') ?>
<?= Form::end() ?>

