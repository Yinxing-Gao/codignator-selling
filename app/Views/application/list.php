<?php

// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал
//var_dump($order_list);
?>

<div id="application_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center"><?= lang_('App.applications', $locale); ?></h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/zKEhwSNGRLY" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_application_sidenav" data-title="Додати заявку">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="static_menu row">
		<div class="col-md-12">
			<div class="type_btns">
				<a class="btn btn-info" href="#all"><?= lang_('App.all', $locale); ?></a>
				<a class="btn btn-info" href="#cash"><?= lang_('App.cash', $locale); ?></a>
				<a class="btn btn-info" href="#tov"><?= lang_('App.cashless', $locale); ?></a>
				<a class="btn btn-secondary"
				   href="/application/index/approved"><?= lang_('App.approved', $locale); ?></a>
				<a class="btn btn-dark" href="/application/index/payed"><?= lang_('App.payed', $locale); ?></a>
			</div>

			<div class="date_filters">
				<div>
					<label for="date_for"><?= lang_('App.from', $locale); ?></label>
					<input type="date" class="form-control" id="date_from" placeholder="" name="date_from"
						   value="<?= !empty($date_from) ? $date_from : date("Y-m-d", strtotime('Last Tuesday')) ?>"
						   required="">
				</div>
				<div>
					<label for="date_for">до</label>
					<input type="date" class="form-control" id="date_to" placeholder="" name="date_from"
						   value="<?= !empty($date_to) ? $date_to : date("Y-m-d", strtotime('Next Tuesday')) ?>"
						   required="">
				</div>
				<a class="btn btn-info" data_url="<?= !empty($url) ? $url : ''; ?>" id="filter_by_date"
				   href="#"><?= lang_('App.by date', $locale); ?></a>
			</div>
		</div>
	</div>
	<?php if (!empty($page_options['search'])): ?>
		<div class="search_menu">
			<label for="username"><?= lang_('App.search', $locale); ?></label>
			<div class="input-group">
				<select class="form-control" id="search_app_id" name="search_app_id">
					<option value=""></option>
					<?php if (!empty($applications_for_search)): ?>
						<?php foreach ($applications_for_search as $app_s): ?>
							<option
								value="<?= $app_s['id']; ?>"><?= $app_s['product']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
				<select class="form-control" id="search_app_keywords" name="search_app_keywords">
					<option value=""></option>
					<?php if (!empty($applications_for_search)): ?>
						<?php foreach ($applications_for_search as $app_s): ?>
							<option
								value="<?= $app_s['id']; ?>"><?= $app_s['product']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
		</div>
	<?php endif; ?>
	<div class="fixed_menu row">
		<div class="col-md-12">
			<div class="fixed_container">
				<div class="action_btns">
					<?= in_array('can_aprove_apps', $access) ? '<a class="btn btn-info approve" href="#">' . lang_('App.approve', $locale) . '</a>' : '' ?>
					<?= in_array('can_transfer_apps', $access) ? '<a class="btn btn-info transfer_to_pay" href="#">' . lang_('App.transfer to pay', $locale) . '</a>' : '' ?>
					<a class="btn btn-info pay" href="#"><?= lang_('App.pay', $locale); ?></a>
					<a class="btn btn-info check_as_payed" href="#"><?= lang_('App.check as payed', $locale); ?></a>
					<?= in_array('can_reject_apps', $access) ? '<a class="btn btn-info reject" href="#">' . lang_('App.reject', $locale) . '</a>' : '' ?>

				</div>
			</div>
		</div>
	</div>

	<div id="panel_all" class="panel row">
		<div class="col-md-12" id="search_table_block"></div>
		<div class="col-md-12" id="app_table_block">
			<table class="table tablesorter" id="app_table">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<!--						<th scope="col"></th>-->
					<th scope="col">#</th>
					<th scope="col"><?= lang_('App.date', $locale); ?></th>
					<th scope="col"><?= lang_('App.author', $locale); ?></th>
					<!--						<th scope="col">-->
					<? //= lang_('App.department', $locale); ?><!--</th>-->

					<th scope="col"><?= lang_('App.date for', $locale); ?></th>
					<th scope="col"><?= lang_('App.amount', $locale); ?></th>
					<!--						<th scope="col">-->
					<? //= lang_('App.currency', $locale); ?><!--</th>-->
					<th scope="col"><?= lang_('App.type', $locale); ?></th>
					<th scope="col"><?= lang_('App.product/service', $locale); ?></th>
					<th scope="col"><?= lang_('App.project', $locale); ?></th>
					<th scope="col"><?= lang_('App.amount in UAH', $locale); ?></th>
					<th scope="col"><?= lang_('App.payed', $locale); ?></th>
					<th scope="col"><?= lang_('App.amount left', $locale); ?></th>
					<th scope="col"><?= lang_('App.status', $locale); ?></th>
					<th scope="col"><?= lang_('App.comment', $locale); ?></th>
					<th scope="col">Дії</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($applications)): ?>
					<?php foreach ($applications as $app): ?>
						<tr class="app_row_cat_<?= $app['category']; ?>" data-app-id="<?= $app['id']; ?>">
							<td data-label=""><input class="row_checkbox" type="checkbox"/></td>
							<!--								<td>-->
							<!--									--><?php //if (empty($app['base_app_id'])): ?>
							<!--										<svg class="bi bi-bell" width="1em" height="1em" viewBox="0 0 16 16"-->
							<!--											 fill="currentColor" xmlns="http://www.w3.org/2000/svg">-->
							<!--											<path d="M8 16a2 2 0 002-2H6a2 2 0 002 2z"></path>-->
							<!--											<path fill-rule="evenodd"-->
							<!--												  d="M8 1.918l-.797.161A4.002 4.002 0 004 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 00-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 111.99 0A5.002 5.002 0 0113 6c0 .88.32 4.2 1.22 6z"-->
							<!--												  clip-rule="evenodd"></path>-->
							<!--										</svg>-->
							<!--									--><?php //else: ?>
							<!--										<svg class="bi bi-arrow-clockwise" width="1em" height="1em" viewBox="0 0 16 16"-->
							<!--											 fill="currentColor" xmlns="http://www.w3.org/2000/svg">-->
							<!--											<path fill-rule="evenodd"-->
							<!--												  d="M3.17 6.706a5 5 0 017.103-3.16.5.5 0 10.454-.892A6 6 0 1013.455 5.5a.5.5 0 00-.91.417 5 5 0 11-9.375.789z"-->
							<!--												  clip-rule="evenodd"></path>-->
							<!--											<path fill-rule="evenodd"-->
							<!--												  d="M8.147.146a.5.5 0 01.707 0l2.5 2.5a.5.5 0 010 .708l-2.5 2.5a.5.5 0 11-.707-.708L10.293 3 8.147.854a.5.5 0 010-.708z"-->
							<!--												  clip-rule="evenodd"></path>-->
							<!--										</svg>-->
							<!--									--><?php //endif; ?>
							<!--								</td>-->

							<td data-label="#"><?= $app['id']; ?></td>
							<td data-label="Дата"><?= date('d.m.Y', $app['date']); ?></td>
							<td data-label="Автор"><?= $app['author_name']; ?> <?= $app['author_surname']; ?></td>
							<!--								<td>--><? //= $app['department']; ?><!--</td>-->

							<td data-label="Дата на коли потрібно"><?= date('d.m.Y', $app['date_for']); ?></td>
							<td data-label="Сума">
								<?= $app['amount']; ?>&nbsp;<?= $currencies[$app['currency']]; ?>
								<!--									<input class="form-control amount_to_pay"/>-->
							</td>
							<!--								<td></td>-->
							<td data-label="Тип"><?= !empty($app['type_id']) ? $types[$app['type_id']] : ''; ?></td>
							<td data-label="Товар/Послуга" class="product_td">
								<?= $app['product']; ?>
							</td>
							<td data-label="Проект">Назва проекту</td>
							<td data-label="Сума в гривні"><?= $app['total']; ?></td>

							<td data-label="Оплачено"><?= !empty($app['payed_amount']) ? (float)$app['payed_amount'] : 0; ?></td>
							<td data-label="Остаток"
								class="amount_left_to"><?= $app['total'] - $app['payed_amount']; ?></td>

							<td data-label="Статус"><?= $app['status_name']; ?></td>
							<td data-label="коментар директора"><?php if (!empty($app['director_comment'])): ?>
									<?= $app['director_comment']; ?>
								<?php endif; ?>
							</td>
							<td>
								<select name="action" class="category_select"
								<option value=""></option>
								<option value="A">Одобрити</option>
								<?php if ($app['type_id'] == 2): ?>
									<option value="A">Передати на оплату бухгалтеру</option>
								<?php elseif ($app['type_id'] == 1): ?>
									<option value="A">Передати під звіт <?= $app['author']; ?></option>
								<?php endif; ?>
								<option value="A">Оплатити</option>
								<option value="A">Оплатити частину</option>
								<option value="A">Позначити як оплачено</option>
								<option value="A">Запланувати</option>
								<option value="A">Відкласти</option>
								<option value="A">Відхилити</option>
								<?php if (in_array('can_write_director_comments', $access)): ?>
									<option value="A">Прокоментувати</option>
								<?php endif; ?>

								</select>
							</td>
							<td>
								<div class="icon app_info" data-app_info="<?= $app['id']; ?>" data-title="Деталі">
									<img src="<?= base_url(); ?>/icons/fineko/info.svg"/>
								</div>
								<?php if ($page_options['can_edit_apps']): ?>
									<a href="/application/edit/<?= $app['id']; ?>">
										<div class="icon edit_application" data-title="Редагувати заявку">
											<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
										</div>
									</a>
								<?php endif; ?>
								<div class="icon delete_application">
									<img src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
	<div id="panel_cash" class="panel row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Автор</th>
					<th scope="col">Департамент</th>
					<th scope="col">Дата</th>
					<th scope="col">Дата на коли</th>
					<th scope="col">Сума</th>
					<th scope="col">Валюта</th>
					<th scope="col">Тип</th>
					<th scope="col">Товар/Послуга</th>
					<!--					<th scope="col">Стаття розходів</th>-->
					<!--					<th scope="col">Ситуація</th>-->
					<!--					<th scope="col">Дані</th>-->
					<!--					<th scope="col">Рішення</th>-->
					<th scope="col">Проект</th>
					<th scope="col">Сума в гривні</th>
					<th scope="col">Оплачено</th>
					<th scope="col">Остаток</th>
					<th scope="col">Статус</th>
					<th scope="col">Дії</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($order_list)): ?>
					<?php foreach ($order_list as $app): ?>
						<?php if ($app['type_id'] == 1): ?>
							<tr class="app_row_cat_<?= $app['category']; ?>" data-app-id="<?= $app['id']; ?>">
								<td data-label=""><input class="row_checkbox" type="checkbox"/></td>
								<td data-label="#"><?= $app['id']; ?></td>
								<td data-label="Автор"><?= $app['author']; ?></td>
								<td data-label="Департамент"><?= $app['department']; ?></td>
								<td data-label="Дата"><?= date('d.m.Y', $app['date']); ?></td>
								<td data-label="На коли потрібно"><?= date('d.m.Y', $app['date_for']); ?></td>
								<td data-label="Сума"><?= $app['amount']; ?><?= $currencies[$app['currency']]; ?></td>
								<td data-label="Тип"><?= $types[$app['type_id']]; ?></td>
								<td data-label="Товар/послуга"><?= $app['product']; ?>
									<?php if (in_array('can_write_director_comments', $access)): ?>
										<img src="<?= $base_url; ?>/img/comment_icon.jpg"
											 class="add_director_comment"/>
									<?php endif; ?>
									<span class="director_comment">
										<?php if (!empty($app['director_comment'])): ?>
											<hr/>
											<strong>Коментар директора:</strong>
											<span><?= $app['director_comment']; ?></span>
										<?php endif; ?>
								</td>
								<td data-label="Проект"></td>
								<td data-label="Сума в гривні"><?= $app['total']; ?></td>

								<td data-label="Оплачено"><?= (float)$app['payed_amount']; ?></td>
								<td data-label="Остаток"
									class="amount_left_to"><?= $app['total'] - $app['payed_amount']; ?></td>
								<td data-label="Статус"><?= $app['status']; ?></td>
								<td><a data-app_info="<?= $app['id']; ?>" class="btn btn-info app_info">Деталі</a>
								</td>
								<td>
									<select name="category" class="category_select">
										<option value=""></option>
										<option value="A" <?= $app['category'] == 'A' ? 'selected' : ''; ?>>A
										</option>
										<option value="B" <?= $app['category'] == 'B' ? 'selected' : ''; ?>>B
										</option>
										<option value="C" <?= $app['category'] == 'C' ? 'selected' : ''; ?>>C
										</option>
									</select>
								</td>
								<td>
									<?php if ($page_options['can_edit_apps']): ?>
										<a href="/application/edit/<?= $app['id']; ?>"><img class="edit_application"
																							src="<?= $base_url; ?>/img/edit.jpg"/></a>
									<?php endif; ?>
									<img class="delete_application" src="<?= $base_url; ?>/img/trash-icon.jpg"/>
								</td>

							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
	<div id="panel_tov" class="panel row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Автор</th>
					<th scope="col">Департамент</th>
					<th scope="col">Дата</th>
					<th scope="col">Дата на коли</th>
					<th scope="col">Сума</th>
					<!--					<th scope="col">Валюта</th>-->
					<th scope="col">Тип</th>
					<th scope="col">Товар/Послуга</th>
					<!--					<th scope="col">Стаття розходів</th>-->
					<!--					<th scope="col">Ситуація</th>-->
					<!--					<th scope="col">Дані</th>-->
					<!--					<th scope="col">Рішення</th>-->
					<th scope="col">Проект</th>
					<th scope="col">Сума в гривні</th>
					<th scope="col">Оплачено</th>
					<th scope="col">Остаток</th>
					<th scope="col">Статус</th>
					<th scope="col"></th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($order_list)): ?>
					<?php foreach ($order_list as $app): ?>
						<?php if ($app['type_id'] == 2): ?>
							<tr class="app_row_cat_<?= $app['category']; ?>" data-app-id="<?= $app['id']; ?>">
								<td data-label=""><input class="row_checkbox" type="checkbox"/></td>
								<td data-label="#"><?= $app['id']; ?></td>
								<td data-label="Автор"><?= $app['author']; ?></td>
								<td data-label="Департамент"><?= $app['department']; ?></td>
								<td data-label="Дата"><?= date('d.m.Y', $app['date']); ?></td>
								<td data-label="Дата на коли потрібні гроші"><?= date('d.m.Y', $app['date_for']); ?></td>
								<td data-label="Сума"><?= $app['amount']; ?><?= $currencies[$app['currency']]; ?></td>
								<td data-label="Тип"><?= $types[$app['type_id']]; ?></td>
								<td data-label="Товар/Послуга"><?= $app['product']; ?>
									<?php if (in_array('can_write_director_comments', $access)): ?>
										<img src="<?= $base_url; ?>/img/comment_icon.jpg"
											 class="add_director_comment"/>
									<?php endif; ?>
									<span class="director_comment">
										<?php if (!empty($app['director_comment'])): ?>
											<hr/>
											<strong>Коментар директора:</strong>
											<span><?= $app['director_comment']; ?></span>
										<?php endif; ?>
								</td>
								<td data-label="Проект"></td>
								<td data-label="Сума в гривні"><?= $app['total']; ?></td>
								<td data-label="Оплачено"><?= (float)$app['payed_amount']; ?></td>
								<td data-label="Остаток"
									class="amount_left_to"><?= $app['total'] - $app['payed_amount']; ?></td>

								<td data-label="Статус"><?= $app['status']; ?></td>
								<td><a data-app_info="<?= $app['id']; ?>" class="btn btn-info app_info">Деталі</a>
								</td>
								<td>
									<select name="category" class="category_select">
										<option value=""></option>
										<option value="A" <?= $app['category'] == 'A' ? 'selected' : ''; ?>>A
										</option>
										<option value="B" <?= $app['category'] == 'B' ? 'selected' : ''; ?>>B
										</option>
										<option value="C" <?= $app['category'] == 'C' ? 'selected' : ''; ?>>C
										</option>
									</select>
								</td>
								<td>
									<?php if ($page_options['can_edit_apps']): ?>
										<a href="/application/edit/<?= $app['id']; ?>"><img class="edit_application"
																							src="../../img/edit.jpg"/></a>
									<?php endif; ?>
									<img class="delete_application" src="<?= $base_url; ?>/img/trash-icon.jpg"/>
								</td>

							</tr>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="application_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="application_sidenav_content sidenav_content">
		<h4>Додати заявку</h4>
		<form class="needs-validation" novalidate="">
			<input type="hidden" name="author_id" value="<?= $user_id; ?>"/>
			<div class="row">
				<div class="col-xs-12 col-md-12 element">
					<label for="contractor_id">
						Поставщик чи підрядник</label>
					<select class="form-control" required="" name="contractor_id">
						<option value=""></option>
						<?php if (!empty($sidenav_contractors)): ?>

							<?php foreach ($sidenav_contractors as $sidenav_contractor): ?>
								<option
									<?= !empty($sidenav_contractors['is_default']) ? 'selected' : ''; ?>
									value="<?= $sidenav_contractor['id']; ?>"><?= $sidenav_contractor['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-md-12">
					<label for="username">Вартість*</label>
					<div class="input-group">

						<input type="number" class="form-control" name="amount"
							   placeholder="сума"
							   required="">
						<select class="form-control" name="currency" required="">
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
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<label for="date_for">Дата на коли потрібно*&nbsp;&nbsp;</label>
					<input type="date" class="form-control" placeholder="" name="date_for"
						   required="">
				</div>
				<!--					<div class="col-md-12">-->
				<!--						<label>-->
				<!--							<input name=fixed" id="fixed"-->
				<!--								   type="checkbox"/>Фіксована</label>-->
				<!--					</div>-->


				<div class="col-md-12">
					<label for="project">Кому</label>
					<select class="form-control" required="" name="authority_id">
						<?php if (!empty($authorities)): ?>
							<?php foreach ($authorities as $department): ?>
								<optgroup label="<?= $department['name']; ?>">
									<?php if (!empty($department['children'])): ?>
										<?php foreach ($department['children'] as $authority): ?>
											<option
												value="<?= $authority['user_id']; ?>"><?= $authority['position_name']; ?>
												- <?= $authority['user_name']; ?> <?= $authority['user_surname']; ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</optgroup>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-md-12">
					<label>
						<input type="radio" checked name="pay_from" class="form-control" value="direct"/>
						<span>Оплатити напряму</span>
					</label>
				</div>

				<div class="col-md-12">
					<label>
						<input type="radio" name="pay_from" class="form-control" value="transfer"/>
						<span>Передати мені на оплату</span>
					</label>
				</div>

				<div class="col-md-12">
					<label for="project">Відноситься до проекту</label>
					<select class="form-control" id="project" required="" name="project_id">
						<option value=""></option>
						<?php if (!empty($projects)): ?>
							<?php foreach ($projects as $project): ?>
								<option value="<?= $project['id']; ?>"><?= $project['name']; ?> </option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-md-12">
					<label for="product">Товар/послуга*</label>
					<input class="form-control" id="product" name="product"
						   placeholder="" required="">
				</div>

				<div class="col-md-12">
					<label for="product">Рахунок</label>
					<input type="file" multiple="multiple" class="form-control"
						   accept=".txt,image/*,application/*,application/x-msexcel">
					<!--						<a href="#" class="upload_files button">Завантажити</a>-->
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
				</div>

				<div class="col-md-12">
					<label for="order_names">Номери рахунків<i class="note">Можна писати кілька через
							кому</i></label>
					<input name="order_names" class="form-control" type="text"/>
				</div>

				<div class="col-md-12">
					<label for="username">Ситуація*
						<input type="" class="form-control" id="situation" name="situation"
							   placeholder="тут коротко треба описати ситуації, результатом якої є потреба у фінансуванні"
							   required="">
				</div>

				<div class="col-md-12">
					<label for="username">Стаття розходів</label>
					<select class="form-control" id="expenses" name="article_id" required="">
						<option value=""></option>
						<?php if (!empty($expenses)): ?>
							<?php foreach ($expenses as $expense_item): ?>
								<option
									value="<?= $expense_item['id']; ?>"><?= $expense_item['item']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-md-12">
					<label for="data">Дані*</label>
					<textarea style="height: 150px" class="form-control" id="data" name="data"
							  placeholder="тут треба МАКСИМАЛЬНО детально описати всі дані, на основі яких прийнято рішення про потребу в фінансуванні. Наприклад, якщо це деталі на апарат, то бажено вказати дату, коли апарат повинен бути готовим, тривалість доставки і т.д.Це дані повинні дати вичерпну відповідь на запитання 'чому це треба купити взагалі?' і 'чому це треба придбати саме зараз?'"
							  required=""></textarea>
				</div>

				<div class="col-md-12">
					<label for="decision">Рішення*</label>
					<input type="" class="form-control" id="decision" name="decision" placeholder=""
						   required="">
				</div>

				<div class="col-md-12">
					<label>
						<input type="checkbox" id="urgently" name="urgently"
							   class="form-control"/>Термінова</label>
				</div>

				<div style="display:none" class="col-md-12">
					<label for="decision"><input name="repeat" id="repeat" type="checkbox"/>Операція буде
						повторюватися</label>
					<select class="form-control" id="repeat_type" name="repeat_type" required="">
						<option value=""></option>
						<option value="day">Щодня</option>
						<option value="week">Щотижня</option>
						<option value="month">Щомісяця</option>
					</select>
				</div>

				<div style="display:none" class="col-md-12">
					<label for="date_for">Дата закінчення</label>
					<input type="date" class="form-control" id="repeat_end_date" placeholder=""
						   name="repeat_end_date"
						   required="">
				</div>

				<div style="display: none">
					<h4 style="text-align: center">Керівник департаменту</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="department_chief" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="department_chief" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>

					<h4 style="text-align: center">Голова рекомендаційної ради</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="finance_chief" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="finance_chief" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>

					<h4 style="text-align: center">Директор</h4>

					<div class="row">
						<div class="col-md-6 text-center">
							<input name="director" type="radio" class="custom-control-input" checked=""
								   required="">
							<label class="custom-control-label" for="credit">Одобрено</label>
						</div>
						<div class="col-md-6 text-center">
							<input name="director" type="radio" class="custom-control-input"
								   required="">
							<label class="custom-control-label" for="debit">Не одобрено</label>
						</div>
						<div class="col-md-12">
							<label for="">Коментар</label>
							<textarea class="form-control" id="" placeholder="">
						</textarea>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<label for=""></label>
					<button data-action="add" class="btn btn-primary btn-lg btn-block btn_send_app"
							type="submit">
						Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

