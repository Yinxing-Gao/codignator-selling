<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<style>

</style>
<div class="container" id="production_process" data-progress="<?= $progress; ?>">
	<h2 class="text-center">Процес <?= !empty($project) ? $project->name : ''; ?>
		<input name="project_id" type="hidden" value="<?= !empty($project) ? $project->id : 0; ?>"/>
		<a href="#" class="play_video_instruction">
			<img src="../../img/yt.png"/>
		</a>
		<p class="text-center" style="color:red; font-size: 21px;">На сторінці проводяться технічні роботи, будь
			ласка, зайдіть пізніше</p>
		<div id="progressbar">
			<div class="progress-label"><?= $progress; ?>%</div>
		</div>

	</h2>
	<div class="text-center">
		<div class="row">
			<div class="col-md-6">
				<h3>Склад</h3>
				<?php if (!empty($missing_items)): ?>
					<span class="alert alert-primary missing_info"><strong><?= count($missing_items) ?></strong> елементів на складі недостатньо для завершення даного проекту <btn
							class="btn btn-info generate_app_from_spec"
							data-missing="<?= htmlspecialchars(json_encode($missing_items)); ?>" value=" подати заявку">Подати заявку</btn></span>
				<?php endif; ?>

				<table border="1px solid">
					<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>Кількість<br/><span style="font-size: 11px">потрібна /  на складі</span></th>
						<th>Одиниця</th>
						<th>Ціна закупки</th>

						<th><img width="40px" alt="Додати до проекту всю специфікацію"
								 src="../../img/big_black_right_arrow.jpg" class="add_all_from_spec_to_project"/>
						</th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($items)): ?>
						<?php foreach ($items as $block_id => $block): ?>
							<tr>
								<td colspan="5"><b><?= $block['name'] ?></b></td>
								<td><img width="40px" alt="Додати до проекту весь блок"
										 data-block_id="<?= $block_id; ?>"
										 class="add_block_from_spec_to_project"
										 src="../../img/black_right_arrow_.jpg"/></td>
							</tr>
							<?php if (!empty($block['items'])): ?>
								<?php foreach ($block['items'] as $item): ?>
									<?php if (!in_array($item['id'], $project_items_id)): ?>
										<tr <?= ($item['amount'] > $item['storage_amount']) ? 'style="color:red"' : ''; ?>
											class="specification_item_left block_item_<?= $block_id ?>_left"
											data-item_id="<?= $item['id']; ?>"
											data-storage_item_id="<?= $item['item_id']; ?>">
											<td><?= $item['storage_name_id']; ?></td>
											<td><?= $item['name']; ?></td>
											<td><?= $item['amount']; ?>/ <?= $item['storage_amount']; ?></td>
											<td><?= $item['unit']; ?></td>
											<td><?= $item['storage_item_price']; ?></td>
											<td><img class="add_item_from_spec_to_project"
													 alt="Додати до проекту деталь" src="../../img/right_arrow.jpg"/>
											</td>
										</tr>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
				<a href="#">Підгрузити інші пункти зі складу</a>
			</div>
			<div class="col-md-1"></div>
			<div class="col-md-5">
				<h3><?= !empty($project) ? $project->name : ''; ?></h3>
				<table border="1px solid">
					<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>Ціна</th>
						<th>Кількість<br/><span style="font-size: 11px"> в проекті / потрібна</span></th>
						<th>Одиниця</th>
						<th></th>

					</tr>
					</thead>
					<tbody>
					<?php if (!empty($project_items)): ?>
						<?php foreach ($project_items as $project_block_id => $project_block): ?>
							<tr>
								<td colspan="5"><b><?= $project_block['name'] ?></b></td>
								<td><img src="../../img/back_arrow.jpg"/></td>
							</tr>
							<?php if (!empty($project_block['items'])): ?>
								<?php foreach ($project_block['items'] as $project_item): ?>
									<tr>
										<td><?= $project_item['storage_name_id']; ?></td>
										<td><?= $project_item['name']; ?></td>
										<td><?= $project_item['price']; ?></td>
										<td><?= $project_item['amount']; ?>/<?= $project_item['spec_amount']; ?></td>
										<td><?= $project_item['unit']; ?></td>
										<td><img width="25px" src="../../img/blue_back_arrow.jpg"/></td>
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
</div>
<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
