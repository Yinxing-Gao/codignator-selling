<?php include_once 'head.php' ?>
<link rel="stylesheet" href="../../css/login.css">
<div class="layer_block">
	<div class="form_block">
		<div class="form">
			<form class="form-horizontal" role="form" method="POST">
				<div class="form-group">
					<div class="form-group">
						<div class="col-sm-10">
							<label for="inputEmail3" class=" control-label">Создание аккунта
								<!--							<a href="/rules" class="rules_btn">-->
								<!--								<img src="-->
								<? //= $base_url; ?><!--/img/rules.jpg"/></a>-->
							</label>
						</div>
						<div class="col-sm-10">
							<label for="inputPassword3" class="control-label"></label>
							<input type="text" class="form-control" placeholder="Название компании" id="login_input"
								   name="company_name">
						</div>
						<div class="col-sm-10">
							<label for="inputPassword3" class="control-label"></label>
							<input type="text" class="form-control" placeholder="Имя" id="login_input" name="login">
						</div>
						<div class="col-sm-10">
							<label for="inputPassword3" class="control-label"></label>
							<input type="text" class="form-control" placeholder="Фамилия" id="login_input"
								   name="login">
						</div>
						<div class="col-sm-10">
							<label for="inputPassword3" class="control-label"></label>
							<input type="password" class="form-control" placeholder="Пароль" id="password_input"
								   name="password">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default btn-sm btn-login">Создать аккаунт</button>
						</div>
					</div>
			</form>
		</div>
	</div>
</div>

<?php include_once 'footer.php' ?>
