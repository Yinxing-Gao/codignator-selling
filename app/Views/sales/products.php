<div id="products">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Продукти</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_product_sidenav" data-title="Додати новий продукт">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Назва</th>
					<th scope="col">Ціна</th>
					<th scope="col">Тип оплати</th>
					<th scope="col">Сума першої оплати (%)</th>
					<th scope="col">Середня тривалість закриття угоди (днів)</th>
					<th scope="col">Середня тривалість робіт (днів)</th>
					<th scope="col">Стаття доходу</th>
					<th scope="col">Коментар</th>
					<th scope="col">Департамент</th>
					<th scope="col">Групи запитань</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($products)): ?>
					<?php foreach ($products as $product): ?>
						<tr data-contract_id="<?= $product['id']; ?>">
							<td data-label="Назва"><?= $product['name']; ?></td>
							<!--														<td>-->
							<? // //= $product['contract_type']; ?><!--</td>-->
							<td data-label="Ціна"><?= $product['price']; ?> <?= $product['currency']; ?></td>
							<td data-label="Тип оплати"><?= !empty($product['payment_type']) ? $payment_types[$product['payment_type']] : ''; ?></td>
							<td data-label="Сума першої оплати (%)"><?= $product['first_payment']; ?> %</td>
							<td data-label="Середня тривалість закриття угоди (днів)"><?= $product['average_sale_time']; ?></td>
							<td data-label="Середня тривалість робіт (днів)"><?= $product['average_project_time']; ?></td>
							<td data-label="Стаття доходу"><?= $product['article_name']; ?></td>
							<td data-label="Коментар"><?= $product['comment']; ?></td>
							<td data-label="Департамент"><?= $product['department_name']; ?></td>
							<td data-label="Групи запитань">
								<?php if (!empty($product['question_groups'])): ?>
									<?php foreach ($product['question_groups'] as $question_group): ?>
										<div class="question_group"><?= $question_group['name']; ?> </div>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td>
								<div class="icon delete_product" data-title="Видалити продукт">
									<img src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="product_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="product_sidenav_content sidenav_content">
		<h4>Додати новий продукт</h4>
		<form class="product_form">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Номер / назва *</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="Ім'я"
						   required="">
				</div>

				<div class="col-xs-12 col-md-12 element">
					<label for="price">Ціна</label>
					<div class="input-group">
						<input type="number" class="form-control" id="price" name="price" placeholder="сума"
							   value="0" required="">
						<select class="form-control" id="currency" name="currency" required="">
							<option value="UAH">₴</option>
							<option value="USD">$</option>
							<option value="EUR">€</option>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label>
						Коментар</label>
					<textarea style="height: 100px" class="form-control" name="comment"></textarea>
				</div>

				<div class="col-xs-12 col-md-12">
					<label>
						Департамент</label>
					<select class="form-control" id="department_id" name="department_id"
							<?= !empty($department_id) ? 'readonly' : ''; ?>>
						<option value=""></option>
						<?php if (!empty($departments)): ?>
							<?php foreach ($departments as $department): ?>
								<option
									<?= (!empty($department_id) && $department_id == $department['id']) ? 'selected' : ''; ?>
									value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="article_id">Стаття доходу<span
							class="article_id_warning">(Перше виберіть департамент)</span></label>
					<select class="form-control" id="article_id" name="article_id" disabled>
						<option value="">Без статті</option>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label>
						Тип</label>
					<select class="form-control" id="type" name="type" <?= !empty($type) ? 'readonly' : ''; ?>>
						<?php if (!empty($types)): ?>
							<?php foreach ($types as $key => $value): ?>
								<option <?= (!empty($type) && $type == $key) ? 'selected' : ''; ?>
										value="<?= $key; ?>"><?= $value; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label>
						Тип оплати</label>
					<select class="form-control" id="payment_type" name="payment_type"
							<?= !empty($payment_type) ? 'readonly' : ''; ?>>
						<?php if (!empty($payment_types)): ?>
							<?php foreach ($payment_types as $key => $value): ?>
								<option <?= (!empty($payment_type) && $payment_type == $key) ? 'selected' : ''; ?>
										value="<?= $key; ?>"><?= $value; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12 element">
					<label for="first_payment">Сума першої оплати (%)</label>
					<input type="number" class="form-control" id="first_payment" name="first_payment"
						   placeholder="Перший платіж"
						   value="0">
				</div>

				<div class="col-xs-12 col-md-12 element">
					<label for="average_sale_time">Середня тривалість закриття угоди (днів)</label>
					<input type="number" class="form-control" id="average_sale_time" name="average_sale_time"
						   placeholder="Середній час продаж"
						   value="0">
				</div>

				<div class="col-xs-12 col-md-12 element">
					<label for="average_project_time">Середня тривалість робіт (днів)</label>
					<input type="number" class="form-control" id="average_project_time" name="average_project_time"
						   placeholder="Середній час проекту"
						   value="0">
				</div>

				<div class="col-xs-12 col-md-12">
					<label>
						Частини</label>
					<select class="form-control" id="has_parts" name="has_parts"
							<?= !empty($has_parts) ? 'readonly' : ''; ?>>
						<?php if (!empty($has_parts_options)): ?>
							<?php foreach ($has_parts_options as $key => $value): ?>
								<option <?= (!empty($has_parts) && $has_parts == $key) ? 'selected' : ''; ?>
										value="<?= $key; ?>"><?= $value; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="question_group_ids">Групи запитань</label>
					<select class="js-example-basic-multiple form-control question_group_ids"
							name="question_group_ids[]"
							multiple="multiple"
							id="question_group_ids">
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="storage_name_id">Назва складу</label>
					<select class="form-control" id="storage_name_id" name="storage_name_id">
						<option value="">Немає</option>
					</select>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_product" data-action="add"
							type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
