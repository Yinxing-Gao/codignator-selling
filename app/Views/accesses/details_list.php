<table class="table table-striped">
	<thead class="thead-dark">
	<tr>
		<th>Назва</th>
		<th>Опис</th>
		<th style="width: 30px" class="text-center">
			<img src="<?=base_url();?>/icons/bootstrap/bag-check-white.svg"/>
		</th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($accesses)) : ?>
		<?php foreach ($accesses as $access): ?>
			<tr>
				<td><?= $access['name']; ?></td>
				<td><?= $access['description']; ?></td>
				<td class="text-center" style="width: 30px">
					<input class="form-control" type="checkbox"
						   <?= in_array($access['id'], $department_accesses) ? 'checked' : ''; ?>/>
				</td>
				<td class="text-center" style="width: 30px"></td>
			</tr>
			<?php if (!empty($access['children'])) : ?>
				<?php foreach ($access['children'] as $access_sub_item): ?>
					<tr>
						<td>
							<img src="../../../icons/bootstrap/dot.svg"/>
							<?= $access_sub_item['name']; ?>
						</td>
						<td><?= $access_sub_item['description']; ?></td>
						<td style="width: 30px">
							<input class="form-control" type="checkbox"
								   <?= in_array($access_sub_item['id'], $department_accesses) ? 'checked' : ''; ?>/>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	</tbody>
</table>

