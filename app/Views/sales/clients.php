<div id="sales">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Клієнти</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_client_sidenav" data-title="Додати клієнта">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<!--					<th scope="col">Проект</th>-->
					<th scope="col">Ім'я</th>
					<th scope="col">Загальна сума</th>
					<th scope="col">Каса</th>
					<th scope="col">Продукти</th>
					<th scope="col">Статус</th>
					<th scope="col">Оплачено</th>
					<th scope="col">Очікується</th>
					<th scope="col">Наступна виплата</th>
					<th style="max-width: 300px" scope="col">Коментар</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($current_clients)): ?>

					<?php foreach ($current_clients as $current_client): ?>
						<tr data-current_client_id="<?= $current_client['id']; ?>">
							<td data-label=""><input class="current_client_checkbox" type="checkbox"></td>
							<td data-label="#"><?= $current_client['id']; ?></td>
							<!--							<td data-label="Проект">-->
							<? //= $current_client['name']; ?><!--</td>-->
							<td data-label="Ім'я"><?= $current_client['name']; ?></td>
							<td data-label="Загальна сума">
								<?= $current_client['amount']; ?>
								<?= $currencies[$current_client['currency']]; ?>
							</td>
							<td data-label="Каса">
								<select class="form-control"
										name="application_id" required="">
									<?php if (!empty($income_wallets)): ?>
										<?php foreach ($income_wallets as $income_wallet): ?>
											<option
												<?= $current_client['wallet_id'] == $income_wallet['id'] ? 'selected' : '' ?>
												value="<?= $income_wallet['id']; ?>">
												<?= $income_wallet['name']; ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</td>
							<td data-label="Продукти">
								<?php if (!empty($current_client['products'])): ?>
									<?php foreach ($current_client['products'] as $product): ?>
										<?= $product['name']; ?><br/>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td data-label="Статус"><?= $current_client['status']; ?></td>
							<td data-label="Оплачено">
								<?= $current_client['payed_amount']; ?>
							</td>
							<td data-label="Остаток">
								<?= $current_client['amount_left']; ?>
							</td>
							<td data-label="Наступна виплата"

							</td>
							<td data-label="Коментар" style="max-width: 300px">
								<!--								--><? //= $current_client['comment']; ?>
							</td>
							<td><a href="#" class="current_client_ops"
								   data-current_client_id="<?= $current_client['id']; ?>">Операції</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="client_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="client_sidenav_content sidenav_content">
		<form class="needs-validation">
			<h4 id="title" class="text-center">Додати нового клієнта</h4>
			<!--			<input type="hidden" class="form-control" name="account_id"-->
			<!--				   value="--><? //= $account_id; ?><!--">-->
			<!---->
			<!--			<input type="hidden" class="form-control" name="author_id"-->
			<!--				   value="--><? //= $user_id; ?><!--">-->
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="">
						Ім'я*</label>
					<input class="form-control" type="text" name="name">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="description">
						Контакти</label>
					<div class="contacts_block">
						<div class="contact" data-type="phone" data-title="додати телефон">
							<img src="../../../icons/fineko/phone.svg" class="phone"/>
						</div>
						<div class="contact" data-type="telegram" data-title="додати Telegram">
							<img src="../../../icons/fineko/telegram.svg" class="telegram"/>
						</div>
						<div class="contact" data-type="fb" data-title="додати фб">
							<img src="../../../icons/fineko/fb.svg" class="fb"/>
						</div>
						<div class="contact" data-type="other" data-title="додати інший контакт">
							<img src="../../../icons/fineko/other_contact.svg" class="other"/>
						</div>
					</div>
					<div class="contact_fields_block">

					</div>
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Продукт</label>
					<select class="form-control" id="lead_product" name="product_id" required="">
					</select>
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Вартість продукту</label>
					<div class="input-group">
						<input class="form-control" type="text" name="amount">
						<select class="form-control" id="currency" name="currency" required="">
							<option value="UAH">₴</option>
							<option value="USD">$</option>
							<option value="EUR">€</option>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Документи</label>
					<input class="form-control" type="file" multiple name="documents">
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_lead" type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

