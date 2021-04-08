<?php if (!empty($js)) : ?>
	<?php foreach ($js as $js_file): ?>
		<script type="text/javascript" src="<?= base_url() ?>/js/<?= $js_file; ?>.js?v=1.1"></script>
	<?php endforeach; ?>
<?php endif; ?>

