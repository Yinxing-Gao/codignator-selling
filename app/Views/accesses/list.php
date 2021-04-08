<div id="accesses">
	<h2 class="text-center">Доступи департаменту <?= $department->name; ?>
		<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction">
			<img src="../../../icons/bootstrap/play.svg"/>
		</a>
		<input type="hidden" id="access_department_id" value="<?= $department->id; ?>"/>
	</h2>

	<div class="row">
		<div class="col-md-4">
			<h4 class="text-center">Меню і сторінки</h4>
			<table class="table table-striped">
				<thead class="thead-dark">
				<tr>
					<th>Назва</th>
					<th style="width: 30px" class="text-center">
						<img src="../../../icons/bootstrap/bag-check-white.svg"/>
					</th>
					<th style="width: 30px" class="text-center">

					</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($menu)) : ?>
					<?php foreach ($menu as $menu_item): ?>
						<tr data-access_id="<?= $menu_item['access_id']; ?>">
							<td><?= $menu_item['name']; ?></td>
							<td class="text-center" style="width: 30px">
								<input class="form-control" type="checkbox"
									   <?= in_array($menu_item['access_id'], $department_accesses) ? 'checked' : ''; ?> />
							</td>
							<td class="text-center" style="width: 30px">
							</td>
						</tr>
						<?php if (!empty($menu_item['children'])) : ?>
							<?php foreach ($menu_item['children'] as $menu_sub_item): ?>
								<tr data-access_id="<?= $menu_sub_item['access_id']; ?>">
									<td>
										<img src="../../../icons/bootstrap/dot.svg"/>
										<?= $menu_sub_item['name']; ?>
									</td>
									<td style="width: 30px">
										<input class="form-control access_checkbox" type="checkbox"
											   <?= in_array($menu_sub_item['access_id'], $department_accesses) ? 'checked' : ''; ?>/>
									</td>
									<td style="width: 30px">
										<img class="access_details" src="../../../icons/bootstrap/ui-checks.svg"/>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			<h4 class="text-center">Вміст</h4>
			<div class="access_details_block"></div>
		</div>
		<div class="col-md-4">
			<h4 class="text-center">Сайднави</h4>
			<table class="table table-striped">
				<thead class="thead-dark">
				<tr>
					<th>Назва</th>
					<th style="width: 30px" class="text-center">
						<img src="../../../icons/bootstrap/bag-check-white.svg"/>
					</th>
					<th style="width: 30px" class="text-center">

					</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($sidenav_accesses)) : ?>
					<?php foreach ($sidenav_accesses as $access): ?>
						<tr data-access_id="<?= $access['id']; ?>">
							<td><?= $access['name']; ?></td>
							<td class="text-center" style="width: 30px">
								<input class="form-control access_checkbox" type="checkbox"
									   <?= in_array($access['id'], $department_accesses) ? 'checked' : ''; ?>/>
							</td>
							<td class="text-center" style="width: 30px">
								<img class="access_details" src="../../../icons/bootstrap/ui-checks.svg"/>
							</td>
						</tr>

					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>

			<h4 class="text-center">Телеграм операції</h4>
			<table class="table table-striped">
				<thead class="thead-dark">
				<tr>
					<th>Назва</th>
					<th style="width: 30px" class="text-center">
						<img src="../../../icons/bootstrap/bag-check-white.svg"/>
					</th>
					<th style="width: 30px" class="text-center">

					</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($telegram_accesses)) : ?>
					<?php foreach ($telegram_accesses as $access): ?>
						<tr data-access_id="<?= $access['id']; ?>">
							<td><?= $access['name']; ?></td>
							<td class="text-center" style="width: 30px">
								<input class="form-control access_checkbox" type="checkbox"
									   <?= in_array($access['id'], $department_accesses) ? 'checked' : ''; ?>/>
							</td>
							<td class="text-center" style="width: 30px">
								<img class="access_details" src="../../../icons/bootstrap/ui-checks.svg"/>
							</td>
						</tr>

					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
