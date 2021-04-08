<div id="plan_fact_company_result">
	<h2 class="text-center">Фін.результат компанії</h2>
	<div class="row">
		<div class="col-md-2">
			<label for="username">Період</label>
			<select class="form-control" id="month_change" name="month_change" required="">
				<?php if (!empty($months)): ?>
					<?php foreach ($months as $id => $month): ?>
						<option <?= ($id == $month_year) ? 'selected' : ''; ?>
								value="<?= $id; ?>"><?= $month; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label for="username">Департамент</label>
			<select class="form-control" id="department_id" required="">
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label>Включити всі піддепартаменти</label>
			<input type="checkbox" class="form-control"/>
		</div>
		<div class="col-md-6"></div>
	</div>
	<div class="row">
		<table border="1px solid">
			<thead>
			<tr>
				<th>Назва статті</th>
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<th data-department_id="<?= $department['id']; ?>"><?= $department['name']; ?></th>
					<?php endforeach; ?>
				<?php endif; ?>
				<th>Компанія</th>
			</tr>
			</thead>
			<tbody>

			<?php \App\Models\Dev::var_dump($article_tree); ?>
			<?php if (!empty($article_tree['income'])): ?>
				<?php echo_branch(0, $article_tree['income'], $departments, 'income', $company_result, 0, $tab); ?>
			<?php endif; ?>
			<tr class="divider">
				<td colspan="<?= count($departments) + 2; ?>"></td>
			</tr>
			<?php if (!empty($article_tree['expense'])): ?>
				<?php echo_branch(0, $article_tree['expense'], $departments, 'expense', $company_result, 0, $tab); ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
<?php
function echo_branch($parent_id, $tree, $departments, $type, $company_result, $level, $tab)
{
	$incomes = $company_result['incomes'];
	$expenses = $company_result['expenses'];
	$articles_total_income = $company_result['articles_total_income'];
	$articles_total_expense = $company_result['articles_total_expense'];

//	$departments_total_income = $company_result['departments_total_income'];
//	$departments_total_expense = $company_result['departments_total_expense'];
	$margins = $company_result['margins'];
	foreach ($tree as $branch): ?>
		<tr data-article_id="<?= $branch['id']; ?>" class="<?= $parent_id == 0 ? 'main' : ''; ?>">
			<td>(<?= $branch['id']; ?>)
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>

				<?= $branch['name']; ?><?= $parent_id == 0 ? ' в т.ч.:' : ''; ?></td>
			<?php if (!empty($departments)): ?>
				<?php foreach ($departments as $department): ?>
					<td>
						<?php for ($i = 0; $i < $level; $i++): ?>
							<?= $tab; ?>
						<?php endfor; ?>

						<?php if ($type == 'income'): ?>
							<?= !empty($incomes[$branch['id']][$department['id']]) ? $incomes[$branch['id']][$department['id']] : 0; ?>
						<?php elseif ($type == 'expense'): ?>
							<?= !empty($expenses[$branch['id']][$department['id']]) ? $expenses[$branch['id']][$department['id']] : 0; ?>
						<?php endif; ?>
					</td>
				<?php endforeach; ?>
			<?php endif; ?>
			<td>
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>
				<?php if ($type == 'income'): ?>
					<?= !empty($incomes[$branch['id']]['company']) ? $incomes[$branch['id']]['company'] : 0; ?>
				<?php elseif ($type == 'expense'): ?>
					<?= !empty($expenses[$branch['id']]['company']) ? $expenses[$branch['id']]['company'] : 0; ?>
				<?php endif; ?>
			</td>
		</tr>
		<?php if (!empty($branch['children'])): ?>
			<?php echo_branch($branch['id'], $branch['children'], $departments, $type, $company_result, $level + 1, $tab); ?>
		<?php endif; ?>
		<?php if ($parent_id == 0 && $type == 'expense'): ?>
			<tr class="margin">
				<td>Маржинальний дохід</td>
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<td>
							<?= !empty($margins[$department['id']][$branch['id']]) ? $margins[$department['id']][$branch['id']] : 0; ?>
						</td>
					<?php endforeach; ?>
				<?php endif; ?>
				<td>
					<?= !empty($margins['company'][$branch['id']]) ? $margins['company'][$branch['id']] : 0; ?>
				</td>
			</tr>
		<?php endif; ?>
	<?php endforeach;
} ?>
