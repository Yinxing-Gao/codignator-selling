<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<div id="sales">
	<h2 class="text-center">Проекти відділу продаж</h2>
	<?php if (!empty($projects)): ?>
		<div class="js-ui-accordion">
			<?php foreach ($projects as $project): ?>
				<h3 ><?= $project['name']; ?>
					<svg class="bi" width="1em" height="1em" fill="currentColor">
						<use xlink:href="bootstrap-icons.svg#briefcase"></use>
					</svg>
					<img data-project_id="<?= $project['id']; ?>" class="edit_project" src="../../icons/bootstrap/journal-text.svg"/>
<!--					<img data-project_id="--><?//= $project['id']; ?><!--" class="edit_project" src="../../img/edit.jpg"/>-->

<!--					<img data-contractor_id="--><?//= $project['contractor_id']; ?><!--" data-project_id="--><?//= $project['id']; ?><!--"-->
<!--						 class="add_operation_to_project_open" src="../../img/plus.png"/>		-->
					<img data-contractor_id="<?= $project['contractor_id']; ?>" data-project_id="<?= $project['id']; ?>"
						 class="add_operation_to_project_open" src="../../icons/bootstrap/node-plus.svg"/>
				</h3>

				<div>
					<?php if (!empty($planned_operations[$project['id']])): ?>
						<div class="col-md-12 operation_table_div">
							<table class="table">
								<thead class="thead-dark">
								<tr>
									<th scope="col">#</th>
									<th scope="col">Сума</th>
									<th scope="col">Коментар</th>
									<th scope="col">Проект</th>
									<th style="width:150px" scope="col">Заявка</th>
									<th style="width:150px" scope="col">Стаття</th>
									<th scope="col">Дата</th>
									<th scope="col"></th>
								</tr>
								</thead>
								<tbody>

								<?php foreach ($planned_operations[$project['id']] as $operation): ?>
									<tr class="operation_tr" data-operation_id="<?= $operation['id']; ?>">
										<td><?= $operation['id']; ?></td>
<!--										<td class="filter_td">--><?//= $operation['contractor1_name'] ?><!--</td>-->
										<td>
											<div class="operation_amount"
												 style="background-color:<?= $op_style[$operation['operation_type_id']][0]; ?>; border:3px solid <?= $op_style[$operation['operation_type_id']][1]; ?>; min-width:100px">
												<?= $operation['amount2']; ?>
												&nbsp;<?= key_exists($operation['currency2'], $currencies)
													? $currencies[$operation['currency2']] : ''; ?>
										</td>
<!--										<td class="filter_td">--><?//= $operation['contractor2_name'] ?><!--</td>-->
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
										<td><?= !empty($operation['date']) ? date('d.m.Y', $operation['date']) : ''; ?></td>
										<td>
											<img class="edit_operation" src="../../img/edit.jpg"/>
											<img class="delete_operation" src="../../img/trash-icon.jpg"/>
										</td>

									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else: ?>
						<div class="">
							<h4>Операцій по цьому проекту не знайдено</h4>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
