<div id="credits">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Кредити</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_lead_sidenav" data-title="Додати кредит">
				<a href="/credit/add">
					<img src="../../../icons/fineko/leads.svg"/>
				</a>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-md-12">
			<!--			<a class="btn btn-info">Додати</a>-->
			<span class="total_span">Загальна сума в грн - <?= number_format($total, 2, ',', ' '); ?></span>
			<span
				class="total_span">Загальна сума по процентах в грн - <?= number_format($total_percent, 2, ',', ' '); ?> грн</span>

			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Департамент</th>
					<th scope="col">Сума</th>
					<th scope="col">Сума в гривні</th>
					<th scope="col">Тип</th>
					<th scope="col">Заявка на віддачу тіла</th>
					<th scope="col">Дата віддачі</th>
					<th scope="col">Процент ( % )</th>
					<th scope="col">Процент</th>
					<th scope="col">Процент в гривні</th>
					<th scope="col">Дата оплати процентів</th>
					<th scope="col">Заявка на проценти</th>
					<th scope="col">Коментар</th>
					<th scope="col">Відповідальний</th>
					<th scope="col"></th>

				</tr>
				</thead>
				<tbody>
				<?php if (!empty($credits)): ?>
					<?php foreach ($credits as $credit): ?>
						<tr data-credit_id="<?= $credit['id']; ?>">
							<td data-label="#"><?= $credit['id']; ?></td>
							<td data-label="Контрагент"><?= $credit['contractor_name']; ?></td>
							<td data-label="Департамент"><?= $credit['department']; ?></td>
							<td data-label="Сума"
								class="amount"><?= number_format($credit['amount'], 0, ',', '&nbsp;'); ?>
								&nbsp;<?= $currencies_names[$credit['currency']]; ?></td>
							<td data-label="Сума в гривні"><?= number_format($credit['total'], 0, ',', '&nbsp;'); ?></td>
							<td data-label="Тип"><?= $credit['type']; ?></td>
							<td data-label="Заявка на віддачу тіла">
								<?php if ($credit['credit_app_id'] == 0): ?>
									<a class="btn btn-info" href="#">Створити</a>
								<?php else: ?>
									<a href="/application/edit/<?= $credit['credit_app_id']; ?>"><?= $credit['credit_app_id']; ?></a>
								<?php endif; ?>
							</td>
							<td data-label="Дата віддачі"><?= date("d.m.Y", $credit['end_date']); ?></td>

							<td data-label="Процент ( % )"><?= !empty($credit['percent']) ? $credit['percent'] . '%' : ''; ?></td>
							<td data-label="Процент" class="percent_amount">
								<?php if (!empty($credit['percent_amount'])) : ?>
									<?= number_format($credit['percent_amount'], 0, ',', '&nbsp;'); ?>
									&nbsp;<?= $currencies_names[$credit['percent_currency']]; ?>
								<?php endif; ?>
							</td>
							<td data-label="Процент в гривні">
								<?php if (!empty($credit['percent_total'])) : ?>
									<?= number_format($credit['percent_total'], 0, ',', '&nbsp;'); ?>
								<?php endif; ?>
							</td>
							<td data-label="Дата оплати процентів"><?= !empty($credit['next_payment_date']) ? date("d.m.Y", $credit['next_payment_date']) : ''; ?></td>
							<td data-label="Заявка на проценти">
								<?php if ($credit['percent_app_id'] == 0): ?>
									<a class="btn btn-info" href="#">Створити</a>
								<?php else: ?>
									<a href="/application/edit/<?= $credit['percent_app_id']; ?>"><?= $credit['percent_app_id']; ?></a>
								<?php endif; ?>
							</td>
							<td data-label="Коментар"><?= $credit['comment']; ?></td>
							<td data-label="Відповідальний"><?= $credit['responsible_name']; ?> <?= $credit['responsible_surname']; ?></td>
							<td data-label="">
								<a href="/credit/edit/<?= $credit['id']; ?>">
									<img class="edit_credit icon" src="../../../icons/fineko/edit.svg"/>
								</a>
								<img class="delete_credit icon" src="../../../icons/fineko/delete.svg"/>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<!--				<tr class="total_tr">-->
				<!--					<td colspan="4" class="text-right"> Загальна сума в грн</td>-->
				<!--					<td colspan="2">--><? //= number_format($total, 2, ',', ' '); ?><!-- грн</td>-->
				<!--					<td colspan="5" class="text-right">Загальна сума по процентах в грн</td>-->
				<!--					<td colspan="2">-->
				<? //= number_format($total_percent, 2, ',', ' '); ?><!-- грн</td>-->
				<!--					<td colspan="4"></td>-->
				<!--				</tr>-->
				</tbody>
			</table>
		</div>
	</div>
</div>



