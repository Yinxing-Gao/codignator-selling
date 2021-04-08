<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал
//echo "<pre>";
//var_dump($departments);
//echo "<br/>";die();
//echo "<br/>";
//var_dump($income_list);
//?>
<div id="operation_list">
	<div class="text-center">
		<h2>Операції департаменту <a href="https://www.youtube.com/embed/eA5LXaDdaEY"
									 class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>
		<p style="color:red;font-size:16px;">На сторінці проводяться технічні роботи, зайдіть, будь ласка, пізніше</p>
	</div>
	<div class="filter_btns row">
		<div class="col-md-10">
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $op_style[1]; ?>;">100
				</div>
				<span>дохід</span>
			</div>
			<div class="color_block">
				<div class="color" style="background-color:<?= $op_style[2]; ?>;">100</div>
				<span>розхід</span>
			</div>
			<div class="color_block">
				<div class="color" style="background-color:<?= $op_style[3]; ?>">100</div>
				<span>переміщення</span>
			</div>
			<div class="color_block">
				<div class="color" style="background-color:<?= $op_style[4]; ?>;">100</div>
				<span>кредит</span>
			</div>
		</div>
		<div class="col-md-2">
			<input id="search" class="form-control" placeholder="Пошук"/>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12 operation_table_div">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Контрагент 1</th>
					<th scope="col">&nbsp;&nbsp;&nbsp;&nbsp;Сума&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th scope="col">Контрагент 2</th>
					<th scope="col">Департамент</th>
					<th scope="col">Коментар</th>
					<th scope="col">Заявка</th>
					<th scope="col">Стаття</th>
					<th scope="col">Проект</th>
					<th scope="col">Дата</th>
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
									<td><?= $operation['id']; ?></td>
									<td class="filter_td">
										<?= $operation['contractor_name']; ?></td>
									<td>
										<div class="operation_amount"
											 style="background-color:<?= $op_style[$operation['operation_type_id']]; ?>; min-width: 100px">
											<?= $operation['amount'] . ' ' . $operation['currency']; ?>
											<?= (!empty($operation['currency2']) && $operation['currency'] != $operation['currency2']) ? ' / ' . $operation['amount2'] . ' ' . $operation['currency2'] : ''; ?>
										</div>
									</td>
									<td class="filter_td"><?= $operation['contractor2_name']; ?></td>
									<!--									<td class="filter_td">-->
									<? //= $operation['department_name']; ?><!-- -->
									<? //= $operation['operation_type_id'] == 3 ? ' / ' . $operation['department2_name'] : ''; ?><!--</td>-->
									<td class="filter_td">
										<?php if ($operation['operation_type_id'] == 3): ?>
											<?= $operation['department_name'] . ' / ' . $operation['department2_name'] ?>
										<?php else: ?>
											<select class="form-control operation_department_id" name="department_id">
												<option value=""></option>
												<?php if (!empty($departments)): ?>
													<?php foreach ($departments as $department): ?>
														<option
															<?= $operation['department_id'] == $department['id'] ? 'selected' : '' ?>
															value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
										<?php endif; ?>
									</td>
									<td class="filter_td"><?= $operation['comment']; ?></td>
									<td>
										<?php if ($operation['app_id'] != 0): ?>
											<?= $operation['app_id']; ?>. <?= $operation['app_product'] ?>
											(<?= $operation['app_author_surname']; ?> <?= substr($operation['app_author_name'], 0, 2); ?>. )
										<?php endif; ?>
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
											<select style="width:150px" class="form-control operation_article_id"
													name="article_list_id" required="">
												<option value="">Нерозподілені статті</option>
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
									<td class="filter_td"><?= $operation['project_name']; ?></td>
									<td><?= date("d.m.Y", $operation['date']); ?></td>
									<td></td>
								</tr>
							<?php endforeach; ?>
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


