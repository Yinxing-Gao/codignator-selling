<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал


?>
<div id="nova_poshta_list">
	<div class="text-center">
		<h2>Нова Пошта
			<a href="https://www.youtube.com/embed/zcbBAuG3Xls" class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg"/>
			</a>
		</h2>
	</div>

	<div class="row">
		<div class="col-md-9">
			<p>Перейти в <a href="https://t.me/FinekoBot">Telegram bot</a></p>
			<table class="table tablesorter contractors_list">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">first name</th>
					<th scope="col">last name</th>
					<th scope="col">username</th>
					<th scope="col">language_code</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($telegram_info)): ?>
					<tr>
						<td><?= $telegram_info['id']; ?></td>
						<td><?= $telegram_info['first_name']; ?></td>
						<td><?= $telegram_info['last_name']; ?></td>
						<td>
							<a target="_blank" href="https://t.me/<?= $telegram_info['username']; ?>">
								@<?= $telegram_info['username']; ?>
							</a>
						</td>
						<td><?= $telegram_info['language_code']; ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<div class="row">
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
	</div>



