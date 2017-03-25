<?php
/**
 * Created by PhpStorm.
 * User: murat
 * Date: 23.03.2017
 * Time: 19:09
 */
use BIT\Core\Alert;
use BIT\Core\FormHelpers\Form;
use BIT\Core\FormHelpers\FormElement;

/**
 * @var $form
 */
?>

<h1>Вход</h1>
<?= new Alert ?>
<?= Form::begin($form) ?>
<?= new FormElement($form, 'login') ?>
<?= new FormElement($form, 'password') ?>
<?= new FormElement($form, 'submit') ?>
<?= Form::end() ?>
