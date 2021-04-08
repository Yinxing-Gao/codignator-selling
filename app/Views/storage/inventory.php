<div id="storage_item_list">
	<div class="text-center">
		<h2>Інвентаризація(<?= $storage->name; ?>) на <?= date('d.m.Y', time()); ?>
			<a href="#" class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg" />
			</a>
		</h2>
	</div>
	<input type="hidden" name="storage_id" value="<?= $storage->id; ?>"/>
	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Назва*</th>
					<th scope="col">Одиниця вимірювання*</th>
					<th scope="col">Вартість*</th>
					<th scope="col">К-сть*</th>
					<th scope="col">Опис</th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($items)): ?>
					<?php foreach ($items as $item): ?>
						<tr data-item_id="<?= $item['id']; ?>">
							<td><?= $item['id']; ?></td>
							<td><?= $item['name']; ?></td>
							<td><?= $item['unit_id']; ?></td>
							<td><input class="form-control" type="text" name="buy_price"
									   value="<?= $item['buy_price']; ?>"/></td>
							<td><input class="form-control" type="number" name="amount"
									   value="<?= $item['min_amount']; ?>"/></td>
							<td><textarea class="form-control"
										  name="description"><?= $item['description']; ?></textarea></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
		<div class="col-md-12">
			<label for=""></label>
			<button class="btn btn-block btn-info add_position" type="submit">Додати позицію</button>
		</div>
		<div class="col-md-12">
			<label for=""></label>
			<button class="btn btn-primary btn-lg btn-block do_inventory" type="submit">Провести інвентаризацію</button>
		</div>
	</div>
</div>

<?php // додати звітність по інвентаризації ?>
