<div id="balance">
	<h2 class="text-center">Баланс</h2>
	<div class="row">
		<div class="col-md-6">
			<h4 class="text-center">Активи</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Основні засоби</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($main_assets_storages)): ?>
					<?php foreach ($main_assets_storages as $m_s_storage): ?>
						<tr>
							<td><?= $m_s_storage['name']; ?></td>
							<td>0.00</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Запаси</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($storages)): ?>
					<?php foreach ($storages as $storage): ?>
						<tr>
							<td><?= $storage['name']; ?></td>
							<td>0.00</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Гроші</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($wallets)): ?>
					<?php foreach ($wallets as $wallet): ?>
						<tr>
							<td><?= $wallet['name']; ?></td>
							<td><?= $wallet['checkout']; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Дебіторська заборгованість</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<h4 class="text-center">Пасиви</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Зобов'язання</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
				</tbody>
			</table>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Власний капітал</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td></td>
					<td></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
