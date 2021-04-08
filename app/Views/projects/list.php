<div id="projects">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Проекти</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>
	<div class="action_btns">
		<a style="visibility:hidden" class="btn btn-info combine" href="#">Об'єднати</a>
		<!--		--><?php //endif; ?>
	</div>
	<div class="row">
		<h4 class="text-center" style="width: 100%">Мої проекти</h4>
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Назва</th>
					<th scope="col">Департамент</th>
					<th scope="col">Продукт</th>
<!--					<th scope="col">Контрагент</th>-->
<!--					<th scope="col">Форма оплати</th>-->
<!--					<th scope="col">Сума</th>-->
					<th scope="col">Статус</th>
					<th scope="col">Дата завершення</th>
					<th scope="col">Коментар</th>
<!--					<th scope="col">Склади</th>-->
					<th scope="col">Спостерігачі</th>
					<th scope="col"></th>
<!--					<th scope="col"></th>-->
<!--					<th scope="col"></th>-->

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($projects)): ?>
					<?php foreach ($projects as $project): ?>
						<tr data-project_id="<?= $project['id']; ?>">
							<td data-label=""><input class="project_checkbox" type="checkbox"></td>
							<td data-label="#"><?= $project['id']; ?></td>
							<td data-label="Назва"><?= $project['name']; ?></td>
							<td data-label="Департамент"><?= $project['department_name']; ?></td>
							<td data-label="Продукти">
								<?php if (!empty($project['products'])): ?>
									<ul>
										<?php foreach ($project['products'] as $product): ?>
											<li><?= $product['name']; ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</td>
<!--							<td data-label="Контрагент">--><?//= $project['contractor_name']; ?><!--</td>-->
<!--							<td data-label="Форма оплати">--><?//= $project['type']; ?><!--</td>-->
<!--							<td data-label="Сума">--><?//= $project['contract_amount']; ?><!--</td>-->
							<td data-label="Статус"><?= $statuses[$project['status']]; ?></td>
							<td data-label="Дата старту"><?= $project['end_date'] > 0 ? date("d.m.Y", $project['end_date']) : ''; ?></td>
							<td data-label="Коментар"><?= $project['comment']; ?></td>
<!--							<td data-label="Склади">-->
<!--								--><?php //if (!empty($project['storages'])): ?>
<!--									<ul>-->
<!--										--><?php //foreach ($project['storages'] as $storage): ?>
<!--											<li>--><?//= $storage['name']; ?><!--</li>-->
<!--										--><?php //endforeach; ?>
<!--									</ul>-->
<!--								--><?php //endif; ?>
<!--							</td>-->
							<td data-label="Спостерігачі">
								<?php if (!empty($project['observers'])): ?>
									<ul>
										<?php foreach ($project['observers'] as $observer): ?>
											<li><?= $observer['name']; ?> <?= $observer['surname']; ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</td>
							<td>
								<a href="/plan_fact/project/<?= $project['id']; ?>" class="project_plan_fact"
								   data-title="План факт">
									<div class="icon" data-title="План факти">
										<img src="../../../icons/fineko/plan_fact.svg"/>
									</div>
								</a>
								<div class="icon" data-title="Редагувати">
									<img src="../../../icons/fineko/edit.svg" class="edit_project"
										 data-title="редагувати"/>
								</div>
								<div class="icon" data-title="Специфікації">
									<img src="../../../icons/fineko/specifications.svg" class="open_specification"
										 data-title="специфікації"/>
								</div>
								<div class="icon" data-title="інформація про ліда">
									<img src="../../../icons/fineko/leads.svg" class=""
										 data-title="інформація про ліда"/>
								</div>
								<div class="icon" data-title="Видалити">
									<img src="../../../icons/fineko/delete.svg" class="delete_project"
										 data-title="видалити"/>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>

		<h4 class="text-center" style="width: 100%">Спостерігаю</h4>
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Проект</th>
					<th scope="col">Департамент</th>
					<th scope="col">Продукт</th>
<!--					<th scope="col">Контрагент</th>-->
<!--					<th scope="col">Форма оплати</th>-->
<!--					<th scope="col">Сума</th>-->
					<th scope="col">Статус</th>
					<th scope="col">Дата завершення</th>
					<th scope="col">Коментар</th>
<!--					<th scope="col">Склади</th>-->
					<th scope="col">Спостерігачі</th>
					<th scope="col"></th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($observed_projects)): ?>
					<?php foreach ($observed_projects as $project): ?>
						<tr data-project_id="<?= $project['id']; ?>">
							<td data-label=""><input class="project_checkbox" type="checkbox"></td>
							<td data-label="#"><?= $project['id']; ?></td>
							<td data-label="Назва"><?= $project['name']; ?></td>
							<td data-label="Департамент"><?= $project['department_name']; ?></td>
							<td data-label="Продукти">
								<?php if (!empty($project['products'])): ?>
									<ul>
										<?php foreach ($project['products'] as $product): ?>
											<li><?= $product['name']; ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</td>
							<!--							<td data-label="Контрагент">--><?//= $project['contractor_name']; ?><!--</td>-->
							<!--							<td data-label="Форма оплати">--><?//= $project['type']; ?><!--</td>-->
							<!--							<td data-label="Сума">--><?//= $project['contract_amount']; ?><!--</td>-->
							<td data-label="Статус"><?= $statuses[$project['status']]; ?></td>
							<td data-label="Дата старту"><?= $project['end_date'] > 0 ? date("d.m.Y", $project['end_date']) : ''; ?></td>
							<td data-label="Коментар"><?= $project['comment']; ?></td>
							<!--							<td data-label="Склади">-->
							<!--								--><?php //if (!empty($project['storages'])): ?>
							<!--									<ul>-->
							<!--										--><?php //foreach ($project['storages'] as $storage): ?>
							<!--											<li>--><?//= $storage['name']; ?><!--</li>-->
							<!--										--><?php //endforeach; ?>
							<!--									</ul>-->
							<!--								--><?php //endif; ?>
							<!--							</td>-->
							<td data-label="Спостерігачі">
								<?php if (!empty($project['observers'])): ?>
									<ul>
										<?php foreach ($project['observers'] as $observer): ?>
											<li><?= $observer['name']; ?> <?= $observer['surname']; ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</td>
							<td>
								<a href="/plan_fact/project/<?= $project['id']; ?>" class="project_plan_fact"
								   data-title="План факт">
									<div class="icon" data-title="План факти">
										<img src="../../../icons/fineko/plan_fact.svg"/>
									</div>
								</a>
								<div class="icon" data-title="Специфікації">
									<img src="../../../icons/fineko/specifications.svg" class="open_specification"
										 data-title="специфікації"/>
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


