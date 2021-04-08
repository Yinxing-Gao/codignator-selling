<div id="telegram_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Телеграм</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?= base_url() ?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_telegram_sidenav" data-title="Познайомитись з ботом">
				<img src="<?= base_url() ?>/icons/fineko/telegram.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<p>Перейти в <a href="https://t.me/FinekoBot">Telegram bot</a></p>
			<table class="table tablesorter contractors_list">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Ім'я</th>
					<th scope="col">Прізвище</th>
					<th scope="col">Ім'я користувача</th>
					<th scope="col">Мова</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($telegram_info)): ?>
					<tr>
						<td data-label="#"><?= $telegram_info['id']; ?></td>
						<td data-label="Ім'я"><?= $telegram_info['first_name']; ?></td>
						<td data-label="Прізвище"><?= $telegram_info['last_name']; ?></td>
						<td data-label="Ім'я користувача">
							<a target="_blank" href="https://t.me/<?= $telegram_info['username']; ?>">
								@<?= $telegram_info['username']; ?>
							</a>
						</td>
						<td data-label="Мова"><?= $telegram_info['language_code']; ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="telegram_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="telegram_sidenav_content sidenav_content">
		<form class="contractor_form">
			<div class="col-md-12">
				<p>Для того, щоб добавити чи змінити акаунт Телеграм, натисніть на кнопку нижче</p>
			</div>
			<div class="col-md-12">
				<label for=""></label>
				<button class="btn btn-primary btn-lg btn-block generate_telegram_code" data-action="add"
						type="submit">Згенерувати код
				</button>
			</div>
			<div class="col-md-12 telegram_code">
				<label for=""></label>
				<input class="form-control" id="telegram_code" type="text" disabled value="23134112"/>
			</div>
			<div class="col-md-12 telegram_go_to_bot">
				<p>Тепер зайдіть в Телеграм чат бота FINEKO, виберіть пункт меню "зареєструвати код", відправте
					боту цей код, і зачекайте, доки він вас зареєструє</p>
				<a target="_blank" href="https://t.me/FinekoBot">@FinekoBot</a>
			</div>


			<input type="hidden" name="account_id"
				   value="<?= $account_id; ?>">
		</form>
	</div>
</div>



