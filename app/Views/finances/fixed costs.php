<div id="leads_list">
	<input type="hidden" id="api_key" value="<?= $account->api_key; ?>"/>
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Постійні витрати</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_budgets_sidenav" data-title="Додати бюджет">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 ">
			<div class="">
				<table class="table table-striped tablesorter">
					<thead class="thead-dark">
					<!--					<tr>-->
					<!--						<th colspan="4">Бюджети</th>-->
					<!--						<th colspan="6">Шаблони операцій</th>-->
					<!--					</tr>-->
					<tr>
						<th></th>
						<th>Назва</th>
						<th>Стаття</th>
						<th>Сума</th>
						<th>Контрагент</th>
						<th>Коментар</th>
						<th>Дати</th>
						<th></th>
						<!--						<th>Назва</th>-->
						<!--						<th>Стаття</th>-->

						<!--						<th>Сума</th>-->

						<!--						<th></th>-->
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($budgets)): ?>
						<?php foreach ($budgets as $budget): ?>
							<?php $rows = count($budget['operations']) > 0 ? count($budget['operations']) : 1; ?>
							<tr>
								<td data-title="">Бюджет</td>
								<td data-title="Назва"><?= $budget['name']; ?></td>
								<td data-title="Стаття"><?= $budget['article_name']; ?></td>
								<td data-title="Сума в гривні"><?= $budget['amount']; ?> грн</td>
								<td></td>
								<td data-title="Коментар"><?= $budget['comment']; ?></td>
								<td data-title="Дати"></td>
								<td>
									<div class="icon edit_budget" data-title="Редагувати бюджет">
										<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
									</div>
									<div class="icon add_budget_operations"
										 data-title="Додати плановані операції до цього бюджету">
										<img
											src="<?= base_url(); ?>/icons/fineko/operations.svg"/>
									</div>
									<div class="icon add_budget_operations"
										 data-title="Додати шаблон операцій до цього бюджету">
										<img src="<?= base_url(); ?>/icons/fineko/operations.svg"/>
									</div>
								</td>
							</tr>
							<?php if (!empty($budget['operations'])): ?>
								<?php foreach ($budget['operations'] as $operation): ?>
									<tr>
										<td data-title="">Планована операція</td>
										<td data-title=""></td>
										<td data-title="Стаття"><?= $operation['article_name']; ?></td>
										<td data-title="Сума">
											<?= $operation['operation_type_id'] == 1 ? $operation['amount1'] : $operation['amount2']; ?>
										</td>
										<td></td>
										<td><?= $operation['comment']; ?></td>
										<td></td>
										<td>
											<div class="icon edit_operation" data-title="редагувати шаблон операції">
												<img
													src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div id="budgets_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>
	<h4 class="text-center">Додати бюджет</h4>
	<div class="budget_sidenav_content">

		<form>
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="">
						Назва*</label>
					<input class="form-control" type="text" name="name">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Стаття</label>
					<select class="form-control" name="article_id" >
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Сума</label>
					<div class="input-group">
						<input class="form-control" type="text" name="amount">
						<select class="form-control" id="currency" name="currency" required="">
							<option value="UAH">₴</option>
<!--							<option value="USD">$</option>-->
<!--							<option value="EUR">€</option>-->
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Тип</label>
					<div class="input-group">
						<select class="form-control" name="type" required="">
							<option value="fixed costs">Постійні витрати</option>

						</select>
					</div>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Коментар</label>
					<textarea class="form-control" name="comment"></textarea>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_budget" type="submit">
						Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
