<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал


?>
<div id="operation_list">
	<div class="text-center">

		<h2>Операції</h2>
	</div>

	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Контрагент&nbsp;1</th>
					<th scope="col">Сума</th>
					<th scope="col">Валюта</th>
					<!--				<th scope="col">Тип</th>-->
<!--					<th scope="col">Сума_2</th>-->
<!--					<th scope="col">Валюта_2</th>-->
<!--					<th scope="col">Контрагент_2</th>-->
					<th scope="col">Дата</th>
					<th scope="col">Заявка</th>
					<th scope="col">Проект</th>
					<th scope="col">Курс валют</th>
					<th scope="col">Коментар</th>
					<th scope="col">Статус</th>
					<th scope="col">Баланс(готівка)</th>
					<th scope="col">Баланс(ТОВ)</th>
					<th scope="col">Статус</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($operation_list)): ?>
					<?php foreach ($operation_list as $operation): ?>
						<tr>
							<td><?= $operation['id']; ?></td>
							<td><?= key_exists($operation['contractor1_id'], $contractors) ? $contractors[$operation['contractor1_id']] : ''; ?></td>
							<td>
								<div class="operation_amount"
									 style="background-color:<?= $op_style[$operation['operation_type_id']]; ?>"><?= $operation['amount1']; ?></div>
							</td>
							<td><?= key_exists($operation['currency1'], $currencies) ? $currencies[$operation['currency1']] : ''; ?></td>
							<!--						<td>--><? //= $operation['operation_type_id']; ?><!--</td>-->
<!--							--><?// if ($operation['operation_type_id'] != 1): ?>
<!--								<td>-->
<!--									<div class="operation_amount"-->
<!--										 style="background-color:--><?//= $op_style[$operation['operation_type_id']]; ?><!--">--><?//= $operation['amount2']; ?><!--</div>-->
<!--								</td>-->
<!--								<td>--><?//= key_exists($operation['currency2'], $currencies) ? $currencies[$operation['currency2']] : ''; ?><!--</td>-->
<!--								<td>--><?//= key_exists($operation['contractor2_id'], $contractors) ? $contractors[$operation['contractor2_id']] : ''; ?><!--</td>-->
<!--							--><?php //else: ?>
<!--								<td></td>-->
<!--								<td></td>-->
<!--								<td></td>-->
<!--							--><?php //endif; ?>
							<td><?= date("d.m.Y", $operation['date']); ?></td>
							<td>#<?= $operation['app_id']; ?>. <?= $operation['app_product'] ?>
								(<?= $operation['app_author_surname']; ?> <?= substr($operation['app_author_name'], 0, 1); ?>)
							</td>
							<td><?= $operation['project_name']; ?></td>
							<td><?= $operation['rate']; ?></td>
							<td><?= $operation['comment']; ?></td>
							<td><?= $operation['status']; ?></td>

							<td></td>


						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'footer.php' ?>
