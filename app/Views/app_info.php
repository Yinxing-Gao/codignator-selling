<div class="app_info" >
	<?php if (!empty($application)): ?>
		<table>
			<tr>
				<th>ID</th>
				<th>Дата створення</th>
				<th>Дата на коли</th>
				<th>Сума</th>
				<th>Валюта</th>
				<th>Тип</th>
				<th>Автор</th>
				<th>Департамент</th>
				<th>Товар/послуга</th>
			</tr>
			<tr>
				<td><?= $application->id ?></td>
				<td><?= date('d.m.Y H:i:s', $application->date) ?></td>
				<td><?= date('d.m.Y', $application->date_for) ?></td>
				<td><?= $application->amount; ?></td>
				<td><?= $application->currency; ?></td>
				<td><?= $types[$application->type_id]; ?></td>
				<td><?= $application->author; ?></td>
				<td><?= $application->department ?></td>
				<td><?= $application->product ?></td>
			</tr>
		</table>
		<br/>
		<table>
			<tr>
				<th>Проект</th>
				<th>Стаття витрат</th>
				<th>Ситуація</th>
				<th>Рахунок</th>
				<th>Номер рахунку</th>
				<th>Дані</th>
				<th>Рішення</th>
				<th>Категорії</th>
			</tr>
			<tr>
				<td><?= $application->project_name ?></td>
				<td><?= $application->expense_item ?></td>
				<td><?= $application->situation ?></td>
				<td>
					<?php if (!empty($application->order_files)) : ?>
						<?php $files = json_decode(stripslashes($application->order_files)); ?>
						<?php foreach ($files as $file): ?>
							<span>
									<a href="<?= $file; ?>" class="order_file"
									   target="_blank"><?= substr($file, strripos($file, '/') + 1); ?></a>
									</span>
						<?php endforeach; ?>
					<?php endif; ?>
				</td>
				<td><?= $application->order_names ?></td>
				<td><?= $application->data ?></td>
				<td><?= $application->decision ?></td>
				<td><?= $application->category ?></td>
			</tr>
		</table>
		<?php if (!empty($operations)): ?>
			<h2 class="text-center">Операції</h2>
			<div class="table_container">
				<table>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Контрагент_1</th>
						<th scope="col">Сума</th>
						<th scope="col">Контрагент_2</th>
						<th scope="col">Дата</th>
						<th scope="col">Проект</th>
						<th scope="col">Курс валют</th>
						<th scope="col">Коментар</th>
					</tr>

					<?php foreach ($operations as $operation): ?>
						<tr>
							<td><?= $operation['id']; ?></td>
							<td><?= key_exists($operation['contractor1_id'], $contractors) ? $contractors[$operation['contractor1_id']] : ''; ?></td>
							<td>
								<div class="operation_amount"
									 style="background-color:<?= $op_style[$operation['operation_type_id']]; ?>"><?= $operation['amount1']; ?>
									&nbsp;<?= key_exists($operation['currency1'], $currencies) ? $currencies[$operation['currency1']] : ''; ?></div>
							</td>
							<td><?= key_exists($operation['contractor2_id'], $contractors) ? $contractors[$operation['contractor2_id']] : ''; ?></td>
							<td><?= date("d.m.Y", $operation['date']); ?></td>
							<td><?= $operation['project_name']; ?></td>
							<td><?= $operation['rate']; ?></td>
							<td><?= $operation['comment']; ?></td>
<!--							<td>--><?//= $operation['status']; ?><!--</td>-->
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<!--
Номер і назва заявки
-всі дані, ситуація і рішення
-всі операції по цій заявці


-->

