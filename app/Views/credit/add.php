<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>


<div class="container" id="credit_add">
	<div class="text-center">
		<!--		<img class="d-block mx-auto mb-4"-->
		<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
		<!--			 width="300">-->
		<h2>Додавання кредиту
			<a href="#"
			   class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg"/>
			</a>
		</h2>
	</div>

	<div class="row">

		<div class="col-md-12">
			<form class="needs-validation" novalidate="">
				<div class="row">
					<div class="col-md-9">
						<iframe style="margin-top: 35px;" width="100%" height="415"
								src="https://www.youtube.com/embed/usjRk8i3aUo"
								frameborder="0"
								allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
								allowfullscreen></iframe>
						<br/>
						<br/>
						<br/>
						<br/>
						<br/>
					</div>
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-12">
								<label for="lastName">Відповідальний*</label>
								<input type="text" class="form-control" id="author" name="author" placeholder=""
									   value="<?= $username; ?>"
									   required="" <?= !empty($username) ? 'readonly' : '' ?>
									   data-id=" <?= !empty($user) ? $user->id : ''; ?>">
								<input type="hidden" class="form-control" name="responsible_id" placeholder=""
									   value="<?= !empty($user) ? $user->id : ''; ?>"/>
							</div>

							<div class="col-md-12">
								<label for="department">Департамент*</label>
								<select class="form-control" name="department_id">
									<option value=""></option>
									<?php if (!empty($departments)): ?>
										<?php foreach ($departments as $department): ?>
											<option
												<?= (!empty($credit) && $credit->department_id == $department['id']) ? 'selected' : ""; ?>
												value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

							<div class="col-md-12">
								<label for="contractor_type_existing">
									Тип операції*</label>
								<select class="form-control" required="" name="accrual_type">
									<option value="credit">Кредит ( взяття в борг )</option>
									<option value="debit">Інвестиція ( отримання в борг )</option>
								</select>
							</div>

							<div class="col-md-12">
								<label for="contractor_type_existing">
									Контрагент*</label>
								<select class="form-control" required="" name="contractor_id">
									<option value=""></option>
									<?php if (!empty($contractors)): ?>
										<?php foreach ($contractors as $contractor): ?>
											<option
												<?= (!empty($credit) && $credit->contractor_id == $contractor['id']) ? 'selected' : ""; ?>
												value="<?= $contractor['id']; ?>"><?= $contractor['name']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>

							<div class="col-md-12">
								<label for="username">Сума*</label>
								<div class="input-group">

									<input type="number" class="form-control" name="amount"
										   placeholder="сума" value="<?= !empty($credit) ? $credit->amount : ""; ?>"
										   required="">
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
									<!--									<select class="form-control" id="currency" name="currency" required="">-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->currency == 'UAH') ? 'selected' : ""; ?>
									<!--											value="UAH">₴-->
									<!--										</option>-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->currency == 'USD') ? 'selected' : ""; ?>
									<!--											value="USD">$-->
									<!--										</option>-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->currency == 'EUR') ? 'selected' : ""; ?>
									<!--											value="EUR">€-->
									<!--										</option>-->
									<!--									</select>-->
									<!--									<select class="form-control" id="type" name="type_id" required="">-->
									<!--										--><?php //if (!empty($types)): ?>
									<!--											--><?php //foreach ($types as $id => $type): ?>
									<!--												<option-->
									<!--													--><? //= (!empty($credit) && $credit->type == $type) ? 'selected' : ""; ?>
									<!--													value="-->
									<? //= $id; ?><!--">--><? //= $type; ?><!-- </option>-->
									<!--											--><?php //endforeach; ?>
									<!--										--><?php //endif; ?>
									<!--									</select>-->
								</div>
							</div>

							<div class="col-md-12">
								<label for="date_for">Дата віддачі*</label>
								<input type="date" class="form-control" placeholder="" name="end_date"
									   required=""
									   value="<?= !empty($credit) && $credit->end_date ? date('Y-m-d', $credit->end_date) : ""; ?>">
								<div class="credit_return_operation_block">
									<img src="http://app.fineko.space/icons/bootstrap/cash.svg"
										 class="credit_return_operation"/>
								</div>
							</div>

							<!--							<div class="col-md-12">-->
							<!--								<label for="credit_app_id"><input type="checkbox"/>Є заявка на повернення тіла </label>-->
							<!--								<select class="form-control" id="credit_app_id" required="" name="credit_app_id">-->
							<!--									<option value=""></option>-->
							<!--									--><?php //if (!empty($applications)): ?>
							<!--										--><?php //foreach ($applications as $application): ?>
							<!--											<option-->
							<!--												--><? //= (!empty($credit) && $credit->credit_app_id == $application['id']) ? 'selected' : ""; ?>
							<!--												value="-->
							<? //= $application['id']; ?><!--">-->
							<!--												#--><? //= $application['id']; ?>
							<!--												. --><? //= $application['product']; ?>
							<!--												- -->
							<? //= $application['amount']; ?><!-- -->
							<? //= $application['currency']; ?><!-- </option>-->
							<!--										--><?php //endforeach; ?>
							<!--									--><?php //endif; ?>
							<!--								</select>-->
							<!--							</div>-->

							<div class="col-md-12">
								<label for="username">Проценти</label>
								<div class="input-group">
									<input type="number" class="form-control" name="percent"
										   placeholder="процент"
										   required="" value="<?= !empty($credit) ? $credit->percent : ""; ?>">
									<input type="number" class="form-control" id="percent_amount" name="percent_amount"
										   placeholder="сума"
										   required="" value="<?= !empty($credit) ? $credit->percent_amount : ""; ?>">
									<!--									<select class="form-control" id="percent_currency" name="percent_currency"-->
									<!--											required="" value="-->
									<? //= !empty($credit) ? $credit->end_date : ""; ?><!--">-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->percent_currency == 'UAH') ? 'selected' : ""; ?>
									<!--											value="UAH">₴-->
									<!--										</option>-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->percent_currency == 'USD') ? 'selected' : ""; ?>
									<!--											value="USD">$-->
									<!--										</option>-->
									<!--										<option-->
									<!--											--><? //= (!empty($credit) && $credit->percent_currency == 'EUR') ? 'selected' : ""; ?>
									<!--											value="EUR">€-->
									<!--										</option>-->
									<!--									</select>-->
									<!--									<select class="form-control" id="percent_type" name="percent_type_id" required="">-->
									<!--										--><?php //if (!empty($types)): ?>
									<!--											--><?php //foreach ($types as $id => $type): ?>
									<!--												<option-->
									<!--													--><? //= (!empty($credit) && $credit->percent_type_id == $id) ? 'selected' : ""; ?>
									<!--													value="-->
									<? //= $id; ?><!--">--><? //= $type; ?><!-- </option>-->
									<!--											--><?php //endforeach; ?>
									<!--										--><?php //endif; ?>
									<!--									</select>-->
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

								</div>
							</div>

							<div class="col-md-12">
								<label for="percent_date">Дата сплати процентів</label>
								<div class="input-group percent_date_block">
									<input type="date" class="form-control" placeholder=""
										   name="percent_date"
										   value="<?= !empty($credit) && !empty($credit->next_payment_date) ? date('Y-m-d', $credit->next_payment_date) : ""; ?>"
										   required="">
									<select class="form-control" id="percent_period" name="percent_period" required="">
										<option
											<?= (!empty($credit) && $credit->percent_period == 'month') ? 'selected' : ""; ?>
											value="month">щомісяця
										</option>
										<option
											<?= (!empty($credit) && $credit->percent_period == 'week') ? 'selected' : ""; ?>
											value="week">щотижня
										</option>
										<option
											<?= (!empty($credit) && $credit->percent_period == 'day') ? 'selected' : ""; ?>
											value="day">щодня
										</option>
									</select>
								</div>
								<div class="credit_return_operation_block">
									<img src="http://app.fineko.space/icons/bootstrap/cash.svg"
										 class="credit_return_operation"/>
								</div>
							</div>

							<!--							<div class="col-md-3">-->
							<!--								<label for="percent_app_id"><input type="checkbox"/>Є заявка на оплату процентів</label>-->
							<!--								<select class="form-control" id="percent_app_id" required="" name="percent_app_id">-->
							<!--									<option value=""></option>-->
							<!--									--><?php //if (!empty($applications)): ?>
							<!--										--><?php //foreach ($applications as $application): ?>
							<!--											<option-->
							<!--												--><? //= (!empty($credit) && $credit->percent_app_id == $application['id']) ? 'selected' : ""; ?>
							<!--												value="-->
							<? //= $application['id']; ?><!--">-->
							<!--												#--><? //= $application['id']; ?>
							<!--												. --><? //= $application['product']; ?>
							<!--												- -->
							<? //= $application['amount']; ?><!-- -->
							<? //= $application['currency']; ?><!-- </option>-->
							<!--										--><?php //endforeach; ?>
							<!--									--><?php //endif; ?>
							<!--								</select>-->
							<!--							</div>-->


							<div class="col-md-12">
								<label for="data">Коментар</label>
								<textarea class="form-control" name="comment"
										  placeholder="" required="">
								<?= !empty($credit) ? $credit->comment : ""; ?>
						</textarea>
							</div>

							<div class="col-md-12">
								<label for=""></label>
								<button data-action="<?= $action; ?>"
										class="btn btn-primary btn-lg btn-block btn_send_app"
										type="submit">
									Відправити
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

