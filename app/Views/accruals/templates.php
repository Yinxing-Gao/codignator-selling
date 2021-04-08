<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//todo окремо має рахувати нал і безнал

//?>
<div id="accrual_list">
	<div class="text-center">
		<h2>Шаблони для постійних витрат
			<a href="https://www.youtube.com/embed/eA5LXaDdaEY"
			   class="play_video_instruction">
				<img src="../../../icons/bootstrap/play.svg"/>
			</a>
		</h2>
	</div>
	<div class="filter_btns row">
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
					 style="color:<?= $params['op_style']['credit']; ?>;; border: 2px solid <?= $params['op_style']['credit']; ?> ">
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
					<th scope="col"></th><!-- для скорочення юр.компанії -->
					<th scope="col">#</th>
					<th scope="col">Сума</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Департамент</th>
					<th scope="col">Стаття</th>
					<th scope="col">Коментар</th>
					<th scope="col">Дата</th>
					<th scope="col">Періодичність</th>
					<th scope="col">Дата старту</th>
					<th scope="col">Дата завершення</th>
					<th scope="col">Аналізувати попередній місяць</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($accruals)): ?>
					<?php foreach ($accruals as $timestamp => $accrual): ?>
						<tr class="accrual_tr" data-accrual_id="<?= $accrual['id']; ?>">
							<td>OR</td>
							<td><?= $accrual['id']; ?></td>
							<td style="color: <?= $params['op_style'][$accrual['accrual_type']]; ?>">
								<?= $accrual['amount']; ?> <?= $currencies[$accrual['currency']]; ?>
							</td>
							<td><?= $accrual['contractor_name']; ?></td>
							<td><?= $accrual['department_name']; ?></td>
							<td><?= $accrual['article_name']; ?></td>
							<td><?= $accrual['comment']; ?></td>
							<td>
								<?php if (!empty($accrual['date_template'])): ?>
									<?= $accrual['date_template']; ?>
								<?php else: ?>
									<?= !empty($accrual['date']) ? date('d.m.Y', $accrual['date']) : ' - '; ?>
								<?php endif; ?>
							</td>
							<td><?= $accrual['repeat_period']; ?></td>
							<td><?= !empty($accrual['repeat_start_date']) ? date('d.m.Y', $accrual['repeat_start_date']) : ' - '; ?></td>
							<td><?= !empty($accrual['repeat_end_date']) ? date('d.m.Y', $accrual['repeat_end_date']) : ' - '; ?></td>
							<td><?= !empty($accrual['compare_prev']) ? "Так" : 'Ні'; ?></td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>



