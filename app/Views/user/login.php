<div id="login">
	<div class="container">
		<h2 class="text-center">Вхід</h2>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-3">
				<form class="form-horizontal" role="form" method="POST">
					<div class="form-group">
						<label for="inputEmail3" class=" control-label">
							Логін
							<!--							<a href="/rules" class="rules_btn">-->
							<!--								<img src="-->
							<? //= base_url(); ?><!--/icons/bootstrap/info-circle.svg"/></a>-->
						</label>
						<input type="text" class="form-control" placeholder="Логін" id="login_input"
							   name="login">
					</div>

					<div class="form-group">
						<label for="inputPassword3" class="control-label">Пароль</label>
						<input type="password" class="form-control" placeholder="Пароль" id="password_input"
							   name="password">
					</div>
					<div class="form-group" style="display:none">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="not_attach_ip"> Запам'ятати мене
							</label>
						</div>
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-default btn-sm btn-login form-control">Ввійти</button>
					</div>
					<p>Ще немає акаунту? <a href="/user/registration">Зареєструватися</a></p>
				</form>
			</div>
			<div class="col-md-3">
				<label>Вхід через Google</label>
				<!--				<a class="social_button form-control" href="-->
				<? //= $google_url; ?><!--">Google</a>-->
				<a class="social_button form-control" href="<?= $google_url; ?>">Ввійти через Google</a>

				<img class="bot_pic" src="../../../img/bot.png"/>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>

