<!--<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>-->
<style>
	.ck-editor__editable {
		min-height: 400px;
	}
</style>
<div id="job_instruction">
	<div class="row title_div">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h3 class="text-center">Посадові інструкції <?= !empty($position) ? $position->name : ''; ?></h3>
		</div>
		<div class="col-md-3 title_div_icons">
			<div class="title_div_icon save_instruction" data-title="Зберегти"
				 data-position_id="<?= !empty($position) ? $position->id : ''; ?>" data-instruction_id="<?= !empty($instruction) ? $instruction->id : ''; ?>">
				<img src="../../../icons/fineko/leads.svg"/>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<form>
				<textarea name="instruction"
						  id="instruction"><?= !empty($instruction) ? htmlspecialchars_decode($instruction->text) : ''; ?></textarea>
			</form>
		</div>
	</div>
</div>
