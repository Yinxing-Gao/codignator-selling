<div id="operation_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Операції ( <?= $op_user->name; ?> <?= $op_user->surname; ?>)</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нового ліда">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<div class="filter_btns row">
		<div class="col-md-2">
			<select class="form-control operation_application_id" id="selected_wallet_id">
				<?php if (!empty($user_wallets)): ?>
					<?php foreach ($user_wallets as $user_wallet): ?>
						<option value="<?= $user_wallet['id']; ?>">
							<?= $user_wallet['name']; ?>
						</option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-8">
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[1][0]; ?>; border:2px solid <?= $op_style[1][1]; ?>">100
				</div>
				<span>дохід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[2][0]; ?>; border:2px solid <?= $op_style[2][1]; ?>">100
				</div>
				<span>розхід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[4][0]; ?>; border:2px solid <?= $op_style[4][1]; ?>">100
				</div>
				<span>кредит</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[5][0]; ?>; border:2px solid <?= $op_style[5][1]; ?>">100
				</div>
				<span>переміщення від співробітника компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[6][0]; ?>; border:2px solid <?= $op_style[6][1]; ?>">100
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
					<th scope="col">Контрагент</th>
					<th scope="col">Сума</th>
					<th scope="col">Коментар</th>
					<th scope="col">Проект</th>
					<!--					курс валют виводити при наведенні на суму-->
					<th style="width:150px" scope="col">Заявка</th>
					<th style="width:150px" scope="col">Стаття</th>
					<?php if (!empty($user_wallets)): ?>
						<?php foreach ($user_wallets as $wallet): ?>
							<th scope="col">баланс<br/><?= $wallet['name']; ?></th>
						<?php endforeach; ?>
					<?php endif; ?>
					<th scope="col">Дата</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>

				<?php if (!empty($operation_list)): ?>
					<?php foreach ($operation_list

								   as $timestamp => $date_block): ?>
						<?php if (!empty($date_block['operation'])) : ?>
							<?php foreach ($date_block['operation'] as $operation): ?>
								<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
									<td data-label="#"><?= $operation['id']; ?></td>
									<td data-label="Контрагент"
										class="filter_td"><?= $operation['contractor_name'] ?></td>
									<td data-label="Сума">
										<div
											class="operation_amount <?= !empty($operation['amount2']) && !empty($operation['currency2']) ? 'self' : '' ?>"
											style="color:<?= $op_style[$operation['operation_type_id']][0]; ?>; border:2px solid <?= $op_style[$operation['operation_type_id']][1]; ?>; min-width:100px">
											<?= $operation['amount']; ?>
											&nbsp;<?= key_exists($operation['currency'], $currencies)
												? $currencies[$operation['currency']] : ''; ?>

											<?php if (!empty($operation['amount2']) && !empty($operation['currency2'])): ?>
												<br/>
												<?= $operation['amount2']; ?>
												&nbsp;<?= key_exists($operation['currency2'], $currencies)
													? $currencies[$operation['currency2']] : ''; ?>
											<?php endif; ?>
									</td>
									<td data-label="Коментар"
										class="filter_td"><?= htmlspecialchars_decode($operation['comment']); ?></td>
									<td data-label="Проект" class="filter_td"><?= $operation['project_name']; ?></td>
									<td data-label="Заявка">
										<?php if ($operation['operation_type_id'] == 2 ||
											$operation['operation_type_id'] == 5 ||
											$operation['operation_type_id'] == 6): ?>
											<select class="form-control operation_application_id" id="applications"
													name="application_id" required="">
												<option value=""></option>
												<?php if (!empty($applications)): ?>
													<?php foreach ($applications as $application): ?>
														<option
															<?= $operation['app_id'] == $application['id'] ? 'selected' : '' ?>
															value="<?= $application['id']; ?>">
															<?= $application['product']; ?>
														</option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										<?php endif; ?>
										<?php if (!empty($operation['app_id'])): ?>
											<img class="set_app_as_template_for_this_operation"
												 src="../../../icons/bootstrap/bookmark-plus.svg">
										<?php endif; ?>
									</td>
									<td data-label="Стаття">
										<?php if ($operation['operation_type_id'] == 1):
//											$articles = $income_list;
											$select = true;
										elseif ($operation['operation_type_id'] == 2):
//											$articles = $expense_list;
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
										<?php if (!empty($operation['article_id'])): ?>
											<img src="../../../icons/bootstrap/bookmark-plus.svg">
										<?php endif; ?>
									</td>

									<?php if (!empty($user_wallets)): ?>
										<?php foreach ($user_wallets as $wallet): ?>
											<td>
												<?php if ($operation['wallet_id'] == $wallet['id']): ?>
													<?= number_format($operation['wallet_checkout'], 2, ',', '&nbsp;'); ?>
												<?php elseif (!empty($operation['wallet_2_id']) && $operation['wallet_2_id'] == $wallet['id']): ?>
													<?= number_format($operation['wallet_2_checkout'], 2, ',', '&nbsp;'); ?>
												<?php endif; ?>
											</td>
										<?php endforeach; ?>
									<?php endif; ?>
									<td data-label="Дата">
										<input type="date" class="form-control date" placeholder=""
											   name="date_for"
											   value="<?= !empty($operation['date']) ? date("Y-m-d", $operation['date']) : ''; ?>"
											   required="">
									</td>
									<td data-label="">
										<img class="edit_operation icon" src="../../../icons/fineko/edit.svg"/>
									</td>
									<td data-label="">
										<img class="delete_operation icon" src="../../../icons/fineko/delete.svg"/>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr class="report_tr">
								<td colspan="9">Баланс каси на кінець звітного періоду</td>
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
								<td colspan="2"></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-12">

		<!--		--><?php //if (!empty($start_balance)): ?>
		<!--		<p>Баланс на 1.01.20:</p>-->
		<!--		<table class="table" border="1px solid">-->
		<!--			<thead>-->
		<!--			<tr>-->
		<!--				<th>Каса</th>-->
		<!--				<th>Баланс</th>-->
		<!--				<th>Валюта</th>-->
		<!--				<th>Курс валют</th>-->
		<!--				<th>Сума в гривні</th>-->
		<!--			</tr>-->
		<!--			</thead>-->
		<!--			<tbody>-->
		<!--			--><?php //foreach ($start_balance as $wallet): ?>
		<!--				<tr>-->
		<!--					<td>-->
		<? //= $wallet['user_name']; ?><!-- --><? //= $wallet['wallet']['user_surname']; ?>
		<!--						( --><? //= $wallet['name']; ?><!-- )-->
		<!--					</td>-->
		<!--					<td>--><? //= (float)$wallet['balance']; ?><!--</td>-->
		<!--					<td>--><? //= $wallet['currency']; ?><!--</td>-->
		<!---->
		<!--				</tr>-->
		<!--			--><?php //endforeach; ?>
		<!--			--><?php //endif; ?>
		</tbody>
		</table>

	</div>
</div>

