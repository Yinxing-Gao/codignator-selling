<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>
<div class="container" id="container_working_hours">
	<div class="py-5 text-center">
		<h2>Зарплатна відомість
			<a href="https://www.youtube.com/embed/yXpe4N81blo" class="play_video_instruction"><img
					src="<?= base_url(); ?>/icons/bootstrap/play.svg"/></a>
		</h2>
	</div>

	<div class="row">
		<div class="col-md-12">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="selected_user_id" value="<?= $user_id; ?>">
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
					<div class="col-md-10"></div>


				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<h4>Години роботи</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Дата</th>
					<?php if (!empty($workers)): ?>
						<?php foreach ($workers as $worker): ?>
							<th scope="col">Години
								роботи <?= $worker['name']; ?> <?= $worker['surname']; ?></th>
						<?php endforeach; ?>
					<?php endif; ?>
					<th>Планована кількість годин</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($first_day_of_month) && !empty($last_day_of_month)): ?>
					<?php for ($date = $first_day_of_month; $date < $last_day_of_month; $date += 60 * 60 * 24) : ?>
						<tr>
							<td><?= date('d.m.Y', $date); ?> (<?= $weeks[date("N", $date) - 1]; ?>)</td>
							<?php if (!empty($workers)): ?>
								<?php foreach ($workers as $worker): ?>
									<td><input type="number" class="form-control hours_input"
											   data-user_id="<?= $worker['id']; ?>"
											   data-date="<?= date('d.m.Y', $date); ?>"
											   value="<?= !empty($month_hours[$worker['id']][date('d.m.Y', $date)]) ? $month_hours[$worker['id']][date('d.m.Y', $date)] : 8; ?>"/>
									</td>
								<?php endforeach; ?>
							<?php endif; ?>
							<td>8</td>
						</tr>
					<?php endfor; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
		<div class="col-md-6">
			<h4>Начислення з проектів</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Проект</th>
					<th scope="col">Дата</th>
					<th scope="col">Сума</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<tr></tr>
				</tbody>
			</table>
			<h4>Бонуси</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Дата</th>
					<th scope="col">Сума</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<tr></tr>
				</tbody>
			</table>
			<h4>Штрафи</h4>
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Дата</th>
					<th scope="col">Сума</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<tr></tr>
				</tbody>
			</table>
		</div>
	</div>

</div>

