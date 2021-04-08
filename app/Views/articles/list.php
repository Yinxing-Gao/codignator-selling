<div id="article_list">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Статті</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/nrAiNXhKrMk" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?= base_url(); ?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_article_sidenav" data-title="Додати статтю">
				<img src="<?= base_url(); ?>/icons/fineko/leads.svg"/>
			</div>
			<div class="title_div_icon" data-title="Шаблони">
				<a href="<?= base_url(); ?>articles/templates">
					<img src="<?= base_url(); ?>/icons/fineko/leads.svg"/>
				</a>
			</div>
		</div>
	</div>

	<div class="row">
		<!--		<div class="col-md-1">-->
		<!--			<a class="btn btn-info form-control" id="go_to_templates" href="/articles/templates">Шаблони</a>-->
		<!--		</div>-->
		<div class="col-md-2">
			<select class="form-control" id="department_id" required="">
				<?php if (!empty($departments)): ?>
					<?php foreach ($departments as $department): ?>
						<option <?= $department_id == $department['id'] ? 'selected' : ''; ?>
								value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
		</div>
		<div class="col-md-9"></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter">
				<thead class="thead-dark">
				<tr>
					<!--					<th scope="col">#</th>-->
					<th scope="col">Назва</th>
					<th scope="col">Тип</th>
					<th scope="col">Опис</th>
					<th scope="col">Період</th>
					<th scope="col">Відображається</th>
					<!--					<th></th>-->
					<th></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($tree['income'])): ?>
					<?php echo_branch(0, $tree['income'], 0); ?>
				<?php endif; ?>
				<tr class="divider">
					<td colspan="6"></td>
				</tr>
				<?php if (!empty($tree['expense'])): ?>
					<?php echo_branch(0, $tree['expense'], 0); ?>
				<?php endif; ?>

				</tbody>
			</table>
		</div>

	</div>
</div>

<div id="article_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="article_sidenav_content sidenav_content">
		<h4>Додати статтю</h4>
		<form>
			<div class="row">
				<div class="col-xs-12 col-md-12">
					<label for="name">
						Назва *</label>
					<input type="text" class="form-control" name="name" placeholder="Назва"
						   required="">
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Тип</label>

					<select class="form-control" name="type" required="">
						<optgroup label="Операції">
							<option value="expense">Витрата</option>
							<option value="income">Дохід</option>
						</optgroup>
						<optgroup label="Нарахування">
							<option value="income">Дебет</option>
							<option value="expense">Кредит</option>
						</optgroup>
					</select>
				</div>


				<div class="col-xs-12 col-md-12">
					<label for="">
						Батьківська стаття</label>

					<select class="form-control" name="parents_item_id" required="">
						<option value=""></option>
						<?php if (!empty($department_articles)): ?>
							<?php foreach ($department_articles as $article): ?>
								<option value="<?= $article['id']; ?>"><?= $article['name']; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="description">
						Опис</label>
					<textarea style="height: 50px" class="form-control" id="description" name="description"
							  placeholder="" required=""></textarea>
				</div>

				<div class="col-xs-12 col-md-12">
					<label for="">
						Період</label>

					<select class="form-control" name="period" required="">
						<option value="month">Місяць</option>
						<option value="project">Проект</option>
					</select>
				</div>
				<div class="col-md-12">
					<label for=""></label>
					<button class="btn btn-primary btn-lg btn-block btn_add_article" type="submit">Відправити
					</button>
				</div>
			</div>
		</form>
	</div>
</div>


<?php
function echo_branch($parent_id, $tree, $level)
{
	$style = [
		'income' => 'green',
		'expense' => 'red'
	];

	$tab = '<img src="' . base_url() . '/icons/bootstrap/dot.svg" />';
	foreach ($tree as $article): ?>
		<tr data-article_id="<?= $article['id']; ?>" data-parent_id="<?= $parent_id; ?>"
			data-type="<?= $article['type']; ?>">
			<!--			<td>--><? //= $article['id']; ?><!--</td>-->
			<td data-label="Назва">
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>
				<?= $article['name']; ?>
			</td>
			<td data-label="Тип" style="color:<?= $style[$article['type']]; ?>"><?= $article['type']; ?></td>
			<td data-label="Опис"><?= $article['description']; ?></td>
			<td data-label="Період"><?= $article['period']; ?></td>
			<td data-label="Відображається">
				<input type="checkbox" class="form-control is_shown"
					   <?= $article['is_shown'] ? 'checked' : ''; ?>/>
			</td>
			<td>
				<?php if (empty($article['is_base'])): ?>
					<a href="/articles/tree/<?= $article['id']; ?>" class="open_tree icon"
					   data-title="Дерево підстаттей">
						<img src="<?= base_url(); ?>/icons/fineko/article%20tree.svg"/>
					</a>
				<?php endif; ?>
				<div class="icon edit_article" data-title="Редагувати статтю">
					<img class="delete_article" src="<?= base_url(); ?>/icons/fineko/edit.svg"/>
				</div>
				<div class="icon delete_article" data-title="Видалити статтю">
					<img class="delete_article" src="<?= base_url(); ?>/icons/fineko/delete.svg"/>
				</div>
			</td>
		</tr>
		<?php if (!empty($article['children'])): ?>
			<?php echo_branch($article['id'], $article['children'], $level + 1); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>



