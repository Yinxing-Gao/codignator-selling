<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>


<div class="container" id="container_project_add">
	<form class="needs-validation">
		<input type="hidden" name="user_id" value="<?= $user->id; ?>"/>
		<div class="py-5 text-center">
			<!--		<img class="d-block mx-auto mb-4"-->
			<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
			<!--			 width="300">-->
			<h2>Додати проект<a href="#" class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>

		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="row">
					<div class="col-md-4">
						<label for="department">Департамент</label>
						<select class="form-control" id="department" name="department_id" <?=!empty($department_id) ? 'readonly': ''; ?>>
							<option value=""></option>
							<?php if (!empty($departments)): ?>
								<?php foreach ($departments as $department): ?>
									<option <?=(!empty($department_id) && $department_id == $department['id']) ? 'selected' : ''; ?>
										value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label for="lastName">Автор</label>
						<input type="text" class="form-control" id="author" name="author" placeholder=""
							   value="<?= $username; ?>"
							   required="" <?= !empty($username) ? 'readonly' : '' ?>
							   data-id=" <?= !empty($user) ? $user->id : ''; ?>">
						<input type="hidden" class="form-control" name="author_id" placeholder=""
							   value="<?= !empty($user) ? $user->id : ''; ?>"/>

					</div>
					<div class="col-md-2">
						<label for="start_date">Дата старту</label>
						<input type="date" class="form-control" id="start_date" placeholder="" name="start_date"
							   value="<?= date("yy-m-d", time()); ?>" required="">
					</div>

					<div class="col-md-2">
						<label for="date_for">Дата завершення</label>
						<input type="date" class="form-control" id="end_date" placeholder="" name="end_date"
							   value="<?= date("yy-m-d", time() + 60 * 60 * 24 * 3); ?>" required="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label for="date_for">Назва</label>
						<input class="form-control" placeholder="" name="name" required="">
					</div>
					<div class="col-md-4">
						<label for="username">Вартість</label>
						<div class="input-group">

							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"
								   required="">
							<select class="form-control" id="currency" name="currency" required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
							<select class="form-control" id="type" name="type_id" required="">
								<?php if (!empty($types)): ?>
									<?php foreach ($types as $id => $type): ?>
										<option value="<?= $id; ?>"><?= $type; ?> </option>
									<?php endforeach; ?>
								<?php endif; ?>
								<!--								<option value="на ТОВ">на ТОВ</option>-->
								<!--								<option value="на ФОП">на ФОП</option>-->
								<!--								<option value="готівка">готівка</option>-->
								<!--								<option value="невідомо">невідомо</option>-->
							</select>
						</div>
					</div>

					<div class="col-md-4">
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
					<div class="col-md-12">
						<label for="data">Коментар</label>
						<textarea style="height: 100px" class="form-control" id="comment" name="comment"
								  placeholder="" required=""></textarea>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<label for="project">Продукт компанії</label>
				<div style="height: 255px; overflow: auto;">
					<?php if (!empty($products)): ?>
						<?php foreach ($products as $product): ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="products_id[]"
									   value="<?= $product['id']; ?>">
								<label class="form-check-label" for="defaultCheck1">
									<?= $product['name']; ?>
								</label>
							</div>
							<!--						<option value="--><? //= $product['id']; ?><!--">--><? //= $product['name']; ?><!-- </option>-->
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-12">
				<label for=""></label>
				<button class="btn btn-primary btn-lg btn-block btn_add_project" type="submit">Відправити</button>
			</div>
		</div>
	</form>
</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
