<div id="position_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Посади</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_position_sidenav" data-title="Додати нову позицію">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>
	<!--	<div class="row">-->
	<!--		<div class="col-md-2">-->
	<!--			<select class="form-control" id="department_id" required="">-->
	<!--				--><?php //if (!empty($departments)): ?>
	<!--					--><?php //foreach ($departments as $department): ?>
	<!--						<option --><? //= $department_id == $department['id'] ? 'selected' : ''; ?>
	<!--								value="--><? //= $department['id']; ?><!--">-->
	<? //= $department['name']; ?><!--</option>-->
	<!--					--><?php //endforeach; ?>
	<!--				--><?php //endif; ?>
	<!--			</select>-->
	<!--		</div>-->
	<!--		<div class="col-md-10"></div>-->
	<!--	</div>-->
	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col"></th>
					<th scope="col">#</th>
					<th scope="col">Назва</th>
					<th scope="col">Керівник</th>
					<th scope="col">Зарплата</th>
					<th scope="col">Робочі дні</th>
					<th scope="col">Аванси</th>
					<th scope="col">Департамент</th>
					<th scope="col">Потенційна кількість працівників</th>
					<th scope="col">Працівники</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($positions)): ?>
					<?php foreach ($positions as $position): ?>
						<tr data-position_id="<?= $position['id']; ?>">
							<td data-label=""><input class="position_checkbox" type="checkbox"></td>
							<td data-label="#"><?= $position['id']; ?></td>
							<td data-label="Назва"><?= $position['name']; ?></td>
							<td data-label="Керівник">
								<select class="form-control subordination" name="subordination">
									<option value=""></option>
									<?php if (!empty($positions)): ?>
										<?php foreach ($positions as $position_): ?>
											<?php if ($position_['id'] !== $position['id']): ?>
												<option
													<?= ($position_['id'] == $position['subordination']) ? 'selected' : ''; ?>
													value="<?= $position_['id']; ?>"><?= $position_['name']; ?></option>
											<?php endif; ?>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
							</td>
							<td data-label="Зарплата">
								<?php if (!empty($position['salary_amount'])): ?>
								<?= $position['salary_amount']; ?> <?= $currencies[$position['salary_currency']]; ?></td>
							<?php endif; ?>
							<td data-label="Робочі дні">
								<?php if (!empty($week_days)): ?>
									<?php foreach ($week_days as $week_day_id => $week_day_name): ?>
										<input type="checkbox"
											   <?= in_array($week_day_id, explode(',', $position['work_days'])) ? 'checked' : '' ?>
											   value="<?= $week_day_id; ?>"/>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td data-label="Аванси"><?= $position['potential_amount_of_workers']; ?></td>
							<td data-label="Департамент"><?= $position['department_name']; ?></td>
							<td data-label="Потенційна кількість працівників"></td>
							<td data-label="Працівники">
								<?php if (!empty($users)): ?>
									<select name="users[]" class="form-control position_users" multiple>
										<?php foreach ($users as $user): ?>
											<option
												<?= in_array($user['id'], $position['user_ids']) ? 'selected' : '' ?>
												class="position_user" value="<?= $user['id']; ?>">
												<?= $user['name']; ?> <?= $user['surname']; ?>
											</option>
										<?php endforeach; ?>
									</select>
								<?php endif; ?>
							</td>
							<td data-label="">
								<a href="/instruction/job/<?= $position['id']; ?>" class="icon"
								   data-title="Посадові інструкції">
									<img src="<?= base_url(); ?>/icons/fineko/positions.svg"/>
								</a>
								<div data-title="Видалити" class="delete_position icon">
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

<div id="position_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="lead_sidenav_content">
		<h4>Додати позицію</h4>
		<form>
			<input type="hidden" class="form-control" name="account_id"
				   value="<?= $account_id; ?>">
			<input type="hidden" class="form-control" name="department_id"
				   value="<?= $department_id; ?>">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Назва *</label>
					<input type="text" class="form-control" name="name" placeholder="Назва"
						   required="">
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Керівник</label>

					<select class="form-control" name="subordination">
						<option value=""></option>
						<?php if (!empty($positions)): ?>
							<?php foreach ($positions as $position_): ?>

								<option
									value="<?= $position_['id']; ?>"><?= $position_['name']; ?></option>

							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12 element">
					<label for="username">Зарплата</label>
					<div class="input-group">
						<input type="number" class="form-control" name="salary_amount" placeholder="сума"
							   value="0" required="">
						<select class="form-control" id="currency" name="salary_currency" required="">
							<option value="UAH">₴</option>
							<option value="USD">$</option>
							<option value="EUR">€</option>
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-md-12">
					<label for="">
						Робочі дні</label>
					<div class="work_days_checkbox">
						<?php if (!empty($week_days)): ?>
							<?php foreach ($week_days as $week_day_id => $week_day_name): ?>
								<input type="checkbox"
									   <?= in_array($week_day_id, [1, 2, 3, 4, 5]) ? 'checked' : '' ?>
									   name="work_days[]"
									   value="<?= $week_day_id; ?>"/>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_position" type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
