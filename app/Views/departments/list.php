<div id="department_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Департаменти</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?=base_url();?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_department_sidenav" data-title="Додати департамент">
				<img src="<?=base_url();?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<th>#</th>
					<th>Департамент</th>
					<!--					<th>Головний ( так/ні )</th>-->
					<th>Модулі</th>
					<th>Відображається</th>
					<th></th>
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($tree)): ?>
					<?php echo_branch(0, $tree, 0, $branch_data); ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="department_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="department_sidenav_content sidenav_content">
		<h4>Додати/редагувати</h4>
		<form>
			<input type="hidden" class="form-control" name="account_id"
				   value="<?= $account_id; ?>">
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Назва *</label>
					<input type="text" class="form-control" name="name" placeholder="Ім'я"
						   required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Батьківський департамент</label>

					<select class="form-control" name="parent_department_id" required="">
						<option value=""></option>

						<?php if (!empty($departments)): ?>
							<?php foreach ($departments as $department): ?>
								<option value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="address">
						Модулі</label>
					<select class="form-control" name="modules[]" id="modules" required="" multiple>

						<?php if (!empty($modules)): ?>
							<?php foreach ($modules as $module): ?>
								<option value="<?= $module['id']; ?>"><?= $module['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="is_shown">
						<input type="checkbox" class="form-control" id="is_shown" name="is_shown">
						Відображати в меню</label>
				</div>

				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_department" type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
function echo_branch($parent_id, $tree, $level, $branch_data)
{
	$tab = '<img src="../../../icons/bootstrap/dot.svg" />';
	$modules = $branch_data['modules'];
	foreach ($tree as $department): ?>
		<tr data-department_id="<?= $department['id']; ?>" data-parent_id="<?= $parent_id; ?>">
			<td data-label="#"><?= $department['id']; ?><!--</td>-->
			<td data-label="Назва">
				<?php for ($i = 0; $i < $level; $i++):
					echo $tab;
				endfor;
				echo $department['name']; ?>
			</td>
			<!--							<td>--><? //= $department['type']; ?><!--</td>-->
			<td data-label="Модулі" data-modules="<?= $department['modules']; ?>">
				<select class="form-control" name="modules" required="" multiple>
					<?php if (!empty($modules)): ?>
						<?php foreach ($modules as $module): ?>
							<option
								<?= in_array($module['id'], explode(',', $department['modules'])) ? 'selected' : '' ?>
								value="<?= $module['id']; ?>"><?= $module['name']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</td>
			<td data-label="Відображається">
				<input class="form-control is_shown" type="checkbox"/>
			</td>
			<td>
				<a href="access/index/<?= $department['id']; ?>" class="icon" data-title="Доступи депертаменту">
					<img class="department_access" src="<?=base_url(); ?>/icons/fineko/accesses.svg"/>
				</a>
				<div class="icon edit_department" data-title="Редагувати департамент">
					<img src="<?=base_url(); ?>/icons/fineko/edit.svg"/>
				</div>
				<?php if (empty($department['is_default'])): ?>
				<div class="icon delete_department" data-title="Видалити департамент">
					<img class="" src="<?=base_url(); ?>/icons/fineko/delete.svg"/>
				</div>
				<?php endif; ?>
			</td>
		</tr>
		<?php if (!empty($department['children'])): ?>
			<?php echo_branch($department['id'], $department['children'], $level + 1, $branch_data); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>
