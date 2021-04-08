<div id="template_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Шаблони повторюваних планованих операцій</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>

	<div class="filter_btns row">
		<div class="col-md-10">
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[1][0]; ?>; border:2px solid <?= $op_style[1][1]; ?>">100
				</div>
				<span>дохід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[2][0]; ?>; border:2px solid <?= $op_style[2][1]; ?>">100
				</div>
				<span>розхід</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[4][0]; ?>; border:2px solid <?= $op_style[4][1]; ?>">100
				</div>
				<span>кредит</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[5][0]; ?>; border:2px solid <?= $op_style[5][1]; ?>">100
				</div>
				<span>від співробітника компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[6][0]; ?>; border:2px solid <?= $op_style[6][1]; ?>">100
				</div>
				<span>співробітнику компанії</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $op_style[7][0]; ?>; border:2px solid <?= $op_style[7][1]; ?>">100
				</div>
				<span>між своїми касами</span>
			</div>
		</div>
		<div class="col-md-2">
			<input id="search" class="form-control" placeholder="Пошук"/>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12 template_table_div">
			<table class="table">
				<thead class="thead-dark">
				<tr>
					<!--					<th scope="col"></th>-->
					<th scope="col">#</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Сума</th>
					<th scope="col">Коментар</th>
					<th style="width:150px" scope="col">Стаття
					<th rowspan="2" scope="col">Проект</th>
					<th scope="col">Дату старту</th>
					<th scope="col">Повторювати</th>
					<th scope="col">Дата завершення</th>
					<th rowspan="2" scope="col">
						<img src="../../../../icons/bootstrap/calendar-check-white.svg"/>
					</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>

				<?php if (!empty($templates)): ?>
					<?php foreach ($templates as $template): ?>
						<tr class="template_tr" data-template_id="<?= $template['id']; ?>">
							<!--							<td>-->
							<!--								<svg class="bi bi-eye" width="1em" height="1em" viewBox="0 0 16 16"-->
							<!--									 fill="currentColor" xmlns="http://www.w3.org/2000/svg">-->
							<!--									<path fill-rule="evenodd"-->
							<!--										  d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 001.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0014.828 8a13.133 13.133 0 00-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 001.172 8z"-->
							<!--										  clip-rule="evenodd"></path>-->
							<!--									<path fill-rule="evenodd"-->
							<!--										  d="M8 5.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM4.5 8a3.5 3.5 0 117 0 3.5 3.5 0 01-7 0z"-->
							<!--										  clip-rule="evenodd"></path>-->
							<!--								</svg>-->
							<!--							</td>-->
							<td data-label="#"><?= $template['id']; ?></td>
							<td data-label="Контрагент" class="filter_td"><?= $template['contractor_name'] ?></td>
							<td data-label="Сума">
								<div class="operation_amount"
									 style="color:<?= $op_style[$template['operation_type_id']][0]; ?>; border:2px solid <?= $op_style[$template['operation_type_id']][1]; ?>; min-width:100px">
									<?= $template['amount']; ?>
									&nbsp;<?= key_exists($template['currency'], $currencies)
										? $currencies[$template['currency']] : ''; ?>
							</td>
							<td data-label="Коментар" class="filter_td"><?= $template['comment']; ?></td>
							<td data-label="Стаття">
								<?php if ($template['operation_type_id'] == 1):
									$articles = $income_list;
									$select = true;
								elseif ($template['operation_type_id'] == 2):
									$articles = $expense_list;
									$select = true;
								else:
									$select = false;
								endif; ?>
								<?php if ($select): ?>
									<select class="form-control article_id"
											name="article_id" required="">

										<option value="">Не вибрана стаття</option>
										<?php if (!empty($articles)): ?>
											<?php foreach ($articles as $article_item): ?>
												<optgroup label="<?= $article_item['name']; ?>">
													<?php if (!empty($article_item['children'])): ?>
														<?php foreach ($article_item['children'] as $article_1): ?>
															<option
																<?= $template['article_id'] == $article_1['id'] ? 'selected' : '' ?>
																value="<?= $article_1['id']; ?>"><?= $article_1['name']; ?></option>
														<?php endforeach; ?>
													<?php else: ?>
														<option
															<?= $template['article_id'] == $article_item['id'] ? 'selected' : '' ?>
															value="<?= $article_item['id']; ?>"><?= $article_item['name']; ?></option>

													<?php endif; ?>
												</optgroup>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								<?php else: ?>
									<?php if ($template['template_type_id'] == 4): ?>
										<span>Кредит</span>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td data-label="Проект" class="filter_td"><?= $template['project_name']; ?></td>
							<td data-label="Дату старту">
								<input type="date" class="form-control change_template_date" placeholder=""
									   name="date_for"
									   value="<?= !empty($template['planned_on']) ? date("Y-m-d", $template['planned_on']) : ''; ?>"
									   required="">
							</td>
							<td data-label="Повторювати">
								<select class="form-control" id="repeat_type" name="repeat_type" required="">
									<option <?= $template['repeat_period'] === 'month' ? 'selected' : ''; ?>
											value="month">
										Щомісяця
									</option>
									<option <?= $template['repeat_period'] === 'day' ? 'selected' : ''; ?> value="day">
										Щодня
									</option>
									<option <?= $template['repeat_period'] === 'week' ? 'selected' : ''; ?>
											value="week">
										Щотижня
									</option>
								</select>
							</td>
							<td data-label="Дату завершення">
								<!--								<input type="date" class="form-control change_template_date" placeholder=""-->
								<!--									   name="date_for"-->
								<!--									   value="-->
								<? //= !empty($template['planned_on']) ? date("Y-m-d", $template['planned_on']) : ''; ?><!--"-->
								<!--									   required="">-->
								<?= !empty($template['planned_on']) ? date("d.m.Y", $template['planned_on']) : 'без кінця'; ?>
							</td>
							<td data-label="Сповіщення">
								<input type="checkbox" class="form-control notify"
									   <?= $template['notify'] == 1 ? 'checked' : ''; ?>/>
							</td>
							<td>
								<div class="icon edit_template" data-title="Редагувати шаблон">
									<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
								</div>
								<div class="icon delete_template" data-title="Видалити шаблон">
									<img src="<?= base_url(); ?>/icons/bootstrap/trash.svg"/>
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

