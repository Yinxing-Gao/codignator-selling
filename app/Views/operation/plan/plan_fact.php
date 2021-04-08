<div id="plan_fact_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">План - факт по операціях</h3>
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
	<div class="row">
		<div class="col-md-2">
			<select class="form-control" id="department_id" required="">
				<option value="my">Мої операції</option>
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-10"></div>
	</div>
	<div class="row">
		<table class="table tablesorter">
			<thead class="thead-dark">
			<tr>
				<!--					<th scope="col">#</th>-->
				<th colspan="7" class="text-center">План</th>
				<th></th>
				<th colspan="7" class="text-center">Факт</th>
			</tr>
			<tr>
				<th>#</th>
				<th>Сума</th>
				<th>Коментар</th>
				<th>Стаття</th>
				<th>Дата</th>
				<th></th>
				<th></th>
				<th></th>
				<th>#</th>
				<th>Сума</th>
				<th>Коментар</th>
				<th>Стаття</th>
				<th>Дата</th>
				<th></th>
				<th></th>
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
			<td colspan="6">
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>
				<?= $article['name']; ?>
			</td>
			<td colspan="9"></td>
		</tr>
		<?php if (!empty($operations)): ?>
			<?php foreach ($operations as $main_article_id => $data): ?>
				<?php if ($main_article_id == $article['id']): ?>
					<?php for ($i = 0; $i < max(count($data['real']), count($data['plan'])); $i++): ?>
						<tr>
							<?php if (!empty($data['real'][$i])): ?>
								<?php $operation = $data['real'][$i]; ?>
								<td><?= $operation['id']; ?></td>
								<td><?= $operation['amount']; ?> <?= $operation['currency']; ?></td>
								<td><?= $operation['comment']; ?></td>
								<td>
									<select class="form-control">

									</select>
								</td>
								<td><?= date('d.m.Y', $operation['date']); ?></td>
								<td><img class="edit_operation" src="../../../../icons/bootstrap/pencil.svg"/></td>
								<td><img class="delete_operation" src="../../../../icons/bootstrap/trash.svg"/></td>
							<?php else: ?>
								<td colspan="7"></td>
							<?php endif; ?>
							<td class="divide_cell"></td>
							<?php if (!empty($data['plan'][$i])): ?>
								<?php $operation = $data['plan'][$i]; ?>
								<td><?= $operation['id']; ?></td>
								<td><?= $operation['amount']; ?> <?= $operation['currency']; ?></td>
								<td><?= $operation['comment']; ?></td>
								<td>
									<select class="form-control">

									</select>
								</td>
								<td><?= date('d.m.Y', $operation['planned_on']); ?></td>
								<td><img class="edit_operation" src="../../../../icons/bootstrap/pencil.svg"/></td>
								<td><img class="delete_operation" src="../../../../icons/bootstrap/trash.svg"/></td>
							<?php else: ?>
								<td colspan="7"></td>
							<?php endif; ?>
						</tr>
					<?php endfor; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!empty($article['children'])): ?>
			<?php echo_branch($article['id'], $article['children'], $level + 1, $operations); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>
