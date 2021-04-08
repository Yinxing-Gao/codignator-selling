<?php
//
//use App\Models;
//
//$contractors = Models\Contractors::get_contractors();
//$user_wallets = Models\Wallets::get_wallets(['where' => ['user_id = ' . $user->id]]);
//$user_applications = Models\Applications::get_user_apps($user->id);
//if (Models\Position::control_finance($user->id)) {
//	$company_wallets = Models\Wallets::get_wallets(['where' => ['type_id = ' . 2]]);
//}
//$user_wallets = Models\Position::finance_manager($user->id) ? Models\Wallets::get_wallets() : array_merge($user_wallets, $company_wallets)
//?>

<div id="accruals_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>
	<h4 id="title" class="text-center">Нарахування</h4>
	<div class="accruals_sidenav_btns">
		<a class="btn btn-info btn-dark" href="#debit">Дебет</a>
		<a class="btn btn-info" href="#credit">Кредит</a>
	</div>
	<div class="accruals_panels">
		<div id="panel_debit" class="panel">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="account_id" value="<?= $account_id; ?>"/>
				<div class="row">
					<div class="col-xs-12 col-md-12">

						<label for="contractor_type_existing">
							Контрагент</label>
						<select class="form-control" id="contractor_id" required="" name="contractor_id">
							<option value=""></option>
							<?php if (!empty($contractors)): ?>
								<?php foreach ($contractors as $contractor): ?>
									<option
										value="<?= $contractor['id']; ?>"><?= $contractor['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-xs-12 col-md-12">
						<label for="username">В гаманець сума</label>
						<div class="input-group">
							<select class="form-control" id="wallet_2_id" required="" name="wallet_id">
								<option value=""></option>
								<?php if (!empty($business_units)): ?>
									<?php foreach ($business_units as $business_unit): ?>
										<option
											value="<?= $business_unit['id']; ?>"><?= $business_unit['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"
								   required="">
							<select class="form-control" id="currency" name="currency" required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
						</div>
					</div>


					<div class="col-xs-12 col-md-12 fields_for_real_operations">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" placeholder=""
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="project"><input type="checkbox" id="to_project"/>Відноситься до проекту</label>
						<select class="form-control" id="project" required="" name="project_id">
							<option value=""></option>
							<?php if (!empty($projects)): ?>
								<?php foreach ($projects as $project): ?>
									<option value="<?= $project['id']; ?>"><?= $project['name']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="data">Коментар</label>
						<textarea style="height: 100px" class="form-control" id="comment" name="comment"
								  placeholder="" required=""></textarea>
					</div>

				</div>


				<div class="row">
					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_income" type="submit">Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
		<div id="panel_credit" class="panel">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<input type="hidden" name="account_id" value="<?= $account_id; ?>"/>
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<label for="date_for">З гаманця </label>
						<div class="input-group">
							<select class="form-control" id="wallet_1_id" required="" name="wallet_1_id">
								<option value=""></option>
								<?php if (!empty($user_wallets)): ?>
									<?php foreach ($user_wallets as $user_wallet): ?>
										<option data_user_id="<?= $user_wallet['user_id']; ?>"
												value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>

							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"
								   required="">
							<select
								data-currency_rate="<?= !empty($currency_rate) ? htmlspecialchars(json_encode($currency_rate)) : ''; ?>"
								class="form-control" id="currency" name="currency"
								required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
						</div>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="contractor_id">
							Контрагент</label>
						<select class="form-control" id="contractor_id" required="" name="contractor_id">
							<option value=""></option>
							<?php if (!empty($contractors)): ?>
								<?php foreach ($contractors as $contractor): ?>
									<option
										value="<?= $contractor['id']; ?>"><?= $contractor['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 fields_for_real_operations">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" placeholder=""
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required=""/>
					</div>

					<div class="col-xs-12 col-md-12">
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

					<div class="col-xs-12 col-md-12">
						<label for="to_app"><input type="checkbox" id="to_app"/>Відноситься до заявки</label>
						<select class="form-control" id="app" required="" name="app_id">
							<option value=""></option>
							<?php if (!empty($applications)): ?>
								<?php foreach ($applications as $application): ?>
									<option value="<?= $application['id']; ?>">
										#<?= $application['id']; ?>
										. <?= $application['product']; ?>
										- <?= $application['amount']; ?> <?= $application['currency']; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12">
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

					<div class="col-xs-12 col-md-12">
						<label for="is_planned"><input type="checkbox" id="is_planned"/>Запланувати на:</label>

						<input type="date" class="form-control" id="plan_date" placeholder=""
							   value="" name="plan_date" required="">
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="data">Коментар</label>
						<textarea style="height: 100px" class="form-control" id="comment" name="comment"
								  placeholder="" required=""></textarea>
					</div>

				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<label for="storage_purchase_show"><input type="checkbox" id="storage_purchase_show"
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
	</div>
</div>
