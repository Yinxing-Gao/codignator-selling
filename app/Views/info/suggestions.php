<div id="suggestions">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Ідеї та пропозиції</h3>
		</div>
		<div class="col-md-3 title_div_icons"></div>
	</div>
	<p class="text-center">На цій сторінці ви можете проголосувати за ідеї інших клієнтів і ідеї, які наберуть найбільшу
		кількість голосів
		будуть реалізовані безкоштовно. Також можна добавити свою ідею</p>
	<div class="row">
		<div class="col-md-9">
			<table class="table table-striped">
				<thead class="thead-dark">
				<tr>
					<th>#</th>
					<th>Дата</th>
					<th>Автор</th>
					<th>Заголовок</th>
					<th>Опис</th>
					<th>Скріншот</th>
					<th>Голоси</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($suggestions)): ?>
					<?php foreach ($suggestions as $suggestion): ?>
						<tr data-suggestion_id="<?= $suggestion['id']; ?>">
							<td data-label="#"><?= $suggestion['id']; ?></td>
							<td data-label="Дата"><?= date('d.m.Y H:i:s', $suggestion['date']); ?></td>
							<td data-label="Автор"><?= $suggestion['author_name']; ?> <?= $suggestion['author_surname']; ?></td>
							<td data-label="Заголовок"><?= $suggestion['title']; ?></td>
							<td data-label="Опис"><?= $suggestion['message']; ?></td>
							<td data-label="Скріншот"><?= $suggestion['screenshot']; ?></td>
							<td data-label="Голоси">
								<div class="vote_for_suggestion">
									<?php if ($suggestion['voted']) : ?>
										<img src="../../../icons/bootstrap/heart-fill.svg"/>
									<?php else: ?>
										<img src="../../../icons/bootstrap/heart.svg"/>
									<?php endif; ?>
								</div>
								<?= $suggestion['votes_amount']; ?>
							</td>
							<td>
								<?php if (!empty($user_id) && $suggestion['author_id'] == $user_id): ?>
									<img src="../../../icons/bootstrap/trash.svg" class="delete_suggestion"/>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<?php if (!empty($account_id) && !empty($user_id)): ?>
				<h4>Додати</h4>
				<form>
					<input type="hidden" class="form-control" name="account_id"
						   value="<?= $account_id; ?>">

					<input type="hidden" class="form-control" name="author_id"
						   value="<?= $user_id; ?>">
					<div class="row">
						<div class="col-xs-12 col-md-12">
							<label for="">
								Заголовок*</label>
							<input class="form-control" type="text" name="title">
						</div>

						<div class="col-xs-12 col-md-12">
							<label for="description">
								Повідомлення*</label>
							<textarea class="form-control" id="message" name="message"
									  placeholder="" required=""></textarea>
						</div>
						<div class="col-xs-12 col-md-12">
							<label for="">
								Скріншот ( використовуйте програму <a target="_blank" href="https://app.prntscr.com/uk">Lightshot</a>)</label>
							<input class="form-control" type="text" name="screenshot">
						</div>
						<div class="col-xs-12 col-md-12">
							<label for="">
								Тип</label>
							<select class="form-control" name="type" required="">
								<option value="suggestion">Ідея чи пропозиція</option>
								<option value="error">Помилка чи баг</option>
							</select>
						</div>

						<div class="col-md-12">
							<label for=""></label>
							<button class="btn btn-primary btn-lg btn-block btn_add_suggestion" type="submit">Відправити
							</button>
						</div>
					</div>
				</form>
			<?php else: ?>
				<div style="font-size: 12px">
					<br/>
					<p>
						Щоб проголосувати ви маєте бути <a target="_blank" href="http://app.fineko.space/user/login">залогінені</a>
						в системі.
					</p>
					<p>
						Якщо ви ще не є користувачем FINEKO, а тільки приглядаєте собі систему, то ви можете
						ознайомитися з
						можливостями системи отримавши <a href="http://app.fineko.space/user/registration"
														  target="_blank">безкоштовний
							демо доступ на 2 тижні тут</a>.
					</p>

					<p>Також ви можете попередньо переглянути <a
							href="http://landing.fineko.space/features.html" target="_blank">відео</a> про можливості
						системи</p>

					<p>Якщо ви готові уже оплатити сервіс, але вам не хватає якогось ключового інструмента - також <a
							href="http://app.fineko.space/user/registration" target="_blank">реєструйтесь</a> в системі.
						І
						добавляйте свій телеграм в меню налаштування. Як тільки інструмент буде добавлено вам прийде
						сповіщення</p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

