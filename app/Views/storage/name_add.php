<?php
$username = !empty($user) ? $user->name . ' ' . $user->surname : '';
?>


<div class="container" id="storage_item_add">
	<form class="needs-validation">
		<input type="hidden" name="user_id" value="<?= $user->id; ?>"/>
		<input type="hidden" name="storage_id" value="<?= $storage->id; ?>"/>
		<div class="py-5 text-center">
			<!--		<img class="d-block mx-auto mb-4"-->
			<!--			 src="https://ekonombud.in.ua/wp-content/uploads/2019/06/logo_blue-black-1_page-0001.jpg"-->
			<!--			 width="300">-->
			<h2>Додати найменування<a href="#" class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>

		</div>
		<div class="row">
			<div class="col-md-6">
				<form class="needs-validation">
					<div class="row">
						<div class="col-md-8">
							<label for="department">Назва</label>
							<input class="form-control" id="item" name="name" required="">
						</div>
						<div class="col-md-4">
							<label for="item">Одиниця вимірювання</label>
							<select class="form-control" id="units" name="unit_id" required="">
								<option value=""></option>
								<?php if (!empty($units)): ?>
									<?php foreach ($units as $unit): ?>
										<option value="<?= $unit['id']; ?>"><?= $unit['name']; ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
						<div class="col-md-12">
							<label for=""></label>
							<button class="btn btn-primary btn-lg btn-block btn_add_one_storage_name" type="submit">
								Відправити
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<form class="needs-validation">
					<div class="row">

						<div class="col-md-12">
							<label for="project">Додати оптом</label>
							<textarea style="height: 200px" class="form-control" id="data" name="data"
									  placeholder="позиція, шт
позиція2, шт
позиція3, гр
позиція4, м
позиція5, гр "
									  required=""></textarea>
						</div>
						<div class="col-md-12">
							<label for=""></label>
							<button class="btn btn-primary btn-lg btn-block btn_add_plural_storage_names" type="submit">
								Відправити
							</button>
						</div>

					</div>
				</form>
			</div>
		</div>

</div>

