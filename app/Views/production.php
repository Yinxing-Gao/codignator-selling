<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
// Додати зверху зафіксовану плашку, яка буде показувати к-сть грошей в касі по налу і по безналу
// і автоматично відмінусовувати що вже оплачено, і скільки лишається грошей

//окремо має рахувати нал і безнал


?>
<div id="production">
	<div class="text-center">
		<h2>Проекти по виробництву<a href="#" class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>
		<h3>Зараз в роботі</h3>
	</div>

	<div class="row">

		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">#</th>
					<th scope="col">Проект</th>
					<th scope="col">Продукт</th>
					<th scope="col">Контрагент</th>
					<th scope="col">Форма оплати</th>
					<th scope="col">Сума</th>
					<th scope="col">Статус</th>
					<th scope="col">Прогрес</th>
					<th scope="col">Дата завершення</th>
					<th scope="col">Коментар</th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($projects)): ?>
					<?php foreach ($projects as $project): ?>
						<tr>
							<td><?= $project['id']; ?></td>
							<td><?= $project['name']; ?></td>
							<td>
								<?php if (!empty($project['products'])): ?>
									<?php foreach ($project['products'] as $product): ?>
										<?= $product['name']; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td><?= $project['contractor']; ?></td>
							<td><?= $project['type']; ?></td>
							<td><?= $project['amount']; ?></td>
							<td><a href="production/process/<?= $project['id']; ?>"><?= $project['status']; ?></a></td>
							<td><div id="progressbar">
									<div class="progress-label">47%</div>
								</div>
							</td>
							<td><?= date('Y.m.d', $project['end_date']); ?></td>
							<td><?= $project['comment']; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>


