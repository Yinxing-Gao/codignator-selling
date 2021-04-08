<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>
<div class="container" id="container_operation_add_expenses">
	<div class="py-5 text-center">
		<!--		<img class="d-block mx-auto mb-4"-->
		<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
		<!--			 width="300">-->
		<h2>Операція (витрати) <a href="https://www.youtube.com/embed/yXpe4N81blo" class="play_video_instruction"><img
					src="../../img/yt.png"/></a>
			<a href="/rules/page/operation_add_expense" class="rules_btn">
				<img src="../../img/rules.jpg"/>
			</a>
		</h2>
		<div class="text-center">
			<a href="/operation/add_expenses"> витрати</a>
			<a href="/operation/add_income"> приходи</a>
			<a href="/operation/add_transfer"> переміщення</a>
			<!--			<p style="color:red;font-size:16px;">На сторінці проводяться технічні роботи, зайдіть, будь ласка, пізніше</p>-->

		</div>
	</div>

	<div class="row">

		<div class="col-md-12">
			<form class="needs-validation" novalidate="">
				<input type="hidden" name="user_id" value="<?= $user->id; ?>">
				<div class="row">
					<div class="col-md-2">
						<label for="date_for">З гаманця</label>
						<select class="form-control" id="wallet_1_id" required="" name="wallet_1_id">
							<option value=""></option>
							<?php if (!empty($user_wallets)): ?>
								<?php foreach ($user_wallets as $user_wallet): ?>
									<option data_user_id="<?= $user_wallet['user_id']; ?>"
											value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-2">
						<label for="username">Сума</label>
						<div class="input-group">

							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"
								   required="">
						</div>
					</div>

					<div class="col-md-2">
						<label for="username">Валюта</label>
						<div class="input-group">
							<select
								data-currency_rate="<?= !empty($currency_rate) ? htmlspecialchars(json_encode($currency_rate)) : ''; ?>"
								class="form-control" id="currency" name="currency"
								required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
							<input type="hidden" name="total_uah" value="0"/>
						</div>
					</div>

					<div class="col-md-4">

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

					<div class="col-md-2">
						<label for="date">Дата</label>
						<input type="date" class="form-control" id="date" placeholder=""
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-3">
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

					<div class="col-md-3">
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

					<div class="col-md-4">
						<label for="username">Стаття розходів</label>
						<select class="form-control" id="expenses" name="article_id" required="">
							<!--							<option value="">Нерозподілені розходи</option>-->
							<!--							--><?php //if (!empty($expenses)): ?>
							<!--								--><?php //foreach ($expenses as $expense_item): ?>
							<!--									<option value="-->
							<? //= $expense_item['id']; ?><!--">--><? //= $expense_item['item']; ?><!--</option>-->
							<!--								--><?php //endforeach; ?>
							<!--							--><?php //endif; ?>
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

					<div class="col-md-2">
						<label for="is_planned"><input type="checkbox" id="is_planned"/>Запланувати на:</label>

						<input type="date" class="form-control" id="plan_date" placeholder=""
							   value=""  name="plan_date" required="">
					</div>

					<div class="col-md-12">
						<label for="data">Коментар</label>
						<textarea style="height: 100px" class="form-control" id="comment" name="comment"
								  placeholder="" required=""></textarea>
					</div>

				</div>
				<div class="row">
					<div class="col-md-12">
						<label for="storage_purchase_show"><input type="checkbox" id="storage_purchase_show"
																  name="storage_purchase_show"/>Закупка на
							склад</label>
					</div>
				</div>
				<div class="row storage_purchase" style="display: none">
					<div class="col-md-7">
						<div class="row">
							<div class="col-md-7">
							</div>
							<div class="col-md-5">
								<label for="storage">Склад</label>
								<select class="form-control" id="storage" name="storage_id">
									<option value=""></option>
									<?php if (!empty($storages)): ?>
										<?php foreach ($storages as $storage): ?>
											<option
												value="<?= $storage['id']; ?>"><?= $storage['name']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</div>
						</div>
						<div class="row storage_purchase_table_block">
							<table class="table tablesorter" id="storage_purchase_table">
								<thead class="thead-dark">
								<tr>
									<th scope="col">Найменування</th>
									<th scope="col">Ціна</th>
									<th scope="col">Кількість</th>
									<th scope="col">Одиниця</th>
									<th scope="col">Сума</th>
									<th scope="col"></th>
								</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-5">
						<label for="names">Найменування</label>
						<div class="storage_names_for_purchase_block">
							<?php if (!empty($names)): ?>
								<?php foreach ($names as $name): ?>
									<span class="storage_names_for_purchase" data_id="<?= $name['id']; ?>"
										  data_price="<?= $name['buy_price']; ?>" data_amount="<?= $name['amount']; ?>"
										  data_unit="<?= $name['unit']; ?>">
											<?= $name['name']; ?>
									</span>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_expense" type="submit">Відправити
						</button>
					</div>
				</div>
		</div>
	</div>

</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
