<div class="container" id="container_salary_pay">
	<div class="py-5 text-center">
		<!--		<img class="d-block mx-auto mb-4"-->
		<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
		<!--			 width="300">-->
		<h2>Виплатити зп</h2>
	</div>

<div class="row">

	<div class="col-md-12">
		<form class="needs-validation" novalidate="">
			<div class="row">
				<div class="col-md-2">
					<label for="username">Період</label>
					<select class="form-control" id="month_change" name="month_change" required="">
						<?php if (!empty($months)): ?>
							<?php foreach ($months as $id => $month): ?>
								<option <?= ($id == $month_year) ? 'selected' : ''; ?>
										value="<?= $id; ?>"><?= $month; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>
				<div class="col-md-8"></div>
				<div class="col-md-2">
					<label for="date_for">Дата оплати</label>
					<input type="date" class="form-control" name="salary_date"
						   required="">
				</div>
				<table class="table tablesorter">
					<thead class="thead-dark">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Cпівробітник</th>
						<th scope="col">Ставка</th>
						<th scope="col">Проценти за проекти</th>
						<th scope="col">Бонуси</th>
						<th scope="col">Штрафы</th>
						<th scope="col">Загальна сума</th>
						<th scope="col">Аванс</th>
						<th scope="col">Видано авансу</th>
						<th scope="col">Остаток по авансу</th>
						<th scope="col">Основна зп</th>
						<th scope="col">Видано основної зп</th>
						<th scope="col">Остаток</th>
						<th scope="col">Оплатити</th>
					</tr>
					</thead>
					<tbody>
					<?php if (!empty($workers)): ?>
						<?php foreach ($workers as $worker): ?>
							<tr>
								<td><?= $worker['id']; ?></td>
								<td><?= $worker['name']; ?> <?= $worker['surname']; ?></td>
								<td>
									<?php if (!empty($accruals[$worker['id']]['salary'])): ?>
										<?= $accruals[$worker['id']]['salary'] ?>
									<?php endif; ?>
								</td>
								<td>0</td>
								<td class="bonus">0</td>
								<td class="fine">0</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><input type="number" class="form-control"/></td>

							</tr>
						<?php endforeach; ?>
					<?php endif; ?>

					</tbody>
				</table>
			</div>
	</div>
</div>

</div>
