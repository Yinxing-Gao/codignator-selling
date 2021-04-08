<div id="question_groups">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Запитання для клієнтів</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="../../../icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_question_group_sidenav" data-title="Додати групу запитань">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Блок</th>
					<th scope="col">Запитання</th>
					<th scope="col"></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($question_groups)): ?>
					<?php foreach ($question_groups as $question_group): ?>
						<tr data-contract_id="<?= $question_group['id']; ?>">
							<td data-label="Блок"><?= $question_group['name']; ?></td>
							<td data-label="Запитання">
								<?php if (!empty($question_group['questions'])): ?>
									<?php foreach ($question_group['questions'] as $question): ?>
										<?= $question['question']; ?><br/>
									<?php endforeach; ?>
								<?php endif; ?>
							</td>
							<td>
								<div class="icon edit_question_group" data-title="Редагувати блок запитань">
									<img src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
								</div>
								<div class="icon delete_question_group" data-title="Видалити блок запитань">
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

<div id="question_group_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="../../icons/bootstrap/x.svg"/>
	</a>

	<div class="question_group_sidenav_content sidenav_content">
		<form class="contractor_form">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Назва *</label>
					<input type="text" class="form-control" name="number" placeholder="Ім'я"
						   required="">
				</div>
				<div class="col-xs-12 col-md-12 element">
					<label for="username">Запитання</label>
					<textarea style="height: 100px" class="form-control" name="comment"
							  required=""></textarea>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_contract" data-action="add"
							type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>


