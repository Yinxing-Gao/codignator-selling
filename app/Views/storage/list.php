<div id="storage_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h2 class="text-center">Cклади</h2>
		</div>

		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?=base_url();?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_storage_sidenav" data-title="Додати нового ліда">
				<img src="<?=base_url();?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<select class="form-control" id="department_id" required="">
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option <?= $department_id == $department['id'] ? 'selected' : ''; ?>
								value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-10"></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th>Назва</th>
					<th>Останнє оновлення</th>
					<th>Сума на балансі</th>
					<th>Кількість найменувань</th>
					<th>Кількість унікальних позицій</th>
					<th>Коментар</th>
					<th></th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($storages)): ?>
					<?php foreach ($storages as $storage): ?>
						<tr data-storage_id="<?= $storage['id']; ?>" class="storage_table_tr">
							<td data-label="Назва"><?= $storage['name']; ?></td>
							<td data-label="Останнє оновлення"><?= !empty($storage['last_change_date']) ? date('m.d.Y H:i:s', $storage['last_change_date']) : ''; ?></td>
							<td data-label="Сума на балансі"><?= $totals[$storage['id']]; ?></td>
							<td data-label="Кількість найменувань"></td>
							<td data-label="Кількість унікальних позицій"></td>
							<td data-label="Коментар"></td>
							<td class="storage_action_td">
								<a href="<?= $base_url; ?>/storage/names/<?= $storage['id']; ?>"
								   class="storage_action_icon_link" title="Найменування"><img
										src="<?= base_url(); ?>/icons/fineko/storage_names.svg"/></a>
								<a href="<?= $base_url; ?>/storage/storage/<?= $storage['id']; ?>"
								   class="storage_action_icon_link" title="Товари"><img
										src="<?= base_url(); ?>/icons/fineko/storage%20items.svg"/></a>
<!--								<a href="--><?//= $base_url; ?><!--/storage/inventory/--><?//= $storage['id']; ?><!--"-->
<!--								   class="storage_action_icon_link" title="Провести інвентаризацію"><img-->
<!--										src="--><?//= base_url(); ?><!--/icons/fineko/inventory.svg"/></a>-->
								<a href="<?= $base_url; ?>/storage/applications/<?= $storage['id']; ?>"
								   class="storage_action_icon_link" title="Заявки на склад"><img
										src="<?= base_url(); ?>/icons/fineko/storage%20apps.svg"/></a>
								<a title="видалити"><img class="delete_storage" src="../../../icons/fineko/delete.svg"/></a>
							</td>
							<!-- <td>
								<img class="delete_storage" src="../../../icons/bootstrap/trash.svg"/>
							</td> -->
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- side nav -->
<div id="storage_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="storage_sidenav_content">
		<select class="form-control action_select" disabled>
			<option value="add">Додати</option>
		</select>
		<hr/>
		<form class="contractor_form">
			<input type="hidden" name="account_id"
				   value="<?= $account_id; ?>">
			<input type="hidden" name="department_id"
				   value="<?= $department_id; ?>">

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Ім'я / назва *</label>
					<input type="text" class="form-control" name="name" placeholder="Ім'я"
						   required="">
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_storage" data-action="add"
							type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

