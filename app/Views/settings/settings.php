<div class="container" id="settings">
	<div class="py-5 text-center">
		<h2>Налаштування</h2>
	</div>

	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Назва</th>
					<th scope="col">По замовчуванню</th>
					<th scope="col">Значення</th>
					<th scope="col">Коментар</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($settings)): ?>
					<?php foreach ($settings as $setting): ?>
						<tr>
							<td><?= $setting['name']; ?></td>
							<td><?= $setting['default']; ?></td>
							<td><?= $setting['value']; ?></td>
							<td><?= $setting['comment']; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
