<div id="accrual_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Нарахування</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
		</div>
	</div>
	<div class="filter_btns row">
		<div class="col-md-2">
			<!--			<label for="username">Період</label>-->
			<select class="form-control" id="month_change" name="month_change" required="">
				<?php if (!empty($months)): ?>
					<?php foreach ($months as $id => $month): ?>
						<option <?= ($id == $month_year) ? 'selected' : ''; ?>
								value="<?= $id; ?>"><?= $month; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-8">
			<div class="color_block">
				<div class="color"
					 style="color:<?= $params['op_style']['debit']; ?>; border: 2px solid <?= $params['op_style']['debit']; ?>">
					100
				</div>
				<span>Дебет</span>
			</div>
			<div class="color_block">
				<div class="color"
					 style="color:<?= $params['op_style']['credit']; ?>; border: 2px solid <?= $params['op_style']['credit']; ?>">
					100
				</div>
				<span>Кредит</span>
			</div>
		</div>
		<div class="col-md-2">
			<input id="search" class="form-control" placeholder="Пошук"/>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12 accrual_table_div">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Сума</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Департамент</th>
					<th scope="col">Стаття</th>
					<th scope="col">Проект/Договір</th>
					<th scope="col">Коментар</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($accruals)): ?>
					<?php foreach ($accruals as $timestamp => $accrual): ?>
						<tr class="accrual_tr" data-accrual_id="<?= $accrual['id']; ?>">
							<!--							<td data-label="#">EK</td>-->
							<td data-label="#"><?= $accrual['id']; ?></td>
							<td data-label="Сума"><?= $accrual['amount']; ?> <?= $accrual['currency']; ?></td>
							<td data-label="Контрагент">
								<!--								--><? //= $accrual['contractor_id']; ?>
								Тестовий клієнт
							</td>
							<td data-label="Департамент">
								<!--								--><? //= $accrual['department_id']; ?>
							</td>

							<td data-label="Стаття">
								<!--								--><? //= $accrual['article_id']; ?>
							</td>
							<td data-label="Проект">
								Тестовий проект
								<!--								-->
								<? //= $accrual['project_id']; ?><!--/--><? //= $accrual['contract_id']; ?>

							</td>
							<td data-label="Коментар"><?= $accrual['comment']; ?></td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>



