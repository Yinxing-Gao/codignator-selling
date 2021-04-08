<div id="contractor_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Контрагенти</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?= base_url() ?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_contractor_sidenav" data-title="Додати контрагента">
				<img src="<?= base_url() ?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter contractors_list">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Ім'я (назва)</th>
					<th scope="col">Тип</th>
					<th scope="col">Адреса</th>
					<th scope="col">Телефон</th>
					<th scope="col">Матриця імен</th>
					<th scope="col">Коментар</th>
					<th scope="col">Telegram&nbsp;id</th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($contractors)): ?>
					<?php foreach ($contractors as $contractor): ?>
						<tr data-contractor_id="<?= $contractor['id']; ?>"
							data-contractor_name="<?= $contractor['name']; ?>">
							<td data-label=""><input type="checkbox" class="form-control check"/></td>
							<td data-label="#"><?= $contractor['id']; ?></td>
							<td data-label="Ім'я (назва)"><?= $contractor['name']; ?></td>
							<td data-label="Тип"><?= $contractor['contractor_type']; ?></td>
							<td data-label="Адреса"><?= $contractor['address']; ?></td>
							<td data-label="Телефон"><?= $contractor['phone']; ?></td>
							<td data-label="Матриця імен"><?= $contractor['options']; ?></td>
							<td data-label="Коментар"><?= $contractor['comment']; ?></td>
							<td data-label="Telegram&nbsp;id"><?= !empty($contractor['telegram_chat_id']) ? $contractor['telegram_chat_id'] : ''; ?></td>
							<td>
								<div class="icon create_telegram_code" data-title="Запросити контрагента в систему">
									<img src="<?= base_url(); ?>/icons/fineko/telegram.svg"/>
								</div>
								<div class="icon edit_contractor" data-title="Редагувати контрагента">
									<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
								</div>
								<div class="icon delete_contractor" data-title="Видалити контрагента">
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
</div>

<div id="contractor_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="contractor_sidenav_content sidenav_content">
		<form class="contractor_form">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Ім'я / назва *</label>
					<input type="text" class="form-control" name="name" placeholder="Ім'я"
						   required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="contractor_type_existing">
						Тип</label>

					<select class="form-control" id="contractor_types" name="contractor_type" required="">
						<option value=""></option>

						<?php if (!empty($contractor_types)): ?>
							<?php foreach ($contractor_types as $contractor_type): ?>
								<option value="<?= $contractor_type; ?>"><?= $contractor_type; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="address">
						Адреса</label>
					<textarea style="height: 50px" class="form-control" id="address" name="address"
							  placeholder="" required=""></textarea>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="contractor_type_existing">
						Телефон</label>
					<input type="text" class="form-control" name="phone" placeholder="телефон"
						   required="">
				</div>


				<div class="col-xs-12 col-md-12">
					<label for="contractor_type_existing">
						Матриця імен</label>
					<textarea style="height: 100px" class="form-control" id="options" name="options"
							  placeholder="Тут можна прописати через кому альтернативні імена для контрагента, які будуть враховуватися під час пошуку"
							  required=""></textarea>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_contractor" data-action="add"
							type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

