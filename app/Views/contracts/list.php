<div id="contract_list">
	<div class="text-center">
		<h2>Договори
			<a href="https://www.youtube.com/embed/zcbBAuG3Xls" class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg"/>
			</a>
		</h2>
	</div>

	<div class="row">

		<div class="col-md-9">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Номер / назва</th>
					<th scope="col">Тип</th>
					<th scope="col">Сума</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Продукт</th>
					<th scope="col">Коментар</th>
					<th scope="col">Дата</th>
					<th scope="col">Дата початку</th>
					<th scope="col">Дата завершення</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($contracts)): ?>
					<?php foreach ($contracts as $contract): ?>
						<tr data-contract_id="<?= $contract['id']; ?>">
							<td><?= $contract['number']; ?></td>
							<td><?= $contract['contract_type']; ?></td>
							<td><?= $contract['amount']; ?></td>
							<td><?= $contract['contractor_name']; ?></td>
							<td>
								<select class="form-control products" name="products" required="" multiple>
									<?php if (!empty($products)): ?>
										<?php foreach ($products as $product): ?>
											<option
												<?= in_array($product['id'], explode(',', $contract['products_id'])) ? 'selected' : '' ?>
												value="<?= $product['id']; ?>"><?= $product['name']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</td>
							<td><?= $contract['comment']; ?></td>
							<td><?= !empty($contract['date']) ? date('d.m.Y', $contract['date']) : '' ?></td>
							<td><?= !empty($contract['start_date']) ? date('d.m.Y', $contract['start_date']) : '' ?></td>
							<td><?= !empty($contract['end_date']) ? date('d.m.Y', $contract['end_date']) : ''; ?></td>
							<td>
								<img class="delete_contract" src="../../../icons/bootstrap/trash.svg"/>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<select class="form-control action_select" disabled>

				<option value="add">Додати</option>

			</select>
			<hr/>
			<form class="contractor_form">
				<input type="hidden" name="account_id"
					   value="<?= $account_id; ?>">

				<div class="row">
					<div class="col-xs-12 col-md-12">
						<label for="name">
							Номер / назва *</label>
						<input type="text" class="form-control" name="number" placeholder="Ім'я"
							   required="">
					</div>

					<div class="col-xs-12 col-md-12">
						<label>
							Тип</label>

						<select class="form-control" name="contract_type" required="">
							<?php if (!empty($contract_types)): ?>
								<?php foreach ($contract_types as $contract_type_id => $contract_type): ?>
									<option value="<?= $contract_type_id; ?>"><?= $contract_type; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for="username">Сума</label>
						<div class="input-group">
							<input type="number" class="form-control" name="amount" placeholder="сума"
								   value="0" required="">
							<select class="form-control" id="currency" name="currency" required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
						</div>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for="username">Діє від і до</label>
						<div class="input-group">
							<input type="date" class="form-control" name="start_date"
								   value="<?= date('Y-m-d'); ?>" required="">
							<input type="date" class="form-control" name="end_date"
								   value="<?= date('Y-m-d', time() + 60 * 60 * 24 * 30); ?>" required="">
						</div>
					</div>

					<div class="col-xs-12 col-md-12">
						<label for="contractor_type_existing">
							Продукти</label>
						<select class="form-control products" id="products" name="products[]" required="" multiple>
							<?php if (!empty($products)): ?>
								<?php foreach ($products as $product): ?>
<!--									<option-->
<!--										--><?//= in_array($product['id'], explode(',', $contract['products_id'])) ? 'selected' : '' ?>
<!--										value="--><?//= $product['id']; ?><!--">--><?//= $product['name']; ?><!--</option>-->
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>


					<div class="col-xs-12 col-md-12">
						<label>
							Коментар</label>
						<textarea style="height: 100px" class="form-control" name="comment"
								  required=""></textarea>
					</div>

					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_contract" data-action="add"
								type="submit">Відправити
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>


