<div id="search_result_table_block">
<h6 class="text-center">Результати пошуку</h6>
<table class="table tablesorter" id="search_result_table">
	<thead class="thead-dark">
	<tr>
		<th scope="col"></th>
		<th scope="col">#</th>
		<th scope="col"><?= lang_('App.author', $locale); ?></th>
		<th scope="col"><?= lang_('App.department', $locale); ?></th>
		<th scope="col"><?= lang_('App.date', $locale); ?></th>
		<th scope="col"><?= lang_('App.date for', $locale); ?></th>
		<th scope="col"><?= lang_('App.amount', $locale); ?></th>
		<th scope="col"><?= lang_('App.currency', $locale); ?></th>
		<th scope="col"><?= lang_('App.type', $locale); ?></th>
		<th scope="col"><?= lang_('App.product/service', $locale); ?></th>
		<th scope="col"><?= lang_('App.project', $locale); ?></th>
		<th scope="col"><?= lang_('App.amount in UAH', $locale); ?></th>
		<th scope="col"><?= lang_('App.payed', $locale); ?></th>
		<th scope="col"><?= lang_('App.amount left', $locale); ?></th>
		<th scope="col"><?= lang_('App.status', $locale); ?></th>
		<th scope="col"></th>
		<th scope="col"></th>
		<th scope="col"></th>
		<th scope="col"><span class="close_search_table">x</span></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($order_list)): ?>
		<?php foreach ($order_list as $app): ?>
			<tr class="app_row_cat_<?= $app['category']; ?>" data-app-id="<?= $app['id']; ?>">
				<td><input class="row_checkbox" type="checkbox"/></td>
				<td><?= $app['id']; ?></td>
				<td><?= $app['author']; ?></td>
				<td><?= $app['department']; ?></td>
				<td><?= date('d.m.Y', $app['date']); ?></td>
				<td><?= date('d.m.Y', $app['date_for']); ?></td>
				<td>
					<?= $app['amount']; ?>
					<input class="form-control amount_to_pay"/>
				</td>
				<td><?= $currencies[$app['currency']]; ?></td>
				<td><?= $types[$app['type_id']]; ?></td>
				<td class="product_td">
					<?= $app['product']; ?>
					<?php if (in_array('can_write_director_comments', $access)): ?>
						<img src="<?= $base_url; ?>/img/comment_icon.jpg" class="add_director_comment"/>
					<?php endif; ?>
					<span class="director_comment">
										<?php if (!empty($app['director_comment'])): ?>
											<hr/>
											<strong><?= lang_('App.director comment', $locale); ?>:</strong>
											<span><?= $app['director_comment']; ?></span>
										<?php endif; ?>
									</span>
				</td>
				<td></td>
				<td><?= $app['total']; ?></td>

				<td><?= !empty($app['payed_amount']) ? (float)$app['payed_amount'] : 0; ?></td>
				<td class="amount_left_to"><?= $app['total'] - $app['payed_amount']; ?></td>

				<td><?= $app['status']; ?></td>
				<td>
					<select name="category" class="category_select">
						<option value=""></option>
						<option value="A" <?= $app['category'] == 'A' ? 'selected' : ''; ?>>A</option>
						<option value="B" <?= $app['category'] == 'B' ? 'selected' : ''; ?>>B</option>
						<option value="C" <?= $app['category'] == 'C' ? 'selected' : ''; ?>>C</option>
					</select>
				</td>
				<td><a data-app_info="<?= $app['id']; ?>"
					   class="btn btn-info app_info"><?= lang_('App.details', $locale); ?></a></td>
				<td>
					<?php if ($page_options['can_edit_apps']): ?>
						<a href="/application/edit/<?= $app['id']; ?>"><img class="edit_application"
																			src="<?= $base_url; ?>/img/edit.jpg"/></a>
					<?php endif; ?>
					<img class="delete_application" src="<?= $base_url; ?>/img/trash-icon.jpg"/>
				</td>
				<td><span class="close_app">x</span></td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>

	</tbody>
</table>
</div>
