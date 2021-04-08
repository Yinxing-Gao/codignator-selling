<input type="hidden" id="account_id" value="<?= $account_id; ?>"/>
<input type="hidden" id="user_id" value="<?= $user_id; ?>"/>
<?php echo $this->include('templates/sidenav'); ?>
<header>
	<nav class="navbar">
		<div class="container">
			<div class="row">
                <div class="col-md-3 col-6" id="logo-menu">
                    <a href="/"><img class="logo_new" src="<?= base_url() ?>/img/logo_.png"/></a>
                    <div class="open_desktop_sidenav" data-title="Головне меню">
                        <img src="../../../icons/fineko/menu_button.svg"/>
                    </div>
                </div>
				<div class="col-md-5 col-3 window_width">
					<!--					<button class="hamburger hamburger--squeeze open_desktop_sidenav" type="button">-->
					<!--						  <span class="hamburger-box">-->
					<!--							<span class="hamburger-inner"></span>-->
					<!--						  </span>-->
					<!--					</button>-->

				</div>
				<div class="col-md-3 col-xs-2 col-1 coltop_menu_div text-right">
					<div class="open_calculator_popup" data-title="Калькулятор">
						<img src="<?= $base_url; ?>/icons/fineko/calculator.svg" alt="open calculator popup"/>
					</div>
					<div class="open_operation_sidenav" data-title="Операції">
						<img src="<?= $base_url; ?>/icons/fineko/operations.svg" alt="open operation sidenav"/>
					</div>
					<div class="open_accrual_sidenav" data-title="Начислення">
						<img src="<?= $base_url; ?>/icons/fineko/accrual.svg" alt="open accrual sidenav"/>
					</div>
					<div class="open_project_sidenav" data-title="Проекти">
						<img src="<?= $base_url; ?>/icons/fineko/projects.svg" alt="open project sidenav"/>
					</div>
					<div class="open_task_sidenav" data-title="Задачі">
						<img src="<?= $base_url; ?>/icons/fineko/tasks.svg" alt="open tasks sidenav"/>
					</div>
				</div>
				<div class="col-md-1 col-xs-3 col-2 login_div" data-login_time="<?= $user->login_time; ?>">
					<!--<span><?/*= lang_('Menu.hello', $locale); */?>, <?/*= $user->name; */?>!</span>-->
					<span id="logout-button"><a href="/user/logout">Вийти</a></span>
				</div>
			</div>
		</div>
	</nav>
</header>

<?php
echo $this->include('operation/sidenav');
echo $this->include('projects/sidenav');
echo $this->include('accruals/sidenav');
echo $this->include('templates/calculator');
echo $this->include('templates/chat');
echo $this->include('tasks/sidenav');
?>

<div class="container main_container">
	<input type="hidden" id="api_key" value="<?= $account->api_key; ?>"/>
	<?php if (!empty($notifications)): ?>
		<?php foreach ($notifications as $notification): ?>
			<span class="alert alert-danger header_notification"><?= $notification['text']; ?> </span>
		<?php endforeach; ?>
	<?php endif; ?>

