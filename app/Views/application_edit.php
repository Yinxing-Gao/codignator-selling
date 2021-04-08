<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>
<div class="container" id="container_application">
	<div class="py-5 text-center">
		<!--		<img class="d-block mx-auto mb-4"-->
		<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
		<!--			 width="300">-->
		<h2><?= $edit ? 'Редагування заявки ' . $edited_app->id . ' на виділення коштів' : 'Заявка на виділення коштів'; ?>
			<a href="https://www.youtube.com/embed/usjRk8i3aUo" class="play_video_instruction">
				<img src="../../img/yt.png"/>
			</a>
		</h2>
		<p class="lead">Всі поля є обов'язковими для заповнення, прискорюють швидкість розгляду і збільшують
			вірогідність позитивної відповіді <br/><a href="/application/rules">Регламент подання заявок</a></p>
	</div>
	<div class="row">

		<div class="col-md-12">
			<form class="needs-validation" novalidate="">
				<?php if ($edit): ?>
					<input type="hidden" name="app_id" value="<?= $edited_app->id; ?>"/>
				<?php endif; ?>
				<div class="row">
					<div class="col-md-6">
						<label for="department">Департамент</label>
						<select class="form-control" id="department" name="department_id">
							<option value=""></option>
							<?php if (!empty($departments)): ?>
								<?php foreach ($departments as $department): ?>
									<option
										<?= (!empty($edited_app->department_id && $edited_app->department_id == $department['id'])) ? 'selected' : ''; ?>
										value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-6">
						<label for="lastName">Автор</label>
						<input type="text" class="form-control" id="author" name="author" placeholder=""
							   value="<?= !empty($edited_app->author) ? $edited_app->author : ''; ?>"
							   required="" <?= !empty($edited_app->author) ? 'readonly' : '' ?>
							   data-id=" <?= !empty($edited_app->author_id) ? $edited_app->author_id : ''; ?>">
						<input type="hidden" class="form-control" name="author_id" placeholder=""
							   value="<?= !empty($edited_app->author_id) ? $edited_app->author_id : ''; ?>"/>

					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="username">Вартість</label>
						<div class="input-group">

							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"
								   required="" value="<?= !empty($edited_app->amount) ? $edited_app->amount : ''; ?>">
							<select class=" form-control" id="currency" name="currency" required="">
								<option
									<?= (!empty($edited_app->currency && $edited_app->currency == 'UAH')) ? 'selected' : ''; ?>
									value="UAH">₴
								</option>
								<option
									<?= (!empty($edited_app->currency && $edited_app->currency == 'USD')) ? 'selected' : ''; ?>
									value="USD">$
								</option>
								<option
									<?= (!empty($edited_app->currency && $edited_app->currency == 'EUR')) ? 'selected' : ''; ?>
									value="EUR">€
								</option>
							</select>
							<select class="form-control" id="type" name="type_id" required="">
								<?php if (!empty($types)): ?>
									<?php foreach ($types as $id => $type): ?>
										<option
											<?= (!empty($edited_app->type_id && $edited_app->type_id == $id)) ? 'selected' : ''; ?>
											value="<?= $id; ?>"><?= $type; ?> </option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<label for="date_for">Дата на коли потрібно</label>
						<input type="date" class="form-control" id="date_for" placeholder="" name="date_for"
							   value="<?= !empty($edited_app->date_for) ? date("Y-m-d", $edited_app->date_for) : ''; ?>"
							   required="">
					</div>
					<div class="col-md-5">
						<label for="project"><input type="radio" id="to_project"/>Відноситься до проекту</label>
						<select class="form-control" id="project" required="" name="project_id">
							<option value=""></option>
							<?php if (!empty($projects)): ?>
								<?php foreach ($projects as $project): ?>
									<option
										<?= (!empty($edited_app->project_id && $edited_app->project_id == $project['id'])) ? 'selected' : ''; ?>
										value="<?= $project['id']; ?>"><?= $project['name']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

				</div>

				<div class="row">
					<div class="col-md-4">
						<label for="product">Товар/послуга</label>
						<input class="form-control" id="product" name="product"
							   value="<?= !empty($edited_app->product) ? $edited_app->product : ''; ?>"
							   placeholder="тут має бути коротка назва  товару чи послуги" required="">
					</div>
					<div class="col-md-4">
						<label for="product">Рахунок</label>
						<input type="file" multiple="multiple"
							   accept=".txt,image/*,application/*,application/x-msexcel">
						<a href="#" class="upload_files button">Завантажити</a>
						<div class="uploaded_file">
							<?php if (!empty($edited_app->order_files)) : ?>
								<?php $files = json_decode(stripslashes($edited_app->order_files)); ?>
								<?php if (!empty($files)): ?>
									<?php foreach ($files as $file): ?>
										<span>
									<a href="<?= $file; ?>" class="order_file"
									   target="_blank"><?= substr($file, strripos($file, '/') + 1); ?></a>
											<!--										<span class="delete_uploaded_file">x</span>-->
										<img class="delete_uploaded_file" src="../../img/trash-icon.jpg"/>
									</span>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endif; ?>
						</div>
						<input type="hidden" name="uploaded_files" class="ajax-reply"
							   value="<?= $edited_app->order_files; ?>"/>
					</div>
					<div class="col-md-4">
						<label for="username">Стаття розходів</label>
						<select class="form-control" id="expenses" name="article_id" required="">
							<option value=""></option>
							<?php if (!empty($expenses)): ?>
								<?php foreach ($expenses as $expense_item): ?>
									<option
										<?= (!empty($edited_app->article_id && $edited_app->article_id == $expense_item['id'])) ? 'selected' : ''; ?>
										value="<?= $expense_item['id']; ?>"><?= $expense_item['item']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<label for="username">Ситуація</label>
						<input type="" class="form-control" id="situation" name="situation"
							   value="<?= !empty($edited_app->situation) ? $edited_app->situation : ''; ?>"
							   placeholder="тут коротко треба описати ситуації, результатом якої є потреба у фінансуванні"
							   required="">
					</div>

					<div class="col-md-12">
						<label for="data">Дані</label>
						<textarea style="height: 100px" class="form-control" id="data" name="data"
								  placeholder="тут треба МАКСИМАЛЬНО детально описати всі дані, на основі яких прийнято рішення про потребу в фінансуванні. Наприклад, якщо це деталі на апарат, то бажено вказати дату, коли апарат повинен бути готовим, тривалість доставки і т.д.Це дані повинні дати вичерпну відповідь на запитання 'чому це треба купити взагалі?' і 'чому це треба придбати саме зараз?'"
								  required=""><?= !empty($edited_app->data) ? $edited_app->data : ''; ?></textarea>
					</div>

					<div class="col-md-7">
						<label for="decision">Рішення</label>
						<input type="" class="form-control" id="decision" name="decision" placeholder="" required=""
							   value="<?= !empty($edited_app->decision) ? $edited_app->decision : ''; ?>">
					</div>
					<div class="col-md-3">
						<label for="decision"><input name="repeat" id="repeat" type="checkbox"
													 <?= !empty($edited_app->repeat_period) ? 'checked="checked"' : ''; ?>/>Повторити</label>
						<select class="form-control" id="repeat_type" name="repeat_type" required="">
							<option value=""></option>
							<option
								<?= (!empty($edited_app->repeat_period && $edited_app->repeat_period == 'day')) ? 'selected' : ''; ?>
								value="day">Щодня
							</option>
							<option
								<?= (!empty($edited_app->repeat_period && $edited_app->repeat_period == 'week')) ? 'selected' : ''; ?>
								value="week">
								Щотижня
							</option>
							<option
								<?= (!empty($edited_app->repeat_period && $edited_app->repeat_period == 'month')) ? 'selected' : ''; ?>
								value="month">
								Щомісяця
							</option>
						</select>
					</div>
					<div class="col-md-2">
						<label for="date_for">Дата закінчення</label>
						<input type="date" class="form-control" id="repeat_end_date" placeholder=""
							   name="repeat_end_date"
							   value="<?= !empty($edited_app->end_date) ? date('Y-m-d', $edited_app->end_date) : ''; ?>"
							   required="">
					</div>
				</div>

				<div style="display: none">
					<h4 style="text-align: center">Керівник департаменту</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="department_chief" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="department_chief" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>

					<h4 style="text-align: center">Голова рекомендаційної ради</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="finance_chief" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="finance_chief" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>

					<h4 style="text-align: center">Директор</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="director" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="director" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label for=""></label>
						<button data-action="edit" class="btn btn-primary btn-lg btn-block btn_send_app" type="submit">
							Відправити
						</button>
					</div>
				</div>
		</div>
	</div>

</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
