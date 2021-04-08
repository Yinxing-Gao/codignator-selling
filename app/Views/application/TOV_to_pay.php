<?php

// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал


?>
<div id="application_list">
	<div id="fixed_panel">
		<div id="numbers">

		</div>
	</div>
	<div class="text-center">
		<h2>Заявки до оплати<br/>(ТОВ) <a href="https://www.youtube.com/embed/nBXYcSUBxI8"
										  class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>
		<input type="hidden" name="user_id" value="<?= $user->id; ?>"/>
	</div>
	<div class="action_btns">
		<a class="btn btn-info check_as_payed" href="#"><?= lang_('App.check as payed', $locale); ?></a>
		<a class="btn btn-dark" href="<?= $base_url; ?>/operation/user/15">Виписка</a>
	</div>
	<div id="panel_all" class="panel">
		<div class="row">
			<div class="col-md-12">
				<p class="TOV_message">По полю <strong>номер рахунку</strong> система перевіряє у виписці чи оплачений рахунок чи ні. Якщо
					це поле не заповнене або заповнене не так, як в коментарі платежу - після оплати заявку варто
					відмітити як оплачена</p>
				<table class="table tablesorter">
					<thead class="thead-dark">
					<tr>
						<th scope="col"></th>
						<th scope="col">#</th>
						<th scope="col">Автор</th>
						<th scope="col">Дата</th>
						<th scope="col">Дата на коли</th>
						<th scope="col">Сума</th>
						<th scope="col">Валюта</th>
						<!--						<th scope="col">Тип</th>-->
						<th scope="col">Товар/Послуга</th>
						<th scope="col">Сума в гривні</th>
						<th scope="col">Оплачено</th>
						<th scope="col">Остаток</th>
						<th scope="col">Статус</th>
						<th scope="col">Номер рахунку</th>
						<th scope="col">Рахунки</th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($order_list)): ?>
						<?php foreach ($order_list as $app): ?>
							<?php if ($app['type_id'] == 2): ?>
								<tr class="app_row_cat_<?= $app['category']; ?>" data-app-id="<?= $app['id']; ?>">
									<td><input class="row_checkbox" type="checkbox"/></td>
									<td><?= $app['id']; ?></td>
									<td><?= $app['author']; ?></td>
									<!--								<td>--><? //= $app['department']; ?><!--</td>-->
									<td><?= date('d.m.Y', $app['date']); ?></td>
									<td><?= date('d.m.Y', $app['date_for']); ?></td>
									<td><?= $app['amount']; ?></td>
									<td><?= $currencies[$app['currency']]; ?></td>
									<!--								<td>-->
									<? //= $types[$app['type_id']]; ?><!--</td>-->
									<td><?= $app['product']; ?>
										<span class="director_comment">
										<?php if (!empty($app['director_comment'])): ?>
											<hr/>
											<strong><?= lang_('App.director comment', $locale); ?>:</strong>
											<span><?= $app['director_comment']; ?></span>
										<?php endif; ?>
										</span>
									</td>
									<td><?= $app['total']; ?></td>
									<td>0</td>
									<td class="amount_left_to"><?= $app['total'] - 0; ?></td>
									<td><?= $app['status']; ?></td>
									<td><?= $app['order_names']; ?></td>
									<td>
										<?php if (!empty($app['order_files'])) : ?>
											<?php $files = json_decode(stripslashes($app['order_files'])); ?>
											<?php if (!empty($files)): ?>
												<?php foreach ($files as $file): ?>
													<a href="<?= $file; ?>" class="order_file"
													   target="_blank"><?= substr($file, strripos($file, '/') + 1); ?></a>
												<?php endforeach; ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<!--								<td><a data-app_info="-->
									<? //= $app['id']; ?><!--" class="btn btn-info app_info">Деталі</a></td>-->


								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

