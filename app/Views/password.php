<?php include_once 'head.php' ?>
<link rel="stylesheet" href="../../css/login.css">
<div class="layer_block">
	<div class="form_block">


		<div class="form">
			<form class="form-horizontal" role="form" method="POST">
				<div class="form-group">
					<div class="form-group">
						<label for="inputEmail3" class="col-sm-2 control-label">Логін</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="Логин" id="login_input" name="login">
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" placeholder="Пароль" id="password_input"
								   name="password">
							<input type="hidden" class="form-control"  id="id"
								   name="id" value="<?=$id; ?>">
						</div>
					</div>
					<div class="form-group" style="display:none">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="not_attach_ip"> Зап'ятати мене
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default btn-sm btn-password">Зарєструватися</button>
						</div>
					</div>
			</form>
		</div>
	</div>
</div>

<?php include_once 'footer.php' ?>
