<div id="project_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="project_sidenav_content">
		<form class="needs-validation">
			<input type="hidden" name="author_id" value="<?php echo $user->id; ?>"/>
			<input type="hidden" name="id" value=""/>
			<h4 id="title" class="text-center">Додати проект</h4>

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="date_for">Назва</label>
					<input class="form-control" placeholder="" name="name" required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="department">Департамент*</label>
					<select class="form-control" id="department" name="department_id"
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

<!--				<div style="display: none" class="col-xs-12 col-md-12">-->
<!--					<label for="lastName">Автор</label>-->
<!--					<input type="text" class="form-control" id="author" name="author" placeholder=""-->
<!--						   value="--><?//= !empty($user) ? $user->name . ' ' . $user->surname : ''; ?><!--"-->
<!--						   required="" --><?//= !empty($user) ? 'readonly' : '' ?>
<!--						   data-id=" --><?//= !empty($user) ? $user->id : ''; ?><!--">-->
<!--					<input type="hidden" class="form-control" name="author_id" placeholder=""-->
<!--						   value="--><?//= !empty($user) ? $user->id : ''; ?><!--"/>-->
<!---->
<!--				</div>-->

				<div class="col-xs-12 col-md-12">
					<label for="start_date">Дата старту</label>
					<input type="date" class="form-control" data-default="<?= date("yy-m-d", time()); ?>" placeholder="" name="start_date"
						   value="<?= date("yy-m-d", time()); ?>" required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="date_for">Дата завершення</label>
					<input type="date" class="form-control" placeholder="" name="end_date" data-default="<?= date("yy-m-d", time() + 60 * 60 * 24 * 3); ?>"
						   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 3); ?>" required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="department">Статус</label>
					<select class="form-control" id="status" name="status">
						<?php if (!empty($statuses)): ?>
							<?php foreach ($statuses as $status_code => $status): ?>
								<option
									value="<?= $status_code; ?>"><?= $status; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="username">Вартість</label>
					<div class="input-group">

						<input type="number" class="form-control" name="contract_amount" placeholder="сума"
							   required="">
						<select class="form-control" id="currency" name="contract_currency" required="">
							<option value="UAH">₴</option>
							<option value="USD">$</option>
							<option value="EUR">€</option>
						</select>
						<select class="form-control" id="type" name="contract_type_id" required="">
							<?php if (!empty($types)): ?>
								<?php foreach ($types as $id => $type): ?>
									<option value="<?= $id; ?>"><?= $type; ?> </option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="date_for">Договір</label>
					<select class="form-control" id="contract_id" name="contract_id" required="">
						<option value="0"></option>
						<?php if (!empty($contracts)): ?>
							<?php foreach ($contracts as $contract): ?>
								<option value="<?= $contract['id']; ?>"><?= $contract['number']; ?> </option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="products">Продукт компанії</label>
					<select class="js-example-basic-multiple form-control products" name="products[]"
							multiple="multiple"
							id="project_sidenav_products" required="">

<!--						--><?php //if (!empty($observers)): ?>
<!--							--><?php //foreach ($observers as $observer): ?>
<!--								<option value="--><?//= $observer['id']; ?><!--">--><?//= $observer['name']; ?><!-- </option>-->
<!--							--><?php //endforeach; ?>
<!--						--><?php //endif; ?>
					</select>
				</div>

<!--				<div class="col-xs-12 col-md-12">-->
<!--					<label for="products">Задіяні склади</label>-->
<!--					<select class="js-example-basic-multiple form-control storages" name="storages_ids[]"-->
<!--							multiple="multiple"-->
<!--							id="project_sidenav_storages" required="">-->
<!---->
<!--						--><?php //if (!empty($storages)): ?>
<!--							--><?php //foreach ($storages as $storage): ?>
<!--								<option-->
<!--									value="--><?//= $storage['id']; ?><!--">--><?//= $storage['name']; ?><!--</option>-->
<!--							--><?php //endforeach; ?>
<!--						--><?php //endif; ?>
<!--					</select>-->
<!--				</div>-->

<!--				<div class="col-xs-12 col-md-12">-->
<!--					<label for="products">Спостерігачі</label>-->
<!--					<select class="js-example-basic-multiple form-control observers" name="observers_ids[]"-->
<!--							multiple="multiple"-->
<!--							id="project_sidenav_observers" required="">-->
<!---->
<!--						--><?php //if (!empty($observers)): ?>
<!--							--><?php //foreach ($observers as $observer): ?>
<!--								<option-->
<!--									value="--><?//= $observer['id']; ?><!--">--><?//= $observer['name']; ?><!-- --><?//= $observer['surname']; ?><!--</option>-->
<!--							--><?php //endforeach; ?>
<!--						--><?php //endif; ?>
<!--					</select>-->
<!--				</div>-->

				<div class="col-xs-12 col-md-12">
					<label for="data">Коментар</label>
					<textarea style="height: 100px" class="form-control" id="comment" name="comment"
							  placeholder="" required=""></textarea>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_project" type="submit">Відправити</button>
				</div>
			</div>
		</form>
	</div>
</div>
