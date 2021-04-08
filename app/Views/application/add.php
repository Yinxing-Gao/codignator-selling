<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>
<div class="container" id="application_add">
	<div class="py-5 text-center">
		<h2>Заявка на виділення коштів

			<a href="/rules/page/application_add" class="rules_btn">
				<img src="../../../icons/bootstrap/info.svg"/>
			</a>
		</h2>
		<p class="lead">Всі поля є обов'язковими для заповнення, прискорюють швидкість розгляду і збільшують
			вірогідність позитивної відповіді</p>
	</div>
	<div class="row">
		<div class="col-xl-9">
			<iframe style="margin-top: 35px;" width="100%" height="415" src="https://www.youtube.com/embed/usjRk8i3aUo"
					frameborder="0"
					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
					allowfullscreen></iframe>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<strong>«Ситуація»</strong> - тут коротко треба описати ситуації, результатом якої є потреба у
			фінансуванні<br/>
			<i>Заявка може позначатися терміновою, якщо необхідність оплати з'явилася менше ніж за тиждень до необхідної
				дати
				оплати. Це поле не варто використовувати, щоб замінити планування і подавати заявку в останній момент.
				Це буде
				штрафуватися. Ваша задача робити так, щоб кількість термінових заявок щодня зменшувалася.</i><br/><br/>
			<strong>«Дані»</strong> - тут треба МАКСИМАЛЬНО детально описати всі дані, на основі яких прийнято рішення
			про
			потребу в фінансуванні. Наприклад, якщо це деталі на апарат, то бажено вказати дату, коли апарат повинен
			бути
			готовим,
			тривалість доставки і т.д.Це дані повинні дати вичерпну відповідь на запитання 'чому це треба купити
			взагалі?' і
			'чому це треба придбати саме зараз<br/><br/>
			<strong>«Рішення»</strong> - рішення прийняте на основ цих даних. Наприклад, «оплатити покупку принтера» або
			«замінити деталь» або
			«повернути борг»<br/><br/>

			<a href="/rules/page/application_add">Детальніше...</a>
		</div>
		<div class="col-xl-3">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="author_id" value="<?= $user_id; ?>"/>
				<div class="row">
					<div class="col-xs-12 col-md-12 element">
						<label for="contractor_id">
							Поставщик чи підрядник</label>
						<select class="form-control" required="" name="contractor_id">
							<option value=""></option>
							<?php if (!empty($sidenav_contractors)): ?>

								<?php foreach ($sidenav_contractors as $sidenav_contractor): ?>
									<option
										<?= !empty($sidenav_contractors['is_default']) ? 'selected' : ''; ?>
										value="<?= $sidenav_contractor['id']; ?>"><?= $sidenav_contractor['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<label for="username">Вартість*</label>
						<div class="input-group">

							<input type="number" class="form-control" name="amount"
								   placeholder="сума"
								   required="">
							<select class="form-control" name="currency" required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
							<select class="form-control" id="type" name="type_id" required="">
								<?php if (!empty($types)): ?>
									<?php foreach ($types as $id => $type): ?>
										<option value="<?= $id; ?>"><?= $type; ?> </option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<label for="date_for">Дата на коли потрібно*&nbsp;&nbsp;</label>
						<input type="date" class="form-control" placeholder="" name="date_for"
							   required="">
					</div>
					<!--					<div class="col-md-12">-->
					<!--						<label>-->
					<!--							<input name=fixed" id="fixed"-->
					<!--								   type="checkbox"/>Фіксована</label>-->
					<!--					</div>-->


					<div class="col-md-12">
						<label for="project">Кому</label>
						<select class="form-control" required="" name="authority_id">
							<?php if (!empty($authorities)): ?>
								<?php foreach ($authorities as $department): ?>
									<optgroup label="<?= $department['name']; ?>">
										<?php if (!empty($department['children'])): ?>
											<?php foreach ($department['children'] as $authority): ?>
												<option
													value="<?= $authority['user_id']; ?>"><?= $authority['position_name']; ?> - <?= $authority['user_name']; ?> <?= $authority['user_surname']; ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</optgroup>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<label>
							<input type="radio" checked name="pay_from" class="form-control" value="direct"/>
							<span>Оплатити напряму</span>
						</label>
					</div>

					<div class="col-md-12">
						<label>
							<input type="radio" name="pay_from" class="form-control" value="transfer"/>
							<span>Передати мені на оплату</span>
						</label>
					</div>

					<div class="col-md-12">
						<label for="project">Відноситься до проекту</label>
						<select class="form-control" id="project" required="" name="project_id">
							<option value=""></option>
							<?php if (!empty($projects)): ?>
								<?php foreach ($projects as $project): ?>
									<option value="<?= $project['id']; ?>"><?= $project['name']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<label for="product">Товар/послуга*</label>
						<input class="form-control" id="product" name="product"
							   placeholder="" required="">
					</div>

					<div class="col-md-12">
						<label for="product">Рахунок</label>
						<input type="file" multiple="multiple" class="form-control"
							   accept=".txt,image/*,application/*,application/x-msexcel">
						<!--						<a href="#" class="upload_files button">Завантажити</a>-->
						<div class="uploaded_file"></div>
						<input type="hidden" name="uploaded_files" class="ajax-reply"/>
					</div>

					<div class="col-md-12">
						<label for="order_names">Номери рахунків<i class="note">Можна писати кілька через
								кому</i></label>
						<input name="order_names" class="form-control" type="text"/>
					</div>

					<div class="col-md-12">
						<label for="username">Ситуація*
							<input type="" class="form-control" id="situation" name="situation"
								   placeholder="тут коротко треба описати ситуації, результатом якої є потреба у фінансуванні"
								   required="">
					</div>

					<div class="col-md-12">
						<label for="username">Стаття розходів</label>
						<select class="form-control" id="expenses" name="article_id" required="">
							<option value=""></option>
							<?php if (!empty($expenses)): ?>
								<?php foreach ($expenses as $expense_item): ?>
									<option
										value="<?= $expense_item['id']; ?>"><?= $expense_item['item']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-12">
						<label for="data">Дані*</label>
						<textarea style="height: 150px" class="form-control" id="data" name="data"
								  placeholder="тут треба МАКСИМАЛЬНО детально описати всі дані, на основі яких прийнято рішення про потребу в фінансуванні. Наприклад, якщо це деталі на апарат, то бажено вказати дату, коли апарат повинен бути готовим, тривалість доставки і т.д.Це дані повинні дати вичерпну відповідь на запитання 'чому це треба купити взагалі?' і 'чому це треба придбати саме зараз?'"
								  required=""></textarea>
					</div>

					<div class="col-md-12">
						<label for="decision">Рішення*</label>
						<input type="" class="form-control" id="decision" name="decision" placeholder=""
							   required="">
					</div>

					<div class="col-md-12">
						<label>
							<input type="checkbox" id="urgently" name="urgently"
								   class="form-control"/>Термінова</label>
					</div>

					<div style="display:none" class="col-md-12">
						<label for="decision"><input name="repeat" id="repeat" type="checkbox"/>Операція буде
							повторюватися</label>
						<select class="form-control" id="repeat_type" name="repeat_type" required="">
							<option value=""></option>
							<option value="day">Щодня</option>
							<option value="week">Щотижня</option>
							<option value="month">Щомісяця</option>
						</select>
					</div>

					<div style="display:none" class="col-md-12">
						<label for="date_for">Дата закінчення</label>
						<input type="date" class="form-control" id="repeat_end_date" placeholder=""
							   name="repeat_end_date"
							   required="">
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
					<div class="col-md-12">
						<label for=""></label>
						<button data-action="add" class="btn btn-primary btn-lg btn-block btn_send_app"
								type="submit">
							Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

