<div id="plan_fact_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">P&l</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>
	<br/>
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
		<table class="table tablesorter">
			<thead class="thead-dark">
			<tr>
				<th>Стаття</th>
				<th>Сума</th>
				<th>Планується</th>
				<th>Бюджет</th>
				<th>Рекомендації</th>
			</tr>
			</thead>
			<tbody>
			<?php if (!empty($tree['income'])): ?>
				<?php echo_branch(0, $tree['income'], 0, $operations); ?>
			<?php endif; ?>
			<tr class="divider">
				<td colspan="15"></td>
			</tr>
			<?php if (!empty($tree['expense'])): ?>
				<?php echo_branch(0, $tree['expense'], 0, $operations); ?>
			<?php endif; ?>

			</tbody>
		</table>
		<!--		<div class="col-md-6">-->
		<!--			<h4 class="text-center">План</h4>-->
		<!--		</div>-->
		<!--		<div class="col-md-6">-->
		<!--			<h4 class="text-center">Факт</h4>-->
		<!--		</div>-->
	</div>
</div>


<?php
function echo_branch($parent_id, $tree, $level, $operations)
{
	$style = [
		'income' => 'green',
		'expense' => 'red'
	];

	$tab = '<img src="../../../icons/bootstrap/dot.svg" />';
	foreach ($tree as $article): ?>
		<tr class="article_row" data-article_id="<?= $article['id']; ?>" data-parent_id="<?= $parent_id; ?>"
			data-type="<?= $article['type']; ?>">
			<!--			<td>--><? //= $article['id']; ?><!--</td>-->
			<td data-label="Стаття" colspan="1">
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>
				<?= $article['name']; ?>
			</td>
			<td data-label="Сума">0.00</td>
			<td data-label="Планується">0.00</td>
			<td data-label="Бюджет">0.00</td>
			<td data-label="Рекомендації"></td>
		</tr>
		<?php if (!empty($operations)): ?>
			<?php foreach ($operations as $main_article_id => $data): ?>
				<?php if ($main_article_id == $article['id']): ?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!empty($article['children'])): ?>
			<?php echo_branch($article['id'], $article['children'], $level + 1, $operations); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>
