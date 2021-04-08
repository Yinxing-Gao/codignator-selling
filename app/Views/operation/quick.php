<div id="operation_quick">
	<div class="row">
		<div class="col-xs-12 col-md-4 text-center">
			<h4>Швидкі операцій</h4>
			<div class="template_string income">Задача по програмуванню</div>
			<div class="template_string expense">Продукти</div>
			<div class="template_string expense">Транспорт</div>
			<div class="template_string expense">Харчування поза домом</div>
			<div class="template_string expense">Оренда квартири</div>
		</div>

		<div class="col-xs-12 col-md-4 text-center">
			<h4>Операції з заявок</h4>
			<div class="template_string income">Задача по програмуванню</div>
			<div class="template_string expense">Продукти</div>
			<div class="template_string expense">Транспорт</div>
			<div class="template_string expense">Харчування поза домом</div>
			<div class="template_string expense">Оренда квартири</div>
		</div>

		<div class="col-xs-12 col-md-12 text-center">
			<h4>Операції по проектах</h4>
			<div class="row">
				<?php if (!empty($projects)): ?>
					<?php foreach ($projects as $project): ?>
						<div class="col-xs-12 col-md-12 text-center">
							<h6><?= $project['name']; ?></h6>
							<div class="template_string income">Задача по програмуванню</div>
							<div class="template_string expense">Продукти</div>
							<div class="template_string expense">Транспорт</div>
							<div class="template_string expense">Харчування поза домом</div>
							<div class="template_string expense">Оренда квартири</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>



