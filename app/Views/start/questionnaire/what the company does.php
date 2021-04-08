<link rel="stylesheet" href="<?= base_url(); ?>/css/questionnaire.css">
<div class="row">
	<div class="col-md-12">
		<div class="task_item">
			<label>Розкажіть нам, будь ласка, чим займається ваша компанія</label>
			<?php if (!empty($company_types)): ?>
				<select class="form-control">
					<?php foreach ($company_types as $type): ?>
						<option value="<?= $type['id']; ?>"><?= $type['name']; ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="task_item">
			<label>Скільки у вас місячний оборот в гривні</label>
			<?php if (!empty($company_types)): ?>
				<select class="form-control">
					<option value="0-50000">0-50 000</option>
					<option value="50 000-250 000">50 000-250 000</option>
					<option value="250 000 - 1 000 000">250 000 - 1 000 000</option>
					<option value="1 000 000 - 5 000 000">1 000 000 - 5 000 000</option>
					<option value="5 000 000 - 20 000 000">5 000 000 - 20 000 000</option>
					<option value="20 000 000 - 50 000 000">20 000 000 - 50 000 000</option>
					<option value="50 000 000 - 250 000 00">50 000 000 - 250 000 000</option>
					<option value="250 000 000 - 1 000 000 000">250 000 000 - 1 000 000 000</option>
					<option value="1 000 000 000 і вище">1 000 000 000 і вище</option>
				</select>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="task_item">
			<label>Скільки у вас співробітників</label>
			<?php if (!empty($company_types)): ?>
				<select class="form-control">
					<option value="1-10">1 - 10</option>
					<option value="1-10">10 - 30</option>
					<option value="1-10">30 - 50</option>
					<option value="1-10">50 - 100</option>
					<option value="1-10">100 - 250</option>
					<option value="1-10">250 - 500</option>
					<option value="1-10">500 - 1000</option>
					<option value="1-10">500 - 1000</option>
				</select>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-md-12">
		<div class="task_item">
			<label>Хто ваші клієнти</label>
			<div class="checkbox-div">
				<input type="checkbox">
				<label>Роздрібні покупці</label>
			</div>

			<div class="checkbox-div">
				<input type="checkbox">
				<label>Підприємці</label>
			</div>

			<div class="checkbox-div">
				<input type="checkbox">
				<label>Будівельники</label>
			</div>

			<div class="checkbox-div">
				<input type="checkbox">
				<label>Великі промислові об'єкти</label>
			</div>
			<!--			--><?php //if (!empty($company_types)): ?>
			<!--				<select class="form-control">-->
			<!--					--><?php //foreach ($company_types as $type): ?>
			<!--						<option value="--><? //= $type['id']; ?><!--">-->
			<? //= $type['name']; ?><!--</option>-->
			<!--					--><?php //endforeach; ?>
			<!--				</select>-->
			<!--			--><?php //endif; ?>
		</div>
	</div>

</div>

