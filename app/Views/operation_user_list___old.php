<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
?>
<div id="operation_list">
	<div class="text-center">
		<h2>Операції ( <?= $op_user->name; ?> <?= $op_user->surname; ?>) <a
				href="https://www.youtube.com/embed/-hNrclKfgfI" class="play_video_instruction"><img
					src="../../img/yt.png"/></a></h2>
		<p style="color:red;font-size:16px;">На сторінці проводяться технічні роботи, зайдіть, будь ласка, пізніше</p>

	</div>
<!--<pre>-->
<!--	--><?php //var_dump($operation_list); ?>
<!--</pre>-->
	<div class="row">

		<div class="col-md-12 operation_table_div">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<?php if ($not_this_user): ?>
						<th scope="col">Контрагент&nbsp;1</th>
					<?php endif; ?>
					<th scope="col">Сума</th>
					<th scope="col">Контрагент<?= $not_this_user ? '&nbsp;2' : ''; ?></th>
					<th scope="col">Коментар</th>
					<th scope="col">Проект</th>
					<th scope="col">Курс валют</th>
					<th scope="col">Заявка</th>
					<th scope="col">Стаття</th>
					<?php if (!empty($user_wallets)): ?>
						<?php foreach ($user_wallets as $wallet): ?>
							<th scope="col"><?= $wallet['name']; ?></th>
						<?php endforeach; ?>
					<?php endif; ?>
					<th scope="col">Дата</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($operation_list)): ?>
					<?php foreach ($operation_list

								   as $operation): ?>
						<tr data-operation_id="<?= $operation['id']; ?>">
							<td><?= $operation['id']; ?></td>
							<?php if ($not_this_user): ?>
								<td><?= key_exists($operation['contractor1_id'], $contractors) ? $contractors[$operation['contractor1_id']] : ''; ?></td>
							<?php endif; ?>
							<td>
								<div class="operation_amount"
									 style="background-color:<?= $op_style[$operation['operation_type_id']][0]; ?>; border:3px solid <?= $op_style[$operation['operation_type_id']][1]; ?>; min-width:100px">
									<?= $operation['amount1']; ?>
									&nbsp;<?= key_exists($operation['currency1'], $currencies)
										? $currencies[$operation['currency1']] : ''; ?>
							</td>
							<?php if ($not_this_user): ?>
								<td>
									<?= key_exists($operation['contractor2_id'], $contractors) ? $contractors[$operation['contractor2_id']] : ''; ?>
								</td>
							<?php else: ?>
								<?php if ($operation['operation_type_id'] == 1 || $operation['operation_type_id'] == 6): ?>
									<td>
										<?= key_exists($operation['contractor1_id'], $contractors) ? $contractors[$operation['contractor1_id']] : ''; ?>
									</td>
								<?php else: ?>
									<td>
										<?= key_exists($operation['contractor2_id'], $contractors) ? $contractors[$operation['contractor2_id']] : ''; ?>
									</td>
								<?php endif; ?>
							<?php endif; ?>


							<td><?= $operation['comment']; ?></td>
							<td><?= $operation['project_name']; ?></td>
							<td><?= $operation['rate']; ?></td>
							<td>
								<!--								--><?php //if ($operation['app_id'] != 0): ?>
								<!--									-->
								<? //= $operation['app_id']; ?><!--. --><? //= $operation['app_product'] ?>
								<!--									(-->
								<? //= $operation['app_author_surname']; ?><!-- -->
								<? //= substr($operation['app_author_name'], 0, 2); ?><!--. )-->
								<!--								--><?php //endif; ?>
								<select class="form-control" id="applications" name="application_id" required="">
									<option value=""></option>
									<?php if (!empty($applications)): ?>
										<?php foreach ($applications as $application): ?>
											<option <?= $operation['app_id'] == $application['id'] ? 'selected' : '' ?>
													value="<?= $application['id']; ?>"><?= $application['product']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</td>
							<td>
								<select class="form-control" id="expense_list" name="article_id" required="">
									<option value=""></option>
									<?php if (!empty($expense_list)): ?>
										<?php foreach ($expense_list as $expense_list_item): ?>
											<option
												<?= $operation['article_id'] == $expense_list_item['id'] ? 'selected' : '' ?>
												value="<?= $expense_list_item['id']; ?>"><?= $expense_list_item['item']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</td>
							<td>
								<?php if (!empty($user_wallets)): ?>
									<?php foreach ($user_wallets as $wallet): ?>
										<?php if ($operation['operation_type_id'] == 6): // ?>
											<?php if (!empty($operation['wallet_2-checkout'])): ?>

												<?= $operation['wallet_2-checkout']; ?>&nbsp;<?= key_exists($operation['currency2'], $currencies)
													? $currencies[$operation['currency2']] : $currencies[$operation['currency1']]; ?>
											<?php endif; ?>

										<?php else: ?>
											<?= $operation['balance1']; ?>&nbsp;<?= key_exists($operation['currency1'], $currencies)
												? $currencies[$operation['currency1']] : ''; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td><?= date("d.m.Y H:m:i", $operation['date']); ?></td>
							<td>
								<?php if ($operation['operation_type_id'] != 6 && !$not_this_user) : ?>
									<img class="delete_operation" src="../../img/trash-icon.jpg"/>
								<?php endif; ?>
							</td>

						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<!--				<tr>-->
				<!--					<td colspan="6"></td>-->
				<!--					<td colspan="2"><b>Баланс</b></td>-->
				<!--					<td></td>-->
				<!--					<td></td>-->
				<!--				</tr>-->
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-12">

		<?php if (!empty($start_balance)): ?>
		<p>Баланс на 1.01.20:</p>
		<table class="table" border="1px solid">
			<thead>
			<tr>
				<th>Каса</th>
				<th>Баланс</th>
				<th>Валюта</th>
				<!--				<th>Курс валют</th>-->
				<!--				<th>Сума в гривні </th>-->
			</tr>
			</thead>
			<tbody>
			<?php foreach ($start_balance as $wallet): ?>
				<tr>
					<td><?= $wallet['user_name']; ?> <? //= $wallet['wallet']['user_surname']; ?>
						( <?= $wallet['name']; ?> )
					</td>
					<td><?= (float)$wallet['balance']; ?></td>
					<td><?= $wallet['currency']; ?></td>

				</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>

	</div>
</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>

