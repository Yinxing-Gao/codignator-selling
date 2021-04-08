<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>

<div class="container" id="container_operation_add_transfer">
	<div class="py-5 text-center">
<!--		<img class="d-block mx-auto mb-4"-->
<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
<!--			 width="300">-->
		<h2>Операція (переміщення)
			<a href="https://www.youtube.com/embed/K_QkvNRaGsU" class="play_video_instruction">
				<img src="../../img/yt.png" />
			</a>
			<a href="/rules/page/operation_add_transfer" class="rules_btn">
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
					<div class="col-md-3">
						<label for="date_for">З гаманця</label>
						<select class="form-control" id="wallet_1_id" required="" name="wallet_1_id">
							<option value=""></option>
							<?php if (!empty($user_wallets)): ?>
								<?php foreach ($user_wallets as $user_wallet): ?>
									<option data_user_id="<?= $user_wallet['user_id']; ?>" value="<?= $user_wallet['id']; ?>"><?= $user_wallet['name']; ?></option>
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
					<div class="col-md-1">
						<label for="username">Валюта</label>
						<div class="input-group">
							<select class="form-control" id="currency" name="currency" required="">
								<option value="UAH">₴</option>
								<option value="USD">$</option>
								<option value="EUR">€</option>
							</select>
						</div>
					</div>
<!--					<div class="col-md-1">-->
<!--						<label for="username">Курс&nbsp;валют</label>-->
<!--						<div class="input-group">-->
<!---->
<!--							<input type="number" class="form-control" id="amount" name="amount" placeholder="сума"-->
<!--								   required="">-->
<!--						</div>-->
<!--					</div>-->
					<div class="col-md-3">
						<label for="date_for">Співробітнику</label>
						<select class="form-control" id="user2" required="" name="user2">
							<option value=""></option>
							<?php if (!empty($users)): ?>
								<?php foreach ($users as $user): ?>
									<option
										value="<?= $user['id']; ?>"><?= $user['name']; ?> <?= $user['surname']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-md-3">
						<label for="date_for">В гаманець</label>
						<select class="form-control" id="wallet_2_id" required="" name="wallet_2_id">
							<option value=""></option>
							<?php if (!empty($wallets)): ?>
								<?php foreach ($wallets as $wallet): ?>
									<option data_currency="<?= $wallet['currency']; ?>" data_user_id="<?= $wallet['user_id']; ?>" value="<?= $wallet['id']; ?>"><?= $wallet['name']; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>

					<div class="col-md-2">
						<label for="date_for">Дата</label>
						<input type="date" class="form-control" id="date_for" placeholder=""
							   value="<?= date("yy-m-d", time()); ?>"
							   name="date" required="">
					</div>
					<div class="col-md-5">
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

					<div class="col-md-5">
						<label for="project"><input type="checkbox" id="to_add"/>Відноситься до заявки</label>
						<select class="form-control" id="app+_id" required="" name="app_id">
							<option value=""></option>
							<?php if (!empty($applications)): ?>
								<?php foreach ($applications as $application): ?>
									<option value="<?= $application['id']; ?>">#<?= $application['id']; ?>
										. <?= $application['product']; ?>
										- <?= $application['amount']; ?> <?= $application['currency']; ?> </option>
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


				<div class="row">
					<div class="col-md-12">
						<label for=""></label>
						<button class="btn btn-primary btn-lg btn-block btn_add_transfer" type="submit">Відправити
						</button>
					</div>
				</div>
		</div>
	</div>

</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
