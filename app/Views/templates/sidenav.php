<?php
//include_once 'menu.php';
//?>
<div id="desktop_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x-white.svg"/>
	</a>
	<div class="logo_block">
		<div class="blue_block"></div>
		<div class="blue_div">
			<img src="<?= base_url(); ?>/img/fineko_bot.png"/>
		</div>
	</div>

	<?php foreach ($menu as $item): ?>
		<? //php if ($item['access'] == 'all' || in_array($item['access'], $access)): ?>
		<li class="first main_menu_item" id="<?= $item['id']; ?>"
			<?= ($item['link'] == substr($_SERVER['PHP_SELF'], 1)) ? 'class="active"' : ''; ?>>
			<a <?= !empty($item['style']) ? 'style="' . $item['style'] . '"' : ''; ?>
			   href="<?= $item['link']; ?>">
				<div class="main_menu_item_div">
					<div class="main_menu_item_div_icon">
						<img class="sidenav_menu_icon" src="<?= base_url() . '/' . $item['icon']; ?>"/>
					</div>
					<div class="main_menu_item_div_name">
						<?= $item['name']; ?>
					</div>
				</div>
			</a>
			<div class="main_menu_item_div_open">
				<img class="svg" src="../../../icons/bootstrap/chevron-compact-down.svg"/>
			</div>
			<?php if (!empty($item['children'])): ?>
				<ul class="menu">
					<?php foreach ($item['children'] as $children_item): ?>
						<? //php if ($children_item['access'] == 'all' || in_array($children_item['access'], $access)): ?>
						<li>
							<a href="<?= $children_item['link']; ?>">
								<div class="main_menu_sub_item_div">
									<div class="main_menu_sub_item_div_icon">
										<img class="sidenav_menu_icon" src="<?= base_url() . '/' . $children_item['icon']; ?>"/>
									</div>
									<div class="main_menu_sub_item_div_name">
										<?= $children_item['name']; ?>
									</div>
								</div>
<!--								<img class="sidenav_menu_icon" src="--><?//= base_url() . '/' . $children_item['icon']; ?><!--"/>-->

							</a>
						</li>
						<? //php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</li>
		<? //php endif; ?>
	<?php endforeach; ?>
	<div class="down_block">
		<div class="blue_block">
		</div>
		<div class="account_info">
			<p>Аккаунт - <strong><?= $account->name; ?></strong></p>
			<p>Тип - <strong><?= $account->type; ?></strong></p>
			<p>Оплачено до - <strong><?= date('d.m.Y', $account->payed_to); ?></strong></p>
		</div>
	</div>
</div>
