<div id="registration">
	<div class="container">
		<p class="welcome-message alert alert-primary">Ми раді, що ви задумались про правильне управління і планування
			ваших фінансів в
			команії.<br/>Це допоможе вам вийти на новий рівень у вашій компанії, збільшить ваші доході і
			дозволить вам масштабуватися.<br/>А FINEKO стане для
			вас прекрасним другом і помічником</p>

		<h2 class="text-center">Реєстрація</h2>
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-3">
				<form class="form-horizontal" role="form" method="POST">
					<div class="form-group">
						<div class="form-group">
							<!--							<label for="inputEmail3" class="col-sm-2 control-label">Логін</label>-->
							<input type="text" class="form-control" placeholder="Логін*"
								   name="login">
						</div>
						<div class="form-group">
							<!--							<label for="inputEmail3" class="col-sm-2 control-label">email</label>-->
							<input type="text" class="form-control" placeholder="email"
								   name="email">
						</div>
						<div class="form-group">
							<!--							<label for="inputEmail3" class="col-sm-2 control-label">Ім'я</label>-->
							<input type="text" class="form-control" placeholder="Ім'я*"
								   name="name">
						</div>
						<div class="form-group">
							<!--							<label for="inputEmail3" class="col-sm-2 control-label">Прізвище</label>-->
							<input type="text" class="form-control" placeholder="Прізвище*"
								   name="surname">
						</div>
						<div class="form-group">
							<!--							<label class="control-label">Пароль</label>-->
							<input type="password" class="form-control" placeholder="Пароль*"
								   name="password">
						</div>
						<div class="form-group">
							<!--							<label class="control-label">Пароль ще раз</label>-->
							<input type="password" class="form-control" placeholder="Пароль ще раз*"
								   name="password2">
						</div>
						<!--						<div class="form-group">-->
						<!--							<label for="inputPassword3" class="col-sm-2 control-label">Посада</label>-->
						<!--							<input type="position" class="form-control" placeholder="Посада" id="password_input"-->
						<!--								   name="position">-->
						<!--						</div>-->
						<div class="form-group">
							<button type="submit" class="btn btn-default btn-sm btn-reg form-control">
								Зарєструватися
							</button>
						</div>

					</div>
				</form>
			</div>
			<div class="col-md-3">
				<!--				<label>Реєстрація через Google</label>-->
				<a class="social_button form-control" href="<?= $google_url; ?>">Зареєструватися через Google</a>

				<img class="bot_pic" src="../../../img/bot.png"/>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>


