<?php
function echo_branch($parent_id, $type, $articles_tree, $params)
{
	$accruals = $params['accruals'];
	$op_style = $params['op_style'];
	$expense_list = $params['expense_list'];
	$income_list = $params['income_list'];
	$currencies = $params['currencies'];
	foreach ($articles_tree as $branch): ?>
		<tr class="article <?= $type ?>" data-article_id="<?= $branch['id']; ?>">
			<td colspan="11"><?= $branch['name']; ?></td>
		</tr>
		<?php if (!empty($accruals)): ?>
			<?php foreach ($accruals as $accrual): ?>
				<?php if ($accrual['article_id'] == $branch['id']) : ?>
					<tr class="accrual_tr" data-accrual_id="<?= $accrual['id']; ?>">
						<td><?= $accrual['id']; ?></td>
						<td class="filter_td">
							<?= $accrual['contractor_name']; ?></td>
						<td>
							<div class="accrual_amount"
								 style="background-color:<?= $op_style[$accrual['accrual_type']]; ?>; min-width: 100px">
								<?= $accrual['amount'] . ' ' . $currencies[$accrual['currency']]; ?>
							</div>
						</td>
						<td class="filter_td"><?= $accrual['contractor_name']; ?></td>

						<td class="filter_td">
							<select class="form-control accrual_department_id" name="department_id">
								<option value=""></option>
								<?php if (!empty($departments)): ?>
									<?php foreach ($departments as $department): ?>
										<option
											<?= $accrual['department_id'] == $department['id'] ? 'selected' : '' ?>
											value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</td>
						<td class="filter_td"><?= $accrual['comment']; ?></td>

						<td>
							<?php if ($accrual['accrual_type'] == 'debit'):
								$articles = $income_list;
								$select = true;
							elseif ($accrual['accrual_type'] == 'credit'):
								$articles = $expense_list;
								$select = true;
							endif; ?>
							<?php if ($select): ?>
								<select style="width:150px" class="form-control accrual_article_id"
										name="article_list_id" required="">
									<option value="">Нерозподілені статті</option>
									<?php if (!empty($articles)): ?>
										<?php foreach ($articles as $article_item): ?>
											<optgroup label="<?= $article_item['name']; ?>">
												<?php if (!empty($article_item['children'])): ?>
													<?php foreach ($article_item['children'] as $article_1): ?>
														<option
															<?= $accrual['article_id'] == $article_1['id'] ? 'selected' : '' ?>
															value="<?= $article_1['id']; ?>"><?= $article_1['name']; ?></option>
													<?php endforeach; ?>
												<?php else: ?>
													<option
														<?= $accrual['article_id'] == $article_item['id'] ? 'selected' : '' ?>
														value="<?= $article_item['id']; ?>"><?= $article_item['name']; ?></option>

												<?php endif; ?>
											</optgroup>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							<?php else: ?>
								<?php if ($accrual['accrual_type_id'] == 4): ?>
									<span>Кредит</span>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<td class="filter_td"><?= $accrual['project_name']; ?></td>
						<td><?= date("d.m.Y", $accrual['date']); ?></td>
						<td></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!empty($branch['children'])): ?>
			<?php echo_branch($branch['id'], $type, $branch['children'], $params); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>
<div id="accruals">
	<div class="text-center">
		<h2>Начислення
			<a href="https://www.youtube.com/embed/eA5LXaDdaEY"
			   class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg"/>
			</a>
		</h2>
<!--		<p style="color:red;font-size:16px;">На сторінці проводяться технічні роботи, зайдіть, будь ласка, пізніше</p>-->
	</div>
	<div class="filter_btns row">
		<div class="col-md-10">
			<div class="color_block">
				<div class="color"
					 style="background-color:<?= $params['op_style']['debit']; ?>;">100
				</div>
				<span>Дебет</span>
			</div>
			<div class="color_block">
				<div class="color" style="background-color:<?= $params['op_style']['credit']; ?>;">100</div>
				<span>Кредит</span>
			</div>
		</div>
		<div class="col-md-2">
			<input id="search" class="form-control" placeholder="Пошук"/>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12 accrual_table_div">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Контрагент 1</th>
					<th scope="col">&nbsp;&nbsp;&nbsp;&nbsp;Сума&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th scope="col">Контрагент 2</th>
					<th scope="col">Департамент</th>
					<th scope="col">Коментар</th>
					<th scope="col">Стаття</th>
					<th scope="col">Проект</th>
					<th scope="col">Дата</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php echo_branch(0, 'income', $articles_tree['income'], $params); ?>
				<?php echo_branch(0, 'expense', $articles_tree['expense'], $params); ?>

				</tbody>
			</table>
		</div>
	</div>
</div>



