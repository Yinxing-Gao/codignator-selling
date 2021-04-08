<?php

$plan_params = $project_params['plan'];
$fact_params = $project_params['fact'];
//var_dump($plan_params);die();
?>
<style>

</style>
<div class="container" id="plan_fact">
	<h2 class="text-center">План факт <?= !empty($project) ? $project->name : ''; ?>
		<input name="project_id" id="project_id" type="hidden" value="<?= !empty($project) ? $project->id : 0; ?>"/>
		<input name="plan_fact_id" id="plan_fact_id" type="hidden"
			   value="<?= !empty($plan_fact) ? $plan_fact->id : $project->plan_fact_id; ?>"/>
		<a href="#" class="play_video_instruction">
			<img src="../../../icons/bootstrap/play.svg"/>
		</a>
		<a href="/rules/page/plan_fact" class="rules_btn">
			<img src="../../../icons/bootstrap/info.svg"/>
		</a>
		<a href="/accruals/project/<?= $project->id; ?>" class="rules_btn">
			<img src="../../../icons/bootstrap/clipboard-data.svg"/>
		</a>
		<!--		<p class="text-center" style="color:red; font-size: 14px;">На сторінці проводяться технічні роботи, будь-->
		<!--			ласка, зайдіть пізніше</p>-->
	</h2>
	<div class="row">
		<div class="col-md-2">
			<label for="responsible_id">Відповідальний</label>
			<select class="form-control" id="responsible_id" data-param="responsible_id" required=""
					name="responsible_id">
				<option value=""></option>
				<?php if (!empty($responsibles)): ?>
					<?php foreach ($responsibles as $responsible): ?>
						<option <?= $plan_fact->responsible_id == $responsible['id'] ? 'selected' : ''; ?>
								value="<?= $responsible['id']; ?>"><?= $responsible['name']; ?> <?= $responsible['surname']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label for="contract">Договір</label>
			<select class="form-control" id="contract" required="" name="contract_id" readonly>
				<option value=""></option>
				<?php if (!empty($contracts)): ?>
					<?php foreach ($contracts as $contract): ?>
						<option
							<?= (!empty($project->contract_id) && $project->contract_id == $contract['id']) ? 'selected' : '' ?>
							value="<?= $contract['id']; ?>"><?= $contract['number']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label for="department">Департамент</label>
			<select class="form-control" id="department" name="department_id"
					<?= !empty($department_id) ? 'readonly' : ''; ?>>
				<option value=""></option>
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option
							<?= (!empty($department_id) && $department_id == $department['id']) ? 'selected' : ''; ?>
							value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-1">
			<label for="away">На виїзді</label>
			<input type="checkbox" data-param="away" id="away"
				   <?= $plan_fact->away == 1 ? 'checked' : ''; ?> style="width:10px" class="form-control"/>
		</div>
		<div class="col-md-2">
			<label for="username">Валюта</label>
			<div class="input-group">
				<select
					class="form-control" id="currency" data-param="currency" name="currency"
					required="">
					<option <?= $plan_fact->currency == 'UAH' ? 'checked' : ''; ?> value="UAH">₴</option>
					<option <?= $plan_fact->currency == 'USD' ? 'checked' : ''; ?> value="USD">$</option>
					<option <?= $plan_fact->currency == 'EUR' ? 'checked' : ''; ?> value="EUR">€</option>
				</select>
				<input type="hidden" name="total_uah" value="0"/>
			</div>
		</div>
		<div class="col-md-1">
			<label for="training">Тренувальний</label>
			<input type="checkbox" data-param="training" <?= $plan_fact->training == 1 ? 'checked' : ''; ?>
				   id="training" style="width:10px" class="form-control"/>
		</div>
	</div>
	<br/>
	<br/>
	<div class="text-center">
		<div class="row">
			<div class="col-md-6 plan">
				<h4>План</h4>
				<div class="row">
					<div class="col-md-3">
						<label for="date">Дата старту</label>
						<input type="date" class="form-control" data-param="start_date" data-type="plan"
							   id="start_date_plan" placeholder=""
							   value="<?= !empty($plan_params['start_date']) ? date("Y-m-d", (int)$plan_params['start_date']) : date("Y-m-d", time()); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-3">
						<label for="date">Дата завершення</label>
						<input type="date" class="form-control" data-param="end_date" data-type="plan"
							   id="end_date_plan"
							   placeholder=""
							   value="<?= !empty($plan_params['end_date']) ? date("Y-m-d", (int)$plan_params['end_date']) : date("Y-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-3">
						<label for="date">К-сть днів</label><br/>
						<span class="days_amount display_span" data-param="days_amount" data-type="plan"
							  id="days_amount_plan"><?= !empty($plan_params['days_amount']) ? (int)$plan_params['days_amount'] : 8; ?></span>
					</div>
					<div class="col-md-3">
						<label for="date">Площа</label>
						<input type="number" id="square_plan" class="form-control" required data-param="square"
							   data-type="plan"
							   value="<?= !empty($plan_params['square']) ? $plan_params['square'] : 0; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Собівартість</label>
						<span class="display_span" id="cost_plan" data-param="cost"
							  data-type="plan"><?= number_format($costs['plan'], 2, ',', ' '); ?></span>
					</div>
					<div class="col-md-3">
						<label>Загальна сума</label>
						<input type="number" class="form-control" id="total_amount_plan" data-param="total_amount"
							   data-type="plan"
							   value="<?= !empty($plan_params['total_amount']) ? $plan_params['total_amount'] : 0; ?>"/>
					</div>
					<div class="col-md-6">
						<label>Маржинальність</label><br/>
						<span class="display_span" id="margin_plan" data-param="margin"
							  data-type="plan"><?= !empty($plan_params['margin']) ? $plan_params['margin'] : 0; ?> грн - <?= !empty($plan_params['margin_percent']) ? $plan_params['margin_percent'] : 0; ?>%</span>
						<input type="hidden" name="plan_margin_without_salary"
							   value="<?= !empty($plan_params['margin_without_salary']) ? $plan_params['margin_without_salary'] : 0; ?>"/>
					</div>
				</div>
				<hr/>

			</div>
			<div class="col-md-6 fact">
				<h4>Факт <label class="by_plan" for="by_plan">
						<input type="checkbox" id="by_plan" class="form-control" checked/>
						Все по плану</label>
				</h4>
				<div class="row">
					<div class="col-md-3">
						<label for="date">Дата старту</label>
						<input type="date" class="form-control" id="start_date_fact" data-param="start_date"
							   data-type="fact" placeholder=""
							   value="<?= !empty($fact_params['start_date']) ? date("Y-m-d", (int)$fact_params['start_date']) : date("Y-m-d", time()); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-3">
						<label for="date">Дата завершення</label>
						<input type="date" class="form-control" id="end_date_fact" data-param="end_date"
							   data-type="fact"
							   placeholder=""
							   value="<?= !empty($fact_params['end_date']) ? date("Y-m-d", (int)$fact_params['end_date']) : date("Y-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-3">
						<label for="date">К-сть днів</label><br/>
						<span class="days_amount display_span" data-param="amount" data-type="fact"
							  id="days_amount_fact"><?= !empty($fact_params['days_amount']) ? $fact_params['days_amount'] : 8; ?></span>
					</div>
					<div class="col-md-3">
						<label for="square">Площа</label>
						<input type="number" class="form-control" id="square_fact" data-param="square" data-type="fact"
							   required
							   value="<?= !empty($fact_params['square']) ? $fact_params['square'] : 0; ?>">
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<label>Собівартість</label>
						<span class="display_span" id="cost_fact" data-param="cost"
							  data-type="fact"><?= $costs['fact']; ?></span>
					</div>
					<div class="col-md-3">
						<label>Загальна сума</label>
						<input type="number" class="form-control" id="total_amount_fact" data-param="total_amount"
							   data-article_id="68"
							   data-type="fact"
							   value="<?= !empty($fact_params['total_amount']) ? $fact_params['total_amount'] : 0; ?>"/>

					</div>
					<div class="col-md-6">
						<label>Маржинальність</label><br/>
						<span class="display_span" id="margin_plan" data-param="margin"
							  data-type="plan"><?= !empty($plan_params['margin']) ? $plan_params['margin'] : 0; ?> грн - <?= !empty($plan_params['margin_percent']) ? $plan_params['margin_percent'] : 0; ?>%</span>
						<input type="hidden" name="plan_margin_without_salary"
							   value="<?= !empty($plan_params['margin_without_salary']) ? $plan_params['margin_without_salary'] : 0; ?>"/>
					</div>
				</div>
				<hr/>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 title">
				<h5>Оплати від клієнта</h5>
				<img class="hide" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
				<hr/>
			</div>
			<div class="col-md-6 plan">

				<table class="table income_table">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Сума</th>
						<th scope="col">Коментар</th>
						<th style="width:150px" scope="col">Стаття</th>
						<th scope="col">Дата</th>
						<th style="width: 55px" scope="col">
						</th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($done_operations)): ?>
						<?php foreach ($done_operations as $operation): ?>
							<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
								<td><?= $operation['id']; ?></td>
								<td>
									<div class="operation_amount"
										 style="color:<?= $op_style[$operation['operation_type_id']]; ?>; border:2px solid <?= $op_style[$operation['operation_type_id']]; ?>">
										<?= $operation['amount2']; ?>
										&nbsp;<?= key_exists($operation['currency2'], $currencies)
											? $currencies[$operation['currency2']] : ''; ?>
								</td>
								<td class="filter_td"><?= $operation['comment']; ?></td>
								<!--							<td class="filter_td">-->
								<? //= $operation['project_name']; ?><!--</td>-->

								<td>
									<?php
									$articles = $income_list;
									$select = true;
									if ($select): ?>
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
								<td><?= !empty($operation['date']) ? date('d.m.Y', $operation['date']) : ''; ?></td>
								<td>
									<img class="edit_operation" src="../../../icons/bootstrap/pencil.svg"/>
									<img class="delete_operation" src="../../../icons/bootstrap/trash.svg"/>
								</td>

							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td class="text-center" colspan="6">Операцій не знайдено</td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>


			</div>

			<div class="col-md-6 fact">
				<table class="table income_table">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Сума</th>
						<th scope="col">Коментар</th>
						<th style="min-width:120px" scope="col">Стаття</th>
						<th scope="col">Планована дата</th>
						<th scope="col">Вірогідність</th>
						<th style="min-width: 55px" scope="col">
						</th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($planned_operations)): ?>
						<?php foreach ($planned_operations as $operation): ?>
							<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
								<td><?= $operation['id']; ?></td>
								<td>
									<div class="operation_amount"
										 style="color:<?= $op_style[$operation['operation_type_id']]; ?>; border:2px solid <?= $op_style[$operation['operation_type_id']]; ?>">
										<?= $operation['amount2']; ?>
										&nbsp;<?= key_exists($operation['currency2'], $currencies)
											? $currencies[$operation['currency2']] : ''; ?>
								</td>
								<td class="filter_td"><?= $operation['comment']; ?></td>
								<td>
									<?php
									$articles = $income_list;
									$select = true;
									?>
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
								<td><?= !empty($operation['planned_on']) ? date('d.m.Y', $operation['planned_on']) : ''; ?></td>
								<td><?= $operation['probability']; ?> %</td>
								<td>
									<img class="edit_operation" src="../../../icons/bootstrap/pencil.svg"/>
									<img class="delete_operation" src="../../../icons/bootstrap/trash.svg"/>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td class="text-center" colspan="6">Операцій не знайдено</td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>


			</div>
		</div>

		<?php if (strlen($project->storages_ids) > 0): ?>
			<div class="col-md-12 title">
				<h5>Витрати на матеріали</h5>
				<img class="hide" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
				<hr/>
			</div>
			<div class="row">
				<div class="col-md-5 plan">
					<h6>Матеріали
						<?php if (!empty($materials_apps)): ?>
							<span class="storage_app_info">Створені заявки :
							<a href="<?= $base_url; ?>/storage/applications/<?= $storage1_id; ?>"><?= implode(',', $materials_apps); ?></a>
				</span>
						<?php else: ?>
							<a class="btn btn-info " id="create_materials_app" data-storage_id="<?= $storage1_id; ?>"
							   data-author_id="<?= $user->id; ?>" data-department_id="<?= $department_id; ?>"
							   data-project_id="<?= $project->id; ?>" href="#">Подати
								заявку</a>
						<?php endif; ?>
					</h6>
					<table class="table materials1_table materials_table" data-storage_id="<?= $storage1_id; ?>"
						   data-article_id="31"
						   data-type="plan">
						<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th>Назва</th>
							<th>К-сть</th>
							<th>Одиниця</th>
							<th>Вартість</th>
							<th>Загальна</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php if (!empty($products['plan'])): ?>
							<?php foreach ($products['plan'] as $product): ?>
								<?php if ($product['storage_id'] == $storage1_id): ?>
									<tr data-pfp_id="<?= $product['pf_product_id']; ?>"
										data-product_id="<?= $product['id']; ?>">
										<td class="id"><?= $product['id']; ?></td>
										<td class="name">
											<?= $product['name']; ?>
										</td>
										<td><input type="number" name="amount" value="<?= $product['amount']; ?>"></td>
										<td class="unit"><?= $product['unit']; ?></td>
										<td class="price"><?= number_format($product['price_'], 0, '.', ' '); ?></td>
										<td data-total="<?= $product['price_'] * $product['amount']; ?>"
											class="total"><?= number_format($product['price_'] * $product['amount'], 0, '.', ' '); ?>
											грн
										</td>
										<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg">
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-2">
					<label for="names">Найменування</label>
					<div class="storage1_names_for_plan_fact_block">
						<?php if (!empty($storage1_names)): ?>
							<?php foreach ($storage1_names as $storage1_name): ?>
								<span class="storage1_names_for_plan_fact" data_id="<?= $storage1_name['id']; ?>"
									  data_price="<?= $storage1_name['price_']; ?>"

									  data_unit="<?= $storage1_name['unit']; ?>">
											<?= $storage1_name['name']; ?>
									</span>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-5 fact">
					<h6>Матеріали<span style="margin-left:10px; font-size: 12px">Редагувати заявку</span></h6>
					<table class="table materials1_table materials_table" data-storage_id="<?= $storage1_id; ?>"
						   data-article_id="31"
						   data-type="fact">
						<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th>Назва</th>
							<th>К-сть</th>
							<th>Одиниця</th>
							<th>Вартість</th>
							<th>Загальна</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php if (!empty($products['fact'])): ?>
							<?php foreach ($products['fact'] as $product): ?>
								<?php if ($product['storage_id'] == $storage1_id): ?>
									<tr data-pfp_id="<?= $product['pf_product_id']; ?>"
										data-product_id="<?= $product['id']; ?>">
										<td class="id"><?= $product['id']; ?></td>
										<td class="name">
											<?= $product['name']; ?>
										</td>
										<td><input type="number" name="amount" value="<?= $product['amount']; ?>"></td>
										<td class="unit"><?= $product['unit']; ?></td>
										<td class="price"><?= number_format($product['price_'], 2, ',', ' '); ?></td>
										<td data-total="<?= $product['price_'] * $product['amount']; ?>"
											class="total"><?= number_format($product['price_'] * $product['amount'], 2, ',', ' '); ?>
											грн
										</td>
										<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg">
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<hr/>
			<div class="row">
				<div class="col-md-5 plan">
					<h6>Витратні матеріали
						<a class="btn btn-info add_plan_fact_operations" data-storage_id="<?= $storage2_id; ?>"
						   data-author_id="<?= $user->id; ?>" data-department_id="<?= $department_id; ?>"
						   data-project_id="<?= $project->id; ?>" href="#">Подати
							заявку</a>
					</h6>
					<table class="table materials2_table materials_table" data-storage_id="<?= $storage2_id; ?>"
						   data-article_id="32"
						   data-type="plan">
						<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th>Назва</th>
							<th>К-сть</th>
							<th>Одиниця</th>
							<th>Вартість</th>
							<th>Загальна</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php if (!empty($products['plan'])): ?>
							<?php foreach ($products['plan'] as $product): ?>
								<?php if ($product['storage_id'] == $storage2_id): ?>
									<tr data-pfp_id="<?= $product['pf_product_id']; ?>"
										data-product_id="<?= $product['id']; ?>">
										<td class="id"><?= $product['id']; ?></td>
										<td class="name">
											<?= $product['name']; ?>
										</td>
										<td><input type="number" name="amount" value="<?= $product['amount']; ?>"></td>
										<td class="unit"><?= $product['unit']; ?></td>
										<td class="price"><?= number_format($product['price_'], 2, ',', ' '); ?></td>
										<td data-total="<?= $product['price_'] * $product['amount']; ?>"
											class="total"><?= number_format($product['price_'] * $product['amount'], 2, ',', ' '); ?>
											грн
										</td>
										<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg">
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-2">
					<!--				<label for="names">Найменування</label>-->
					<div class="storage2_names_for_plan_fact_block">
						<?php if (!empty($storage2_names)): ?>
							<?php foreach ($storage2_names as $storage2_name): ?>
								<span class="storage2_names_for_plan_fact" data_id="<?= $storage2_name['id']; ?>"
									  data_price="<?= $storage2_name['price_']; ?>"
									  data_unit="<?= $storage2_name['unit']; ?>">
											<?= $storage2_name['name']; ?>
									</span>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-md-5 fact">
					<h6>Витратні матеріали</h6>
					<table class="table materials2_table materials_table" data-storage_id="<?= $storage2_id; ?>"
						   data-article_id="32"
						   data-type="fact">
						<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th>Назва</th>
							<th>К-сть</th>
							<th>Одиниця</th>
							<th>Вартість</th>
							<th>Загальна</th>
							<th></th>
						</tr>
						</thead>
						<tbody>
						<?php if (!empty($products['fact'])): ?>
							<?php foreach ($products['fact'] as $product): ?>
								<?php if ($product['storage_id'] == $storage2_id): ?>
									<tr data-pfp_id="<?= $product['pf_product_id']; ?>"
										data-product_id="<?= $product['id']; ?>">
										<td class="id"><?= $product['id']; ?></td>
										<td class="name">
											<?= $product['name']; ?>
										</td>
										<td><input type="number" name="amount" value="<?= $product['amount']; ?>"></td>
										<td class="unit"><?= $product['unit']; ?></td>
										<td class="price"><?= number_format($product['price_'], 2, ',', ' '); ?></td>
										<td data-total="<?= $product['price_'] * $product['amount']; ?>"
											class="total"><?= number_format($product['price_'] * $product['amount'], 2, ',', ' '); ?>
											грн
										</td>
										<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg">
										</td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-12 title">
				<h5>Витрати на зарплати</h5>
				<img class="hide" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
				<hr/>
			</div>
			<div class="col-md-5 plan">
				<table class="table salary_table" data-type="plan">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Ім'я</th>
						<th>Оплата за 1</th>
						<th>Одиниця</th>
						<th>Кількість</th>
						<th>Cума</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr class="part_th workers_tr">
						<td colspan="7">Оператори</td>
					</tr>
					<?php if (!empty($involved_workers['plan'])): ?>
						<?php foreach ($involved_workers['plan'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $worker_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th brigadier_tr">
						<td colspan="7">Клієнт менеджер</td>
					</tr>
					<?php if (!empty($involved_workers['plan'])): ?>
						<?php foreach ($involved_workers['plan'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $responsibles_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th">
						<td colspan="7">Інші робітники</td>
					</tr>
					<?php if (!empty($involved_workers['plan'])): ?>
						<?php foreach ($involved_workers['plan'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $other_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th travel_payments_tr">
						<td colspan="7">Відрядні</td>
					</tr>
					<?php if (!empty($involved_workers['plan'])): ?>
						<?php foreach ($involved_workers['plan'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'travel_payments'): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="travel_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td data-total="<?= $involved_worker['amount']; ?>"
										class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-2">
				<div class="workers_for_plan_fact_block">
					<select class="workers_for_plan_fact" id="workers_for_plan_fact">
						<?php if (!empty($workers)): ?>
							<?php foreach ($workers as $worker): ?>

								<!--							<span class="workers_for_plan_fact" data_id="--><? //= $worker['id']; ?><!--"-->
								<!--								  data_name="--><? //= $worker['name'] . ' ' . $worker['surname']; ?><!--"-->
								<!--								  data_profession="--><? //= $worker['profession']; ?><!--"-->
								<!--								  data_profession_id="--><? //= $worker['profession_id']; ?><!--">-->
								<!--											--><? //= $worker['profession']; ?><!-- --><? //= $worker['name']; ?><!-- --><? //= $worker['surname']; ?>
								<!--									</span>-->
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
			</div>
			<div class="col-md-5 fact">
				<table class="table salary_table" data-type="fact">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<th>Ім'я</th>
						<!--						<th>Оплата</th>-->
						<th>Оплата за 1</th>
						<th>Одиниця</th>
						<th>Кількість</th>
						<th>Cума</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr class="part_th workers_tr">
						<td colspan="7">Оператори</td>
					</tr>
					<?php if (!empty($involved_workers['fact'])): ?>
						<?php foreach ($involved_workers['fact'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $worker_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_name_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th brigadier_tr">
						<td colspan="7">Клієнт менеджер</td>
					</tr>
					<?php if (!empty($involved_workers['fact'])): ?>
						<?php foreach ($involved_workers['fact'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $responsibles_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th">
						<td colspan="7">Інші робітники</td>
					</tr>
					<?php if (!empty($involved_workers['fact'])): ?>
						<?php foreach ($involved_workers['fact'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'project_percentage' && in_array($involved_worker['profession_id'], $other_professions)): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="worker_payment"
									data-pfw_id="<?= $involved_worker['pfw_id']; ?>"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr class="part_th travel_payments_tr">
						<td colspan="7">Відрядні</td>
					</tr>
					<?php if (!empty($involved_workers['fact'])): ?>
						<?php foreach ($involved_workers['fact'] as $involved_worker): ?>
							<?php if ($involved_worker['placement'] == 'travel_payments'): ?>
								<tr data-pfw_id="<?= $involved_worker['pfw_id']; ?>" class="travel_payment"
									data-worker_id="<?= $involved_worker['id']; ?>">
									<td class="id"><?= $involved_worker['id']; ?></td>
									<td class="name"><?= $involved_worker['name']; ?> <?= $involved_worker['surname']; ?></td>
									<td colspan="3"></td>
									<td data-total="<?= $involved_worker['amount']; ?>"
										class="money_amount total"><?= $involved_worker['amount']; ?></td>
									<td><img class="delete_worker_from_fact" src="../../img/trash-icon.jpg"/></td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>

		<hr/>
		<div class="row">
			<div class="col-md-12 title">
				<h5>Витрати на транспорт</h5>
				<img class="hide" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
				<hr/>
			</div>
			<div class="col-md-6 plan">
				<!--				<a class="btn btn-info add_plan_fact_operations" id="add_transport_operations"-->
				<!--				   href="#all">Додати</a>-->
				<span class="total_operation_span">
						<?= !empty($fact_params['transport_total']) ? $fact_params['transport_total'] : 0; ?> грн</span>
				<input type="hidden" id="transport_total_fact"
					   value="<?= !empty($fact_params['transport_total']) ? $fact_params['transport_total'] : 0; ?>">
				<table class="table transport_table">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<!--						<th>Дата</th>-->
						<th>Контрагент</th>
						<th>Сума</th>
						<th>Валюта</th>
						<th>Коментар</th>
						<th><img src="../../../icons/bootstrap/plus-circle-white.svg" class="add_plan_fact_operations"/>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($transport_operations)): ?>
						<?php foreach ($transport_operations as $transport_operation): ?>
							<tr class="operation_tr">
								<td><?= $transport_operation['id'] ?></td>
								<!--								<td>-->
								<? //= date('d.m.Y', $transport_operation['date']); ?><!--</td>-->
								<td><?= $transport_operation['contractor1_name']; ?></td>
								<th><?= $transport_operation['amount1'] ?></th>
								<th><?= $transport_operation['currency1'] ?></th>
								<th><?= $transport_operation['comment'] ?></th>
								<th></th>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 fact">
				<!--				<a class="btn btn-info add_plan_fact_operations" id="add_transport_operations"-->
				<!--				   href="#all">Додати</a>-->
				<span class="total_operation_span">
						<?= !empty($fact_params['transport_total']) ? $fact_params['transport_total'] : 0; ?> грн</span>
				<input type="hidden" id="transport_total_fact"
					   value="<?= !empty($fact_params['transport_total']) ? $fact_params['transport_total'] : 0; ?>">
				<table class="table transport_table">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<!--						<th>Дата</th>-->
						<th>Контрагент</th>
						<th>Сума</th>
						<th>Валюта</th>
						<th>Коментар</th>
						<th><img src="../../../icons/bootstrap/plus-circle-white.svg" class="add_plan_fact_operations"/>
						</th>

					</tr>
					</thead>
					<tbody>
					<?php if (!empty($transport_operations)): ?>
						<?php foreach ($transport_operations as $transport_operation): ?>
							<tr class="operation_tr">
								<td><?= $transport_operation['id'] ?></td>
								<!--								<td>-->
								<? //= date('d.m.Y', $transport_operation['date']); ?><!--</td>-->
								<td><?= $transport_operation['contractor1_name']; ?></td>
								<th><?= $transport_operation['amount1'] ?></th>
								<th><?= $transport_operation['currency1'] ?></th>
								<th><?= $transport_operation['comment'] ?></th>
								<th></th>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<hr/>
		<!--		<div class="row">-->
		<!--			<div class="col-md-6 plan">-->
		<!--				<h6>Агентські</h6>-->
		<!--				<div class="row">-->
		<!--					<div class="col-md-7">-->
		<!--						<label>Загальна сума агентських винагород</label>-->
		<!--					</div>-->
		<!--					<div class="col-md-5">-->
		<!--						<input type="number" class="form-control" placeholder="Сума" required="" id="agent_total_plan"-->
		<!--							   data-param="agent_total" data-type="plan"-->
		<!--							   value="-->
		<? //= !empty($plan_params['agent_total']) ? $plan_params['agent_total'] : 0; ?><!--"/>-->
		<!--					</div>-->
		<!--				</div>-->
		<!--				<hr/>-->
		<!---->
		<!--			</div>-->
		<!--			<div class="col-md-6 fact">-->
		<!--				<h6>Агентські-->
		<!--					<a class="btn btn-info add_plan_fact_operations" id="add_agent_accruals" href="#">Додати</a>-->
		<!--					<span-->
		<!--						class="total_operation_span">-->
		<? //= !empty($fact_params['fact_total']) ? $fact_params['fact_total'] : 0; ?><!-- грн</span>-->
		<!--				</h6>-->
		<!--				<input type="hidden" id="agent_total_fact"-->
		<!--					   value="-->
		<? //= !empty($fact_params['agent_total']) ? $fact_params['agent_total'] : 0; ?><!--">-->
		<!--				<table class="table agent_table">-->
		<!--					<thead class="thead-dark">-->
		<!--					<tr>-->
		<!--						<th>#</th>-->
		<!--												<th>Дата</th>-->
		<!--						<th>Сума</th>-->
		<!--						<th>Коментар</th>-->
		<!--						<th></th>-->
		<!--					</tr>-->
		<!--					</thead>-->
		<!--					<tbody>-->
		<!---->
		<!--					</tbody>-->
		<!--				</table>-->
		<!--			</div>-->
		<!--		</div>-->
		<!--		<hr/>-->
		<div class="row">
			<div class="col-md-12 title">
				<h5>Інші витрати по проекту</h5>
				<img class="hide" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
				<hr/>
			</div>
			<div class="col-md-6 plan">
				<h6>Інше
<!--					<a class="btn btn-info add_plan_fact_operations" id="add_other_operations" href="#">Додати</a>-->
					<span
						class="total_operation_span"><?= !empty($fact_params['other_total']) ? $fact_params['other_total'] : 0; ?> грн</span>
				</h6>
				<input type="hidden" id="other_total_fact"
					   value="<?= !empty($fact_params['other_total']) ? $fact_params['other_total'] : 0; ?>">

				<table class="table other_table">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<!--						<th>Дата</th>-->
						<th>Контрагент</th>
						<th>Сума</th>
						<th>Валюта</th>
						<th>Коментар</th>
						<th><img src="../../../icons/bootstrap/plus-circle-white.svg" class="add_plan_fact_operations" </th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($other_operations)): ?>
						<?php foreach ($other_operations as $other_operation): ?>
							<tr class="operation_tr">
								<td><?= $other_operation['id'] ?></td>
								<!--								<td>-->
								<? //= date('d.m.Y', $other_operation['date']); ?><!--</td>-->
								<td><?= $other_operation['contractor1_name']; ?></td>
								<th><?= $other_operation['amount1'] ?></th>
								<th><?= $other_operation['currency1'] ?></th>
								<th><?= $other_operation['comment'] ?></th>
								<th></th>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-6 fact">
				<h6>Інше
<!--					<a class="btn btn-info add_plan_fact_operations" id="add_other_operations" href="#">Додати</a>-->
					<span
						class="total_operation_span"><?= !empty($fact_params['other_total']) ? $fact_params['other_total'] : 0; ?> грн</span>
				</h6>
				<input type="hidden" id="other_total_fact"
					   value="<?= !empty($fact_params['other_total']) ? $fact_params['other_total'] : 0; ?>">

				<table class="table other_table">
					<thead class="thead-dark">
					<tr>
						<th>#</th>
						<!--						<th>Дата</th>-->
						<th>Контрагент</th>
						<th>Сума</th>
						<th>Валюта</th>
						<th>Коментар</th>
						<th><img src="../../../icons/bootstrap/plus-circle-white.svg" class="add_plan_fact_operations" </th>

					</tr>
					</thead>
					<tbody>
					<?php if (!empty($other_operations)): ?>
						<?php foreach ($other_operations as $other_operation): ?>
							<tr class="operation_tr">
								<td><?= $other_operation['id'] ?></td>
								<!--								<td>-->
								<? //= date('d.m.Y', $other_operation['date']); ?><!--</td>-->
								<td><?= $other_operation['contractor1_name']; ?></td>
								<th><?= $other_operation['amount1'] ?></th>
								<th><?= $other_operation['currency1'] ?></th>
								<th><?= $other_operation['comment'] ?></th>
								<th></th>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
			<hr/>
		</div>
	</div>
</div>
