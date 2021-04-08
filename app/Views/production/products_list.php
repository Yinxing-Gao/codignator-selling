<div id="user_list">
	<div class="text-center">
		<h2>Специфікації <a href="#" class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg">
			</a>
		</h2>
	</div>

	<div class="row">

		<div class="col-md-9">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Назва</th>
<!--					<th scope="col">Тип</th>-->
					<th scope="col">Тимчасова</th>

					<th scope="col">Кількість елементів</th>
					<th scope="col">Коментар</th>
<!--					<th scope="col">К-сть позицій</th>-->

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($products)): ?>
					<?php foreach ($products as $product): ?>
						<tr>
							<td><?= $product['id']; ?></td>
							<td><a href="/production/specification/<?= $product['id']; ?>"><?= $product['name']; ?></a></td>
<!--							<td>--><?//= $product['type_id']; ?><!--</td>-->
							<td><input type="checkbox" class="form-control"/> </td>
							<td><?= !empty($spec_items_counts[$product['id']]) ? $spec_items_counts[$product['id']] : 0 ?>
									</td>
							<td></td>
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
							Додати до проекту</label>

						<select class="form-control" name="contract_type" required="">
							<?php if (!empty($contract_types)): ?>
								<?php foreach ($contract_types as $contract_type_id => $contract_type): ?>
									<option value="<?= $contract_type_id; ?>"><?= $contract_type; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-xs-12 col-md-12 element">
						<label for="username">Тимчасова</label>
						<input type="checkbox" class="form-control"/>
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



