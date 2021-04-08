<div id="article_templates">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Шаблони статтей</h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<a href="https://www.youtube.com/embed/zcbBAuG3Xls" class="play_video_instruction title_div_icon"
			   data-title="Відео інструкція">
				<img src="<?=base_url(); ?>/icons/fineko/video.svg"/>
			</a>
			<div class="title_div_icon open_article_templates_sidenav" data-title="Додати шаблони до департаменту">
				<img src="<?=base_url(); ?>/icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<table class="table tablesorter templates_table">
				<thead class="thead-dark">
				<tr>
					<th scope="col">Назва</th>
					<th scope="col">Тип</th>
					<th scope="col">Опис</th>
					<th scope="col">Період</th>
					<th scope="col">Стаття начислення</th>
					<th><input type="checkbox" class="form-control" id="select_all_articles"/></th>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($template_tree['income'])): ?>
					<?php echo_branch(0, $template_tree['income'], 0, $article_types_lang); ?>
				<?php endif; ?>
				<tr class="divider">
					<td colspan="6"></td>
				</tr>
				<?php if (!empty($template_tree['expense'])): ?>
					<?php echo_branch(0, $template_tree['expense'], 0, $article_types_lang); ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>

	</div>
</div>


<div id="article_templates_sidenav" class="sidenav">
	<a href="javascript:void(0)" class="close_nav_btn">
		<img src="<?= base_url(); ?>/icons/bootstrap/x.svg"/>
	</a>

	<div class="article_templates_sidenav_content sidenav_content">
	<form>
		<div class="row">
			<div class="col-md-12">
				<label for="department">Департамент*</label>
				<select class="form-control" name="department_id">
					<?php if (!empty($departments)): ?>
						<?php foreach ($departments as $department): ?>
							<option
								<?= $department['id'] == $department_id ? 'selected' : '' ?>
								value="<?= $department['id']; ?>"><?= $department['name']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="col-md-12">
				<label for=""></label>
				<button class="btn btn-primary btn-lg btn-block add_articles_from_template" type="submit">Додати
					до обраного департаменту
				</button>
			</div>
		</div>
	</form>
</div>

<?php
function echo_branch($parent_id, $tree, $level, $article_types_lang)
{
	$style = [
		'income' => 'green',
		'expense' => 'red'
	];

	$tab = '<img src="'.base_url() .'/icons/bootstrap/dot.svg" />';
	foreach ($tree as $article): ?>
		<tr data-article_id="<?= $article['id']; ?>" data-parent_id="<?= $parent_id; ?>">
			<!--			<td>--><? //= $article['id']; ?><!--</td>-->
			<td data-label="Назва">
				<?php for ($i = 0; $i < $level; $i++): ?>
					<?= $tab; ?>
				<?php endfor; ?>
				<?= $article['name']; ?>
			</td>
			<td data-label="Тип" style="color:<?= $style[$article['type']]; ?>"><?= $article_types_lang[$article['type']]; ?></td>
			<td data-label="Опис" ><?= $article['description']; ?></td>
			<td data-label="Період"><?= $article['period']; ?></td>
			<td data-label="">
				<input type="checkbox" class="form-control check"/>
			</td>
		</tr>
		<?php if (!empty($article['children'])): ?>
			<?php echo_branch($article['id'], $article['children'], $level + 1, $article_types_lang); ?>
		<?php endif; ?>
	<?php endforeach;
} ?>


