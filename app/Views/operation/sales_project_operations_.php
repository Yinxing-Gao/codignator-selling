<?php
?>
<div id="sales_project_operations">
	<h2>Операції приходу по проекту <?= $project->name; ?></h2>
	<div class="operations_types">
		<a class="btn btn-info btn-dark" href="#plan">Плановані</a>
		<a class="btn btn-info" href="#done">Здійснені</a>
	</div>
	<div id="panel_plan" class="operation_panel">
		<?php if (!empty($planned_operations)): ?>
			<div class="col-md-12 operation_table_div">
				<table class="table">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Сума</th>
						<th scope="col">Коментар</th>
						<th style="width:150px" scope="col">Стаття</th>
						<th scope="col">Планована дата</th>
						<th scope="col">Вірогідність</th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>

					<?php foreach ($planned_operations as $operation): ?>
						<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
							<td><?= $operation['id']; ?></td>
							<td>
								<div class="operation_amount"
									 style="background-color:<?= $op_style[$operation['operation_type_id']]; ?>; border:3px solid <?= $op_style[$operation['operation_type_id']]; ?>">
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
							<td><?=$operation['probability']; ?> %</td>
							<td>
								<img class="edit_operation" src="../../../icons/bootstrap/pencil.svg"/>
								<img class="delete_operation" src="../../../icons/bootstrap/trash.svg"/>
							</td>

						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<p class="no_operations">Планованих операцій більше немає</p>
		<?php endif; ?>
	</div>
	<div id="panel_done" class="operation_panel">
		<?php if (!empty($done_operations)): ?>
			<div class="col-md-12 operation_table_div">
				<table class="table">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Сума</th>
						<th scope="col">Коментар</th>
						<th style="width:150px" scope="col">Заявка</th>
						<th style="width:150px" scope="col">Стаття</th>
						<th scope="col">Дата</th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>

					<?php foreach ($done_operations as $operation): ?>
						<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
							<td><?= $operation['id']; ?></td>
							<td>
								<div class="operation_amount"
									 style="background-color:<?= $op_style[$operation['operation_type_id']]; ?>; border:3px solid <?= $op_style[$operation['operation_type_id']]; ?>">
									<?= $operation['amount2']; ?>
									&nbsp;<?= key_exists($operation['currency2'], $currencies)
										? $currencies[$operation['currency2']] : ''; ?>
							</td>
							<td class="filter_td"><?= $operation['comment']; ?></td>
<!--							<td class="filter_td">--><?//= $operation['project_name']; ?><!--</td>-->
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
					</tbody>
				</table>
			</div>
		<?php else: ?>
			<p class="no_operations">Операцій не знайдено</p>
		<?php endif; ?>
	</div>
</div>
