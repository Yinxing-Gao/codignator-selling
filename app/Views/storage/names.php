<div id="storage_name_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Найменування товарів на складі (<?= $storage->name; ?>)</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати нове найменування">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<p class="text-center">Тут можна змінювати назви і одиниці вимірювання всіх найменувань на складі. Ці назви
		використовуються на всіх інших сторінках. К-сть - це необхідна стандартна кількість цих одиниць, яка повинна
		бути на складі ( але не обов'язково є)</p>
<!--	<div class="action_btns">-->
<!--		<a class="btn btn-info" href="/storage/add_names/--><?//= $storage->id; ?><!--">Додати найменування</a>-->
<!--	</div>-->
	<div class="filter_btns">
		<div class="form-check" style="display: none">
			<input type="checkbox" class="" id="">
			<label class="form-check-label" for="">пусті позиції</label>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Назва</th>
					<th scope="col">Одиниця<br/>вимірювання</th>
					<th scope="col">Нормальна<br/>закупівельна ціна</th>
					<th scope="col">Мінімальний<br/>резерв</th>
					<th scope="col">Поставщик</th>
					<th scope="col">Опис</th>
					<th scope="col"></th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($names)): ?>
					<?php foreach ($names as $name): ?>
						<tr data-name_id="<?= $name['id']; ?>">
							<td data-label="#"><?= $name['id']; ?></td>
							<td data-label="Назва" class="storage_name_td"><span
									class="name_td_span"><?= $name['name']; ?></span></td>
							<td data-label="Одиниця вимірювання" data-unit_id="<?= $name['unit_id']; ?>"
								class="storage_unit_td"><span
									class="unit_td_span"><?= $name['unit_id']; ?></span></td>
							<td data-label="Нормальна закупівельна ціна" class="storage_price_td"><span
									class="price_td_span"><?= $name['buy_price']; ?></span>
							</td>
							<td data-label="Мінімальний резерв" class="storage_amount_td"><span
									class="amount_td_span"><?= $name['min_amount']; ?></span>
							</td>
							<td data-label="Поставщик"></td>
							<td data-label="Опис" class="storage_description_td">
								<span
									class="description_td_span"><?= $name['description']; ?></span>
							</td>
							<td>
								<div class="icon edit_storage_name" data-title="Редагувати найменування">
									<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
								</div>
								<div class="icon delete_storage_name" data-title="Видалити найменування">
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


