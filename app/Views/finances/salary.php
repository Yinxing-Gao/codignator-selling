<div id="leads_list">
	<input type="hidden" id="api_key" value="<?= $account->api_key; ?>"/>
	<h2 class="text-center">Зарплати
		<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction">
			<img src="../../../icons/bootstrap/play.svg"/>
		</a>
	</h2>

	<div class="row">
		<div class="col-md-10 ">
			<div class="doublescroll">
				<table class="table table-striped tablesorter">
					<thead class="thead-dark">
					<tr>
						<th>Назва</th>
						<th>Стаття</th>
						<th>Сума</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-2">
			<h4>Додати</h4>
			<form>
				<input type="hidden" class="form-control" name="account_id"
					   value="<?= $account_id; ?>">

				<input type="hidden" class="form-control" name="author_id"
					   value="<?= $user_id; ?>">
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
								<img src="../../../icons/bootstrap/phone.svg" class="phone"/>
							</div>
							<div class="contact" data-type="telegram" data-title="додати Telegram">
								<img src="../../../icons/bootstrap/phone-fill.svg" class="telegram"/>
							</div>
							<div class="contact" data-type="fb" data-title="додати фб">
								<img src="../../../icons/bootstrap/arrow-up-right.svg" class="fb"/>
							</div>
							<div class="contact" data-type="other" data-title="додати інший контакт">
								<img src="../../../icons/bootstrap/arrow-up-square.svg" class="other"/>
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
							Джерело</label>
						<select class="form-control" name="source_id" id="lead_source" required="">
							<!--							<option value="0">Лендінг</option>-->
						</select>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="">
							Кваліфікація</label>
						<select class="form-control" name="qualification" required="">
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
						</select>
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
</div>

