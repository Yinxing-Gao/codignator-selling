<div id="user_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Користувачі</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?= base_url() ?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нового користувача">
				<img src="<?= base_url() ?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<div>
		<p>Для реєстрації інших співробітників потрібно передати їм дану ссилку:</p>
		<p>
			<a href="<?= base_url() ?>user/registration/<?= $account->ref; ?>">
				<?= base_url() ?>user/registration/<?= $account->ref; ?>
			</a>
		</p>
	</div>
	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Ім'я</th>
					<th scope="col">Посади</th>
					<!--					<th scope="col"></th>-->
					<!--					<th scope="col"></th>-->
					<th scope="col">Дата останнього<br/>входу в систему</th>
					<th scope="col"></th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($user_list)): ?>
					<?php foreach ($user_list as $user): ?>
						<tr>
							<td data-label="#"><?= $user['id']; ?></td>
							<td data-label="Ім'я"><?= $user['name']; ?> <?= $user['surname']; ?></td>
							<td data-label="Посади">
								<?php if (!empty($user['positions'])): ?>
									<ul>
										<?php foreach ($user['positions'] as $position): ?>
											<?php if (strlen($position['name']) > 0): ?>
												<li class="user_positions"><?= $position['name']; ?>
													(<?= $position['department_name']; ?>)
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</td>
							<!--							<td><a href="#">Каси</a></td>-->
							<!--							<td>Заявки:<br/>-->
							<!--								<a href="/application/user/-->
							<? //= $user['id']; ?><!--">В роботі </a>-->
							<!--								<a href="/application/user/-->
							<? //= $user['id']; ?><!--/approved">одобрені </a>,-->
							<!--								<a href="/application/user/-->
							<? //= $user['id']; ?><!--/payed">оплачені </a>-->
							<!--							</td>-->
							<!--							<td><a href="/operation/user/-->
							<? //= $user['id']; ?><!--">Операції</a></td>-->

							<!--							</td>-->
							<td data-label="Дата останнього входу в систему"><?= !empty($user['last_activity']) ? date("d.m.Y H:i:s", $user['last_activity']) : ''; ?></td>
							<td><a href="/salary/hours/<?= date('m.Y'); ?>/<?= $user['id']; ?>">Зарплатна відомість</a>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>


