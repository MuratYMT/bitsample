<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.03.2017
 * Time: 11:34
 */
use BIT\Core\Alert;
use BIT\Models\Entity\Account;
use BIT\Models\Entity\Operation;
use BIT\Models\Entity\User;

/**
 * @var User $user
 * @var Operation[] $operations
 * @var Account $account
 */

?>


<h1>Счет</h1>
<?= new Alert() ?>
<div>
    <strong>Баланс счета: <?= number_format($account->balance, 2) ?></strong>
</div>
<div>
    <a href="/index/replenish">Пополнить</a>
    <a href="/index/withdrawal">Вывести</a>
</div>
<table>
    <thead>
    <tr>
        <th>Референц</th>
        <th>Операция</th>
        <th>Дата операции</th>
        <th>Сумма операции</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($operations as $operation) : ?>
        <tr>
            <td>REF<?= $operation->operationId ?></td>
            <td><?= $operation->description ?></td>
            <td><?= $operation->dateOperation ?></td>
            <td><?= number_format($operation->debId === $user->getAccountId() ? $operation->amount : -$operation->amount, 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
