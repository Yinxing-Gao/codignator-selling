<div id="wallets">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Каси (гаманці)</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_wallet_sidenav" data-title="Додати гаманець">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<label for="username">Департамент</label>
			<select class="form-control" id="department_id" required="">
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-2">
			<label>Включити всі піддепартаменти</label>
			<input type="checkbox" class="form-control" <?= $with_children ? 'checked' : ''; ?>/>
		</div>
		<div class="col-md-6"></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th>Користувач</th>
					<th>Каса</th>
					<!--				<th>Баланс FINEKO</th>-->
					<th>Баланс</th>
					<th>Використано кредиту</th>
					<th>Тип</th>
					<th>Форма</th>
					<th>Банк</th>
					<!--				<th>Сума в гривні </th>-->
					<th><img src="../../../icons/bootstrap/eye-white.svg"/></th>
					<th>Віртуальний</th>
					<th>По замовчуванню</th>
					<th>Для оплат</th>
					<th>Для витрат</th>
					<th>Департамент</th>
					<th></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($wallets)): ?>
					<?php foreach ($wallets as $wallet): ?>
						<tr data-wallet_id="<?= $wallet['id']; ?>">
							<td data-label="Користувач">
								<?= $wallet['user_name']; ?> <?= $wallet['user_surname']; ?>
							</td>
							<td data-label="Каса">
								<!--							--><? //= $wallet['user_name']; ?><!-- -->
								<? //= $wallet['user_surname']; ?><!----><? // //= $wallet['wallet']['user_surname']; ?>
								<!--							( -->
								<?= $wallet['name']; ?>
								<!--							)-->
							</td>
							<td data-label="Баланс">
								<!--					--><?php //var_dump($balances[$wallet['id']]); die(); ?>

								<!--							--><?php //if ($wallet['wallet_type'] == 'card'): ?>
								<!--								--><? //= !empty($balances[$wallet['id']]) ?
								//									number_format($balances[$wallet['id']]['balance'], 0, ',', '&nbsp;') :
								//									''; ?><!-- --><? //= $currencies_names[$wallet['currency']]; ?>
								<!--							--><?php //else: ?>
								<?= number_format((float)$wallet['checkout'], 0, ',', '&nbsp;'); ?> <?= $currencies_names[$wallet['currency']]; ?>
								<!--							--><?php //endif; ?>
							</td>
							<td data-label="Використано кредиту">
								<?php if ($wallet['wallet_type'] == 'card'): ?>
									<?= !empty($balances[$wallet['id']]) ? $balances[$wallet['id']]['fin_limit'] : ''; ?> <?= $currencies_names[$wallet['currency']]; ?>
								<?php endif; ?>
							</td>
							<td data-label="Тип"><?= $entity_types[$wallet['type_id']]; ?></td>
							<td data-label="Форма"><?= !empty($forms[$wallet['wallet_type']]) ? $forms[$wallet['wallet_type']] : ''; ?></td>
							<td data-label="Банк"><?= $wallet['bank_name'] ?></td>
							<td data-label="Відображати">
								<input type="checkbox" class="form-control" data-title="відображати"
									   <?= !empty($wallet['is_shown']) ? 'checked' : ''; ?>/>
							</td>
							<td data-label="Віртуальний">
								<input type="checkbox" class="form-control" data-title="віртуальний"
									   <?= !empty($wallet['is_virtual']) ? 'checked' : ''; ?>/>
							</td>

							<td data-label="По замовчуванню">
								<input type="radio" class="form-control" name="is_default"
									   data-title="по замовчуванню"
									   <?= !empty($wallet['is_default']) ? 'checked' : ''; ?>/>
							</td>
							<td data-label="Для оплат">
								<input type="checkbox" class="form-control"
									   data-title="Для оплат"
									   <?= !empty($wallet['for_income']) ? 'checked' : ''; ?>/>
							</td>
							<td data-label="Для витрат">
								<input type="checkbox" class="form-control"
									   data-title="Для витрат"
									   <?= !empty($wallet['for_expense']) ? 'checked' : ''; ?>/>
							</td>
							<td data-label="Департамент">
								<?= $wallet['department_name']; ?>
							</td>

							<td data-label="Редагувати">
								<img class="edit_wallet icon" src="../../../icons/fineko/edit.svg"/>
							</td>

							<td data-label="Видалити">
								<?php if (empty($wallet['is_default'])): ?>
									<img class="delete_wallet icon" src="../../../icons/fineko/delete.svg"/>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="wallet_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="wallet_sidenav_content">
		<h4>Додати гаманець</h4>
		<form>
			<div class="col-xs-12 col-md-12">
				<label for="date_for">Назва</label>
				<input type="text" class="form-control" id="name" placeholder=""
					   value=""
					   name="name" required=""/>
			</div>
			<div class="col-xs-12 col-md-12">
				<label for="date_for">Тип</label>
				<select class="form-control" id="type" name="type_id" required="">
					<?php if (!empty($entity_types)): ?>
						<?php foreach ($entity_types as $id => $type): ?>
							<option value="<?= $id; ?>"><?= $type; ?> </option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="col-xs-12 col-md-12">
				<label class="radio_label">
					<input type="radio" class="form-control" id="cash" name="wallet_type"
						   required="" value="cash" checked>
					Готівка
				</label>
				<label class="radio_label">
					<input type="radio" class="form-control" id="card" name="wallet_type"
						   required="" value="card">
					Карта / Безнал
				</label>
			</div>
			<div class="col-xs-12 col-md-12 banks">
				<label for="date_for">Банк</label>
				<select class="form-control" id="banks" name="bank_id" required="">
					<?php if (!empty($banks)): ?>
						<?php foreach ($banks as $bank): ?>
							<option value="<?= $bank['id']; ?>"><?= $bank['name']; ?> </option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-xs-12 col-md-12 changing_info">
				<p class="monobank monobank_info">
					Для надання доступу потрібно пройти авторизацію у особистову кабінеті
					<a target="_blank" href="https://api.monobank.ua/">https://api.monobank.ua/</a>
					та отримати токен для персонального використання.
				</p>

				<p class="monobank monobank_cards">

				</p>

				<div class="privat_bank privat_bank_info">
					<b>Отримання коду мерчанта</b>
					<ul>
						<li>Увійдіть в обліковий запис Приват24 для фіз. осіб, використовуючи посилання
							<a href="http://privat24.ua">http://privat24.ua</a>;
						</li>
						<li>Перейдіть в розділ меню «Всі послуги» -> «Бізнес» -> «Мерчант»;</li>
						<li>Прив'яжіть карту для роботи з Мерчантом;</li>
						<li>Вкажіть IP-адресу FINEKO <b>185.68.16.160</b>;</li>
						<li>Відзначте вам необхідні для роботи сервіси;
							<ul>
								<li>Виписка за рахунком мерчанта юрособи</li>
								<li>Виписка за рахунком мерчанта фізособи</li>
								<li>Баланс по рахунку мерчанта фізособи</li>
								<li>Інформація про платежі інтернет-еквайрінгу на адресу мерчанта</li>
								<li>Інформація по картці</li>
							</ul>
						</li>
						<li>Натисніть "Далі"</li>
						<li>Підтвердіть пароль OTP</li>
						<li>Реєстрація закінчена. Мерчант клас ID і Пароль введіть в відповідні поля. Сервіс отримає
							доступ тільки до інформації по платежам і по картці. Він не отримає доступ до створення
							платежів
						</li>
					</ul>
				</div>
			</div>

			<div class="col-xs-12 col-md-12 actual_balance">
				<label for="token">Актуальний баланс</label>
				<div class="input-group">

					<input type="number" class="form-control" id="actual_balance"
						   name="checkout" placeholder="сума"
						   required="">
					<select class="form-control" id="card_currency"
							name="currency"
							required="">
						<option value="UAH">₴</option>
						<option value="USD">$</option>
						<option value="EUR">€</option>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-md-12 privat_bank">

				<label for="token">Мерчант ІД</label>
				<input type="text" class="privat_bank form-control monobank_card_id privat_bank_merchant_id"
					   placeholder=""
					   value=""
					   name="merchant_id" required=""/>
			</div>
			<div class="col-xs-12 col-md-12 monobank privat_bank">
				<label class="monobank" for="token">Токен</label>
				<label class="privat_bank" for="token">Пароль</label>
				<input type="text" class="form-control monobank privat_bank monobank_token privat_bank_code"
					   placeholder=""
					   value=""
					   name="merchant_code" required=""/>
			</div>

			<div class="col-xs-12 col-md-12">
				<label class="radio_label">
					<input type="checkbox" class="form-control" name="is_shown"
						   required="" value="card">
					Відображати
				</label>
			</div>

			<div class="col-xs-12 col-md-12">
				<label class="radio_label">
					<input type="checkbox" class="form-control" name="is_virtual"
						   required="" value="card">
					Віртуальний
				</label>
			</div>

			<div class="col-xs-12 col-md-12">
				<a class="btn btn-info add_wallet" href="#">Додати</a>
			</div>
		</form>
	</div>
</div>

