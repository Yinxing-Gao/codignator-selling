<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<h2 class="text-center">Доступи <a href="https://www.youtube.com/embed/nrAiNXhKrMk"
								   class="play_video_instruction"><img src="../../img/yt.png"/></a></h2>
<div class="access_table_block">
	<table border="1px solid">
		<thead>
		<tr>
			<th></th>
			<?php if (!empty($accesses)) : ?>
				<?php foreach ($accesses as $access): ?>
					<th><?= $access['description']; ?></th>
				<?php endforeach; ?>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php if (!empty($professions)) : ?>
			<?php foreach ($professions as $profession): ?>
				<tr data-profession_id="<?=$profession['id']; ?>">
					<td><?= $profession['name']; ?></td>
					<?php if (!empty($accesses)) : ?>
						<?php foreach ($accesses as $access): ?>
							<td data-access_id="<?=$access['id']; ?>" >
								<input class="form-control access_checkbox" type="checkbox"
									   <?= in_array($access['id'], explode(',', $profession['access'])) ? 'checked' : ''; ?>/>
							</td>
						<?php endforeach; ?>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>


		</tbody>
	</table>
</div>
<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
