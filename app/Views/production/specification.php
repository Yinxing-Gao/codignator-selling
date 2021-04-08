<div id="specification">
	<input type="hidden" id="specification_id" value="<?= !empty($this_specification) ? $this_specification->id : '555'; ?>"/>
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Специфікація <span id="specification_name_title"><?= !empty($this_specification) ? $this_specification->name : ''; ?></span></h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" id="add_subspecification_button" data-title="Додати підспецифікацію">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати прорахунки в проект">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<br/>

	<div class="row text-center">
		<div class="col-md-2">
			<div class="row">
				<div class="col-md-12">

					<input id="specification_name_field" type="text" placeholder="Назва специфікації" class="form-control task_statistics"
						   value="<?= !empty($this_specification->name) ? $this_specification->name : ''; ?> "/>
				</div>
				<div class="col-md-12 is_virtual_div">
					<input type="checkbox" style="margin-right: 20px;"
						   <?= !empty($this_specification->is_virtual) ? 'checked' : ''; ?>/>
					<label>Тимчасова</label>
				</div>
				<!--			<div class="row" style="margin-bottom: 20px;">-->
				<!--				<button class="btn btn-info">Додати підспецифікацію</button>-->
				<!--			</div>-->
				<!--			<div class="row" style="margin-bottom: 20px;">-->
				<!--				<button class="btn btn-info">Додати прорахунки в проект</button>-->
				<!--			</div>-->
				<div class="col-md-12">
					<hr/>
					<h6>Скопіювати з іншої специфікації</h6>
					<select class="form-control" id="specifications" name="specification_id" required="">
						<option value=""></option>
						<?php if (!empty($specifications)): ?>
							<?php foreach ($specifications as $specification): ?>
								<option value="<?= $specification['id']; ?>"><?= $specification['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<button class="btn btn-block btn-info copy_specification" type="submit">Скопіювати</button>
				</div>

				<div class="col-md-12">
					<hr/>
					<h6>Завантажити Excel</h6>
					<input type="file" class="form-control"/>

<!--					<button class="btn btn-block btn-info upload_excel" type="submit">Завантажити</button>-->
					<div class="uploaded_file"></div>
					<input type="hidden" name="uploaded_files" class="ajax-reply"/>
					<a href="<?= base_url(); ?>/uploads/excel_templates/spec.xlsx">Шаблон</a>
				</div>

				<div class="col-md-12">
					<hr/>
					<h6>Батьківські специфікації</h6>
                    <div>
                        <?php foreach ($parents as $parent): ?>
                            <a href="/production/specification/<?= $parent->id ?>">
                                <?= $parent->name ?>
                            </a>
                            &nbsp;->
                        <?php endforeach; ?>
                        <?= $this_specification->name ?>
                    </div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<p>Виберіть нижче існуючі специфікації чи окремі елементи зі складу, щоб добавити їх в специфікацію</p>
			<div class="specification_select row">
				<div class="col-xs-12 col-md-12">
					<h4>Специфікації</h4>
					<select class="form-control specification_select" required="" name="specification_select">
						<option value="">Виберіть специфікації</option>
						<?php
						foreach ($specifications as $specification): ?>
							<option id="<?= $specification['id'] ?>" value="<?= $specification['id'] ?>"><?= $specification['name'] ?></option>
						<? endforeach; ?>
					</select>
				</div>
			</div>
			<div class="storage_selects">
				<h4>Склади</h4>
				<?php
				foreach ($storages as $storage): ?>
					<div class="row storage_names_select">
						<div class="col-xs-12 col-md-12">
							<h5><?= $storage['name'] ?></h5>
							<select class="form-control storage_names_select" id="<?= $storage['id'] ?>" required=""
									name="storage_names">
								<option value="">Виберіть товари</option>
                                <?php
                                foreach ($storage_names[$storage['id']] as $storage_name): ?>
                                    <option id="<?= $storage_name['id'] ?>" value="<?= $storage_name['id'] ?>"><?= $storage_name['name'] ?></option>
                                <? endforeach; ?>
							</select>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="text-center specifications_table_div">
				<h4>Підспецифікації</h4>
				<table border="1px solid" class="table tablesorter">
					<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>Кількість</th>
						<th>Одиниця вимірювання</th>
						<th></th>
					</tr>
					</thead>
					<tbody>

					<?php if (!empty($this_specification->items)): ?>
						<?php foreach ($this_specification->items as $item): ?>
							<?php if ($item['type'] === 'specification'): ?>
								<tr data-name_id="<?= $item['id']; ?>">
									<td><?= $item['subspecification_id']; ?></td>
									<td><?= $item['specification_name']; ?></td>
									<td><input type="number" value="<?= $item['amount']; ?>" class="form-control amount_ajax"/></td>
									<td>шт</td>
									<td>
										<img class="delete_specification_name"
											 src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
									</td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					</tbody>
				</table>
			</div>
			<div class="text-center storage_names_table_div">
				<h4>Окремі позиції</h4>
				<table border="1px solid" class="table tablesorter">
					<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>Артикул</th>
						<th>Кількість</th>
						<th>Одиниця вимірювання</th>
						<th>Склад</th>
						<th></th>
					</tr>
					</thead>
					<tbody>

					<?php if (!empty($this_specification->items)): ?>
						<?php foreach ($this_specification->items as $item): ?>
							<?php if ($item['type'] === 'storage_name'): ?>
								<tr data-name_id="<?= $item['id']; ?>">
									<td><?= $item['storage_name_id']; ?></td>
									<td><?= $item['storage_item_name']; ?></td>
									<td><?= $item['article']; ?></td>
									<td><input type="number" value="<?= $item['amount']; ?>" class="form-control amount_ajax"/></td>
									<td><?= $item['unit_name']; ?></td>
									<td><?= $item['storage_name']; ?></td>
									<td>
										<!--										<img class="edit_specification_name" src="../../img/edit.jpg"/>-->
										<!--										<img class="done_editing_specification_name" src="../../img/done.jpg"/>-->
										<!--										<img class="delete_specification_name" src="../../img/trash-icon.jpg"/>-->
										<img class="delete_specification_name"
											 src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
									</td>
								</tr>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="text-center services_table_div">
				<h4>Послуги</h4>
				<table border="1px solid" class="table tablesorter">
					<thead>
					<tr>
						<th>#</th>
						<th>Назва</th>
						<th>Артикул</th>
						<th>Кількість</th>
						<th>Одиниця вимірювання</th>
						<th></th>
					</tr>
					</thead>
					<tbody>

					<?php if (!empty($this_specification->items)): ?>
						<?php foreach ($this_specification->items as $item): ?>
							<?php if ($item['type'] === 'service'): ?>
								<tr data-name_id="<?= $item['storage_name_id']; ?>">
									<td><?= $item['storage_name_id']; ?></td>
									<td><?= $item['storage_item_name']; ?></td>
									<td><?= $item['article']; ?></td>
									<td><input type="number" value="<?= $item['amount']; ?>" class="form-control amount_ajax"/></td>
									<td><?= $item['unit_name']; ?></td>
									<!--									<td>-->
									<? //= $item['storage_name']; ?><!--</td>-->
									<td>
										<!--										<img class="edit_specification_name" src="../../img/edit.jpg"/>-->
										<!--										<img class="done_editing_specification_name" src="../../img/done.jpg"/>-->
										<!--										<img class="delete_specification_name" src="../../img/trash-icon.jpg"/>-->
										<img class="delete_specification_name"
											 src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
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
</div>
