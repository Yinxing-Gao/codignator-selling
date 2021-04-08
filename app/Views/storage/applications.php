<div id="storage_application_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center"><?= lang_('Storage.storage applications', $locale); ?></h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нового ліда">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="static_menu">
		<div class="type_btns">
			<a class="btn btn-info" href="#all"><?= lang_('Storage.all', $locale); ?></a>
			<a class="btn btn-secondary"
			   href="/application/index/approved"><?= lang_('Storage.approved', $locale); ?></a>
			<a class="btn btn-dark" href="/application/index/payed"><?= lang_('Storage.payed', $locale); ?></a>
		</div>
		<?php if (!empty($date_filter)): ?>
			<div class="date_filters">
				<div>
					<label for="date_for"><?= lang_('Storage.from', $locale); ?></label>
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
				   href="#"><?= lang_('Storage.by date', $locale); ?></a>
			</div>
		<?php endif; ?>
	</div>
	<div class="fixed_menu">
		<div class="fixed_container">
			<div class="action_btns">
				<?= in_array('can_aprove_apps', $access) ? '<a class="btn btn-info approve" href="#">' . lang_('Storage.approve', $locale) . '</a>' : '' ?>
				<?= in_array('can_transfer_apps', $access) ? '<a class="btn btn-info transfer_to_pay" href="#">' . lang_('Storage.transfer to pay', $locale) . '</a>' : '' ?>
				<a class="btn btn-info pay" href="#"><?= lang_('Storage.pay', $locale); ?></a>
				<a class="btn btn-info check_as_payed" href="#"><?= lang_('Storage.check as payed', $locale); ?></a>
				<?= in_array('can_reject_apps', $access) ? '<a class="btn btn-info reject" href="#">' . lang_('Storage.reject', $locale) . '</a>' : '' ?>

			</div>
			<div id="numbers_panel">
				<div id="numbers">

				</div>
			</div>
		</div>
	</div>

	<div id="panel_all" class="panel">
		<div class="row">
			<div class="col-md-12" id="search_table_block"></div>
			<div class="col-md-12" id="app_table_block">
				<table class="table tablesorter" id="app_table">
					<thead class="thead-dark">
					<tr>
						<th scope="col"></th>
						<th scope="col">#</th>
						<th scope="col"><?= lang_('Storage.author', $locale); ?></th>
						<th scope="col"><?= lang_('Storage.department', $locale); ?></th>
						<th scope="col"><?= lang_('Storage.date', $locale); ?></th>
						<th scope="col"><?= lang_('Storage.date for', $locale); ?></th>
						<th scope="col"><?= lang_('Storage.items', $locale); ?></th>

						<th scope="col"><?= lang_('Storage.status', $locale); ?></th>
						<th scope="col"></th>
						<th scope="col"></th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($applications)): ?>
						<?php foreach ($applications as $app): ?>
							<tr data-app-id="<?= $app['id']; ?>">
								<td><input class="row_checkbox" type="checkbox"/></td>
								<td><?= $app['id']; ?></td>
								<td><?= $app['author']; ?></td>
								<td><?= $app['department']; ?></td>
								<td><?= date('d.m.Y', $app['date']); ?></td>
								<td><?= date('d.m.Y', $app['date_for']); ?></td>
								<td class="items_td">
									<?php if (!empty($app['items'])): ?>
										<table class="table tablesorter" id="app_table">
											<tbody>
											<?php foreach ($app['items'] as $item): ?>
												<tr>
													<td>#<?= $item['storage_name_id']; ?></td>
													<td><?= $item['name']; ?></td>
													<td><?= $item['amount']; ?> <?= $item['unit']; ?></td>
												</tr>
											<?php endforeach; ?>
											</tbody>
										</table>
									<?php endif; ?>

								</td>

								<td></td>
								<td>
									<a href="/application/edit/<?= $app['id']; ?>"><img class="edit_application"
																						src="<?= $base_url; ?>/icons/bootstrap/pencil.svg"/></a>
									<img class="delete_application" src="<?= $base_url; ?>/icons/bootstrap/trash.svg"/>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

