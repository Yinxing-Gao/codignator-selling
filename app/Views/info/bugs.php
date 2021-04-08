<div id="bugs">
	<h2 class="text-center">Помилки та баги
		<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction">
			<img src="../../../icons/bootstrap/play.svg"/>
		</a>
	</h2>
	<p class="text-center">Нам дуже жаль, що ви знайшли помилку в системі, заповніть,будь ласка, форму нижче і ми
		виправимо їх найближчим часом</p>
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
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($bugs)): ?>
					<?php foreach ($bugs as $bug): ?>
						<tr data-bug_id="<?= $bug['id']; ?>">
							<td><?= $bug['id']; ?></td>
							<td><?= date('d.m.Y H:i:s', $bug['date']); ?></td>
							<td><?= $bug['author_name']; ?> <?= $bug['author_surname']; ?></td>
							<td><?= $bug['title']; ?></td>
							<td><?= $bug['message']; ?></td>
							<td><?= $bug['screenshot']; ?></td>
							<td>
								<?php if (!empty($user_id) && $bug['author_id'] == $user_id): ?>
									<img src="../../../icons/bootstrap/trash.svg" class="delete_bug"/>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
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
								<option value="error">Помилка чи баг</option>
								<option value="bug">Ідея чи пропозиція</option>
							</select>
						</div>

						<div class="col-md-12">
							<label for=""></label>
							<button class="btn btn-primary btn-lg btn-block btn_add_bug" type="submit">Відправити
							</button>
						</div>
					</div>
				</form>

		</div>
	</div>
</div>

