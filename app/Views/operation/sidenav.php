<div id="operation_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>
	<h4 class="text-center title">Додати операцію</h4>
	<div class="operation_sidenav_btns">
		<a class="btn btn-info btn-dark" id="operation_sidenav_open_income" href="#income">Прихід</a>
		<a class="btn btn-info" id="operation_sidenav_open_expense" href="#expense">Витрата</a>
		<a class="btn btn-info" id="operation_sidenav_open_transfer" href="#transfer">Переміщення</a>
	</div>
	<div class="operation_panels">
		<div id="panel_income" class="panel">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="account_id" value="<?= $account_id; ?>"/>
				<input type="hidden" name="operation_type_id" value="1"/>
				<div class="row">
					<div class="col-xs-12 col-md-12 element">

						<label for="contractor_type_existing">
							Клієнт</label>
						<select class="form-control" required="" name="contractor_id">
							<option value=""></option>
							<?php if (!empty($sidenav_clients)): ?>
								<?php foreach ($sidenav_clients as $sidenav_client): ?>
									<option
										<?= !empty($sidenav_client['is_default']) ? 'selected' : ''; ?>
										value="<?= $sidenav_client['id']; ?>"><?= $sidenav_client['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-xs-12 col-md-12 element">
						<label for="username">В гаманець сума</label>
						<div class="input-group">
							<select class="form-control" required="" name="wallet_id">
								<?php if (!empty($sidenav_user_wallets)): ?>
									<?php foreach ($sidenav_user_wallets as $user_wallet): ?>
										<option
											<?= !empty($user_wallet['is_default']) ? 'selected' : ''; ?>
											data-user_id="<?= $user_wallet['user_id']; ?>"
											value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<input type="number" class="form-control" name="amount" placeholder="сума*"
								   required="">
							<!--							<select class="form-control" id="currency" name="currency" required="">-->
							<!--								<option value="UAH">₴</option>-->
							<!--								<option value="USD">$</option>-->
							<!--								<option value="EUR">€</option>-->
							<!--							</select>-->
						</div>
					</div>
					<div class="col-xs-12 col-md-12 element">
						<label for="username">Стаття доходів</label>
						<!--						--><?php //var_dump($sidenav_articles['income']);  ?>
						<select class="form-control" id="sidenav_income_article_id" name="article_id" required="">
							<option value="">Нерозподілені доходи</option>
							<?php if (!empty($sidenav_articles['income'])): ?>
								<?php foreach ($sidenav_articles['income'] as $income_item): ?>
									<optgroup label="<?= $income_item['item']; ?>">
										<?php if (!empty($income_item['children'])): ?>
											<?php foreach ($income_item['children'] as $article_1): ?>
												<option
													value="<?= $article_1['id']; ?>"><?= $article_1['item']; ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<option
												value="<?= $income_item['id']; ?>"><?= $income_item['item']; ?></option>
										<?php endif; ?>
									</optgroup>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-xs-12 col-md-12 element">
						<label class="past_plan" for="income_real">
							<input type="radio" class="form-control real_or_plan" id="income_real"
								   name="time_type"
								   required="" value="real" checked>
							Здійснена операція
						</label>
						<label class="past_plan" for="income_plan">
							<input type="radio" class="form-control real_or_plan" id="income_plan"
								   name="time_type"
								   required="" value="plan">
							Планована операція
						</label>
						<label class="past_plan" for="income_template">
							<input type="radio" class="form-control real_or_plan" id="income_template"
								   name="time_type"
								   required="" value="template">
							Шаблон
						</label>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_real_operations">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time()); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="date_for">Планована дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("Y-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   value="<?= date("Y-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   name="planned_on" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Початкова дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time()); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="repeat_start_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Період</label>
						<select class="form-control" name="repeat_period" required="">
							<option value="month">Щомісяця</option>
							<option value="day">Щодня</option>
							<option value="week">Щотижня</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="income_has_end_date">
							<input type="checkbox" name="has_end_date" id="income_has_end_date" value="1"/>
							Є дата завершення
						</label>
						<input type="date" class="form-control repeat_end_date" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   name="repeat_end_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations fields_for_template_operations">
						<label for="income_notify">
							<input type="checkbox" name="notify" id="income_notify" value="1"/>
							Нагадати в Телеграм
						</label>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="income_is_shown">
							<input type="checkbox" name="is_shown" id="income_is_shown"
								   value="1"
								   checked/>
							Відображати
						</label>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="amount">Вірогідність (<span class="probability_number">100</span> %)</label>

						<input type="range" class="form-control probability" name="probability" value="100" min="0"
							   max="100" step="10" data-highlight="true">
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for="project">Відноситься до проекту</label>
						<select class="form-control" id="sidenav_income_project" required="" name="project_id">
							<option value=""></option>
							<?php if (!empty($projects)): ?>
								<?php foreach ($projects as $project): ?>
									<option value="<?= $project['id']; ?>"><?= $project['name']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_real_operations">
						<label for="credit"><input type="checkbox" name="credit" id="credit"/>Кредит</label>
					</div>

					<div class="credit_block">
						<div class="col-xs-12 col-md-12 element">
							<label for="username">Процент</label>
							<div class="input-group">

								<input type="number" class="form-control" id="сredit_commision_amount"
									   name="сredit_commision_amount" placeholder="сума"
									   required="">
								<select class="form-control" id="сredit_commision_currency"
										name="сredit_commision_currency"
										required="">
									<option value="UAH">₴</option>
									<option value="USD">$</option>
									<option value="EUR">€</option>
								</select>
							</div>
						</div>

						<div class="col-xs-12 col-md-12 element">
							<label for="username">Наступна дата виплати</label>
							<div class="input-group">

								<input type="date" class="form-control" id="date" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 30); ?>"
									   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 30); ?>"
									   name="сredit_payment_date" required=""/>
							</div>
						</div>

					</div>
					<div class="col-xs-12 col-md-12 element">
						<label for="data">Коментар*</label>
						<textarea style="height: 100px" class="form-control" name="comment"
								  placeholder="" required=""></textarea>
					</div>

					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_income" type="submit">Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
		<div id="panel_expense" class="panel">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="account_id" value="<?= $account_id; ?>"/>
				<input type="hidden" name="operation_type_id" value="2"/>
				<div class="row">
					<div class="col-xs-12 col-md-12 element">
						<label for="date_for">З гаманця </label>
						<div class="input-group">
							<select class="form-control" required="" name="wallet_id">
								<?php if (!empty($sidenav_user_wallets)): ?>
									<?php foreach ($sidenav_user_wallets as $user_wallet): ?>
										<option
											<?= !empty($user_wallet['is_default']) ? 'selected' : ''; ?>
											data-user_id="<?= $user_wallet['user_id']; ?>"
											value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>

							<input type="number" class="form-control" name="amount" placeholder="сума*"
								   required="">
							<!--							<select-->
							<!--								data-currency_rate="-->
							<? //= !empty($currency_rate) ? htmlspecialchars(json_encode($currency_rate)) : ''; ?><!--"-->
							<!--								class="form-control" name="currency"-->
							<!--								required="">-->
							<!--								<option value="UAH">₴</option>-->
							<!--								<option value="USD">$</option>-->
							<!--								<option value="EUR">€</option>-->
							<!--							</select>-->
						</div>
					</div>

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

					<div class="col-xs-12 col-md-12 element">
						<label for="username">Стаття розходів</label>
						<select class="form-control" id="expenses" name="article_id" required="">
							<option value="">Нерозподілені розходи</option>
							<?php if (!empty($expenses)): ?>
								<?php foreach ($expenses as $expense_item): ?>
									<optgroup label="<?= $expense_item['name']; ?>">
										<?php if (!empty($expense_item['children'])): ?>
											<?php foreach ($expense_item['children'] as $article_1): ?>
												<option
													value="<?= $article_1['id']; ?>"><?= $article_1['name']; ?></option>
											<?php endforeach; ?>
										<?php else: ?>
											<option
												value="<?= $expense_item['id']; ?>"><?= $expense_item['name']; ?></option>

										<?php endif; ?>
									</optgroup>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label class="past_plan" for="expense_real">
							<input type="radio" class="form-control real_or_plan" id="expense_real"
								   name="time_type"
								   required="" value="real" checked>
							Здійснена операція
						</label>
						<label class="past_plan" for="expense_plan">
							<input type="radio" class="form-control real_or_plan" id="expense_plan"
								   name="time_type"
								   required="" value="plan">
							Планована операція
						</label>
						<label class="past_plan" for="expense_template">
							<input type="radio" class="form-control real_or_plan" id="expense_template"
								   name="time_type"
								   required="" value="template">
							Шаблон
						</label>
					</div>
					<div class="col-xs-12 col-md-12 element fields_for_real_operations">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 30); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="date_for">Планована дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   name="planned_on" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Початкова дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time()); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="repeat_start_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Період</label>
						<select class="form-control" name="repeat_period" required="">
							<option value="month">Щомісяця</option>
							<option value="day">Щодня</option>
							<option value="week">Щотижня</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="expense_has_end_date">
							<input type="checkbox" name="has_end_date" id="expense_has_end_date" value="1"/>
							Є дата завершення
						</label>
						<input type="date" class="form-control repeat_end_date" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   name="repeat_end_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations fields_for_template_operations">
						<label for="expense_notify">
							<input type="checkbox" name="notify" id="expense_notify" value="1"/>
							Нагадати в Телеграм</label>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="expense_is_shown">
							<input type="checkbox" name="is_shown" id="expense_is_shown" value="1" checked/>
							Відображати
						</label>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="amount">Вірогідність (<span class="probability_number">100</span> %)</label>
						<input type="range" class="form-control probability" name="probability" value="100" min="0"
							   max="100" step="10" data-highlight="true">
					</div>

					<div class="col-xs-12 col-md-12 element">
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

					<div class="col-xs-12 col-md-12 element">
						<label for="to_app">Відноситься до заявки</label>
						<select class="form-control" required="" name="app_id">
							<option value=""></option>
							<?php if (!empty($applications)): ?>
								<?php foreach ($applications as $application): ?>
									<option locked="locked" value="<?= $application['id']; ?>">
										#<?= $application['id']; ?>
										. <?= $application['product']; ?>
										- <?= $application['amount']; ?> <?= $application['currency']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>


					<div class="col-xs-12 col-md-12 element">
						<label for="data">Коментар*</label>
						<textarea style="height: 100px" class="form-control" name="comment"
								  placeholder="" required=""></textarea>
					</div>

				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12 element">
						<label for="storage_purchase_show">
							<input type="checkbox" id="storage_purchase_show"
								   name="storage_purchase_show"/>Закупка на
							склад</label>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_expense" type="submit">Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
		<div id="panel_transfer" class="panel">
			<form class="needs-validation" id="test" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="account_id" value="<?= $account_id; ?>"/>
				<input type="hidden" name="operation_type_id" value="3"/>
				<div class="row">
					<div class="col-xs-12 col-md-12 element">
						<label for="date_for">З гаманця</label>
						<div class="input-group">
							<select class="form-control" required="" name="wallet_id">
								<?php if (!empty($sidenav_user_wallets)): ?>
									<?php foreach ($sidenav_user_wallets as $user_wallet): ?>
										<option data_user_id="<?= $user_wallet['user_id']; ?>"
												<?= !empty($user_wallet['is_default']) ? 'selected' : ''; ?>
												value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<input type="number" class="form-control" name="amount" placeholder="сума*"
								   required="">

							<!--							<select class="form-control" id="currency" name="currency" required="">-->
							<!--								<option value="UAH">₴</option>-->
							<!--								<option value="USD">$</option>-->
							<!--								<option value="EUR">€</option>-->
							<!--							</select>-->
						</div>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for="date_for">Співробітнику</label>
						<!--						<div class="input-group">-->
						<select class="form-control" required="" name="user_2_id">
							<option value="<?= $user->id; ?>">Самому собі</option>
							<?php if (!empty($users)): ?>
								<?php foreach ($users as $user): ?>
									<option
										value="<?= $user['id']; ?>"><?= $user['name']; ?> <?= $user['surname']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<?php if (!empty($sidenav_user_wallets) && count($sidenav_user_wallets) > 1) : ?>
						<div class="col-xs-12 col-md-12 element">
							<label for="date_for">в гаманець</label>
							<select class="form-control" required="" name="wallet_2_id">
								<?php foreach ($sidenav_user_wallets as $user_wallet): ?>
									<!--									--><?php //if (empty($user_wallet['is_default'])): ?>
									<option
										data-user_id="<?= $user_wallet['user_id']; ?>"
										value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
									<!--									--><?php //endif; ?>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="col-xs-12 col-md-12 element">
							<label for="">Курс і сума</label>
							<div class="input-group">
								<input type="number" class="form-control" name="rate" placeholder="" value="1"
									   required="">
								<input type="number" class="form-control" name="amount2" placeholder="сума"
									   required="">
							</div>
						</div>
					<?php else: ?>
						<div class="col-xs-12 col-md-12 element">
							<label class="notification_label">В вас тільки один гаманець. Операція переміщення
								недоступна. Створити ще один
								гаманець можна <a href="/wallet" target="_blank">тут</a></label>
						</div>
					<?php endif; ?>
					<div class="col-xs-12 col-md-12 element">
						<label class="past_plan" for="transfer_real">
							<input type="radio" class="form-control real_or_plan" id="transfer_real"
								   name="time_type"
								   required="" value="real" checked>
							Здійснена операція
						</label>
						<label class="past_plan" for="transfer_plan">
							<input type="radio" class="form-control real_or_plan" id="transfer_plan"
								   name="time_type"
								   required="" value="plan">
							Планована операція
						</label>
						<label class="past_plan" for="transfer_template">
							<input type="radio" class="form-control real_or_plan" id="transfer_template"
								   name="time_type"
								   required="" value="template">
							Шаблон
						</label>

					</div>

					<div class="col-xs-12 col-md-12 element fields_for_real_operations">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time()); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_plan_operations">
						<label for="date_for">Планована дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 7); ?>"
							   name="planned_on" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Початкова дата</label>
						<input type="date" class="form-control" placeholder="" data-default="<?= date("yy-m-d", time()); ?>"
							   value="<?= date("yy-m-d", time()); ?>"
							   name="repeat_start_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="date_for">Період</label>
						<select class="form-control" name="repeat_period" required="">
							<option value="month">Щомісяця</option>
							<option value="day">Щодня</option>
							<option value="week">Щотижня</option>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element fields_for_template_operations">
						<label for="expense_has_end_date">
							<input type="checkbox" name="has_end_date" id="expense_has_end_date" value="1"/>
							Є дата завершення
						</label>
						<input type="date" class="form-control repeat_end_date" placeholder="" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 365); ?>"
							   name="repeat_end_date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12 element">
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

					<div class="col-xs-12 col-md-12 element">
						<label for="project">Відноситься до заявки</label>
						<select class="form-control" id="app+_id" required="" name="app_id">
							<option value=""></option>
							<?php if (!empty($applications)): ?>
								<?php foreach ($applications as $application): ?>
									<option value="<?= $application['id']; ?>">#<?= $application['id']; ?>
										. <?= $application['product']; ?>
										- <?= $application['amount']; ?> <?= $application['currency']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-xs-12 col-md-12 element">
						<label for="data">Коментар*</label>
						<textarea style="height: 100px" class="form-control" id="comment" name="comment"
								  placeholder="" required=""></textarea>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_transfer" type="submit">Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
