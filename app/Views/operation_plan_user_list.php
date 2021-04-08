<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
?>
<div id="operation_list">
	<div class="text-center">
		<h2>Операції ( <?= $op_user->name; ?> <?= $op_user->surname; ?>) <a
				href="https://www.youtube.com/embed/-hNrclKfgfI" class="play_video_instruction"><img
					src="../../img/yt.png"/></a></h2>
		<!--		<p style="color:red;font-size:16px;">На сторінці проводяться технічні роботи, зайдіть, будь ласка, пізніше</p>-->

	</div>
	<div class="filter_btns row">
		<div class="col-md-10">
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[1][0]; ?>; border:3px solid <?= $op_style[1][1]; ?>">100
				</div>
				<span>дохід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[2][0]; ?>; border:3px solid <?= $op_style[2][1]; ?>">100
				</div>
				<span>розхід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[4][0]; ?>; border:3px solid <?= $op_style[4][1]; ?>">100
				</div>
				<span>кредит</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[5][0]; ?>; border:3px solid <?= $op_style[5][1]; ?>">100
				</div>
				<span>переміщення від співробітника компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[6][0]; ?>; border:3px solid <?= $op_style[6][1]; ?>">100
				</div>
				<span>переміщення співробітнику компанії</span>
			</div>
		</div>
		<div class="col-md-2">
			<input id="search" class="form-control" placeholder="Пошук"/>
		</div>
	</div>

	<div class="row">

		<div class="col-md-12 operation_table_div">
			<table class="table">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Контрагент&nbsp;1</th>
					<th scope="col">Сума</th>
					<th scope="col">Контрагент&nbsp;2</th>
					<th scope="col">Коментар</th>
					<th scope="col">Проект</th>
					<!--					<th scope="col">Курс валют</th>-->
					<th style="width:150px" scope="col">Заявка</th>
					<th style="width:150px" scope="col">Стаття</th>
					<?php if (!empty($user_wallets)): ?>
						<?php foreach ($user_wallets as $wallet): ?>
<!--							--><?php //if ($wallet['checkout'] != 0): ?>
								<th scope="col">баланс<br/><?= $wallet['name']; ?></th>
<!--							--><?php //endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<th scope="col">Дата</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($operation_list)): ?>
					<?php foreach ($operation_list as $timestamp => $date_block): ?>

						<?php if (!empty($date_block['operation'])) : ?>
							<?php foreach ($date_block['operation'] as $operation): ?>
							<?php if(!in_array($operation['id'], [2117,2116])) :?>
								<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
									<td><?= $operation['id']; ?></td>
									<td class="filter_td"><?= $operation['contractor_name'] ?></td>
									<td>
										<div class="operation_amount"
											 style="background-color:<?= $op_style[$operation['operation_type_id']][0]; ?>; border:3px solid <?= $op_style[$operation['operation_type_id']][1]; ?>; min-width:100px">
											<?= $operation['amount']; ?>
											&nbsp;<?= key_exists($operation['currency'], $currencies)
												? $currencies[$operation['currency']] : ''; ?>
									</td>
									<td class="filter_td"><?= $operation['contractor2_name'] ?></td>
									<td class="filter_td"><?= $operation['comment']; ?></td>
									<td class="filter_td"><?= $operation['project_name']; ?></td>
									<!--							<td>--><? //= $operation['rate']; ?><!--</td>-->
									<td>
										<select style="width:150px" class="form-control operation_application_id"
												id="applications" name="application_id" required="">
											<option value=""></option>
											<?php if (!empty($applications)): ?>
												<?php foreach ($applications as $application): ?>
													<option
														<?= $operation['app_id'] == $application['id'] ? 'selected' : '' ?>
														value="<?= $application['id']; ?>"><?= $application['product']; ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</td>
									<td>
										<?php if ($operation['operation_type_id'] == 1):
											$articles = $income_list;
											$select = true;
										elseif ($operation['operation_type_id'] == 2):
											$articles = $expense_list;
											$select = true;
										else:
											$select = false;
										endif; ?>
										<?php if ($select): ?>
											<select class="form-control article_id"
													name="article_id" required="">

												<option value="">Не вибрана стаття</option>

												<?php if (!empty($articles)): ?>
													<?php foreach ($articles as $article_item): ?>
														<optgroup label="<?= $article_item['name']; ?>">
															<?php if (!empty($article_item['children'])): ?>
																<?php foreach ($article_item['children'] as $article_1): ?>
																	<option
																		<?= $operation['article_id'] == $article_1['id'] ? 'selected' : '' ?>
																		value="<?= $article_1['id']; ?>"><?= $article_1['name']; ?></option>
																<?php endforeach; ?>
															<?php else: ?>
																<option
																	<?= $operation['article_id'] == $article_item['id'] ? 'selected' : '' ?>
																	value="<?= $article_item['id']; ?>"><?= $article_item['name']; ?></option>

															<?php endif; ?>
														</optgroup>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										<?php else: ?>
											<?php if ($operation['operation_type_id'] == 4): ?>
												<span>Кредит</span>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<?php if (!empty($user_wallets)): ?>
										<?php foreach ($user_wallets as $wallet): ?>
<!--											--><?php //if ($wallet['checkout'] != 0): ?>
												<td>
													<?php if ($operation['wallet_id'] == $wallet['id']): ?>
														<?= $operation['wallet_checkout']; ?>
													<?php endif; ?>
												</td>
<!--											--><?php //endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>

									<td><?= date('d.m.Y', $operation['date']); ?></td>
									<td>
										<img class="delete_operation" src="../../img/trash-icon.jpg"/>
									</td>

								</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php elseif (!empty($date_block['checkout'])): ?>

							<tr class="report_tr">
								<td colspan="8">Баланс каси на кінець звітного періоду</td>
								<?php if (!empty($user_wallets)): ?>
									<?php foreach ($user_wallets as $wallet): ?>
										<td>
											<?php foreach ($date_block['checkout'] as $checkout): ?>
												<?php if ($checkout['wallet_id'] == $wallet['id']): ?>
													<?= $checkout['amount']; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
									<?php endforeach; ?>
								<?php endif; ?>
								<td>
									<?= date('d.m.Y', $timestamp); ?>
								</td>
								<td></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>

