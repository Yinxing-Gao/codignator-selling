<div id="operation_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Мої плановані операції</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>
	<div class="filter_btns row">
		<div class="col-md-10">
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
				<span>від співробітника компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[6][0]; ?>; border:2px solid <?= $op_style[6][1]; ?>">100
				</div>
				<span>співробітнику компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[7][0]; ?>; border:2px solid <?= $op_style[7][1]; ?>">100
				</div>
				<span>між своїми касами</span>
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
					<th rowspan="2" scope="col"><img src="../../../../icons/bootstrap/eye-white.svg"/></th>
					<th rowspan="2" scope="col">#</th>
					<th rowspan="2" scope="col">Контрагент</th>
					<th rowspan="2" scope="col">Сума</th>
					<th rowspan="2" scope="col">Коментар</th>
					<th rowspan="2" scope="col">Проект</th>
					<th rowspan="2" style="width:150px" scope="col">Заявка</th>
					<th rowspan="2" style="width:150px" scope="col">Стаття</th>
					<?php if (count($user_wallets) > 0): ?>
						<th colspan="<?= count($user_wallets); ?>" scope="col" class="text-center">
							Планируемый баланс
						</th>
					<?php endif; ?>
					<th rowspan="2" scope="col">Планируемая дата</th>
					<th rowspan="2" scope="col">
						<img src="../../../../icons/bootstrap/calendar-check-white.svg"/>
					</th>
					<th rowspan="2" scope="col"></th>
				</tr>
				<tr>
					<?php if (!empty($user_wallets)): ?>
						<?php foreach ($user_wallets as $wallet): ?>
							<th scope="col" style="width:85px"><?= $wallet['name']; ?></th>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($operation_list)): ?>
					<?php foreach ($operation_list

								   as $timestamp => $date_block): ?>
						<?php if (!empty($date_block['operation'])) : ?>
							<?php foreach ($date_block['operation'] as $operation): ?>
								<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
									<td data-label="Відображається">
										<input type="checkbox" class="form-control is_shown"
											   <?= $operation['is_shown'] == 1 ? 'checked' : ''; ?>>
									</td>
									<td data-label="#"><?= $operation['id']; ?></td>
									<td data-label="Контрагент"
										class="filter_td"><?= $operation['contractor_name'] ?></td>
									<td data-label="Сума">
										<div class="operation_amount"
											 style="color:<?= $op_style[$operation['operation_type_id']][0]; ?>; border:2px solid <?= $op_style[$operation['operation_type_id']][1]; ?>; min-width:100px">
											<?= number_format($operation['amount'], 0, ',', '&nbsp;'); ?>
											&nbsp;<?= key_exists($operation['currency'], $currencies)
												? $currencies[$operation['currency']] : ''; ?>
									</td>
									<td data-label="Коментар"
										class="filter_td"><?= htmlspecialchars_decode($operation['comment']); ?></td>
									<td data-label="Проект" class="filter_td"><?= $operation['project_name']; ?></td>
									<td data-label="Заявка">
										<?php if ($operation['operation_type_id'] == 2): ?>
											<select class="form-control operation_application_id" id="applications"
													name="application_id" required="">

												<option value=""></option>
												<?php if (!empty($applications)): ?>
													<?php foreach ($applications as $application): ?>
														<option
															<?= $operation['app_id'] == $application['id'] ? 'selected' : '' ?>
															value="<?= $application['id']; ?>"><?= $application['department']; ?>
															<?= $application['product']; ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										<?php endif; ?>
									</td>
									<td data-label="Стаття">
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
											<td <?= $operation['wallet_planned_checkout'] < 0 ? 'style="color:red"' : '' ?>>
												<?php if ($operation['wallet_id'] == $wallet['id']): ?>
													<?php if (isset($operation['wallet_planned_checkout']) && $operation['is_shown'] == 1): ?>
														<?= number_format($operation['wallet_planned_checkout'], 0, ',', '&nbsp;'); ?>

													<?php else: ?>
														--
													<?php endif; ?>
													<!--												--><?php //elseif ($operation['wallet_2_id'] == $wallet['id']): ?>
													<!--													--><?php //if (isset($operation['wallet_2_planned_checkout']) && $operation['is_shown'] == 1): ?>
													<!--														--><? //= number_format($operation['wallet_2_planned_checkout'], 0, ',', '&nbsp;'); ?>
													<!---->
													<!--													--><?php //else: ?>
													<!--														---->
													<!--													--><?php //endif; ?>
												<?php else: ?>
													--
												<?php endif; ?>
											</td>

										<?php endforeach; ?>
									<?php endif; ?>
									<td data-label="Планована дата">
										<!--										--><? //=$operation['planned_on']; ?>
										<input type="date" class="form-control date" placeholder=""
											   name="date_for"
											   value="<?= !empty($operation['planned_on']) ? date("Y-m-d", $operation['planned_on']) : ''; ?>"
											   required="">
									</td>
									<td data-label="Сповіщення">
										<input type="checkbox" class="form-control notify"
											   <?= $operation['notify'] == 1 ? 'checked' : ''; ?>/>
									</td>
									<td>
										<div class="icon perform" data-title="Виконати операцію">
											<img src="<?= base_url(); ?>/icons/bootstrap/credit-card-2-back-fill.svg"/>
										</div>
										<div class="icon edit_operation" data-title="Редагувати операцію">
											<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
										</div>
										<div class="icon delete_operation" data-title="Видалити операцію">
											<img src="<?= base_url(); ?>/icons/bootstrap/trash.svg"/>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (!empty($date_block['checkout'])) : ?>

							<tr class="report_tr">
								<td colspan="8">Планований баланс каси на кінець звітного періоду</td>
								<?php if (!empty($user_wallets)): ?>
									<?php foreach ($user_wallets as $wallet): ?>
										<td>

											<?php foreach ($date_block['checkout'] as $checkout): ?>
												<?php if ($checkout['wallet_id'] == $wallet['id']): ?>
													<?= number_format($checkout['amount'], 0, ',', '&nbsp;'); ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
									<?php endforeach; ?>
								<?php endif; ?>
								<td>
									<?= $date_block['checkout'][0]['date'] !== 'today' ? date('d.m.Y', $timestamp) : 'Сьогодні'; ?>
								</td>
								<td colspan="4"></td>
							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
