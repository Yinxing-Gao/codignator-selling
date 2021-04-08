<html lang="en">
<head>
	<script>
		if (screen.width <= 480) {
			document.write('<meta id="myviewport" name="viewport" content="width=480, user-scalable=0">');
		} else {
			document.write('<meta id="myviewport" name="viewport" content="width=device-width, user-scalable=0">');
		}
	</script>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" type="image/x-icon"  href="<?=base_url();?>icons/favicon.ico">

	<title><?= !empty($title) ? $title . ' | ' : ''; ?>FINEKO - система фінансового управління для малого і середнього
		бізнесу</title>

	<link rel="stylesheet" href="<?= base_url(); ?>/css/libraries/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url(); ?>/css/fonts.css?v=1.2">
	<link rel="stylesheet" href="<?= base_url(); ?>/css/libraries/jqueryui.custom.css">
	<link rel="stylesheet" href="<?= base_url(); ?>/css/main.css?v=1.2">
<!--	<link rel="stylesheet" href="--><?//= base_url(); ?><!--/css/humburger.css?v=1.2">-->
	<link rel="stylesheet" href="<?= base_url(); ?>/css/main-mobile.css?v=1.1">
	<link rel="stylesheet" href="<?= base_url(); ?>/css/libraries/select2_injected.min.css?v=1.3">
	<?php if (!empty($css)) : ?>
		<?php foreach ($css as $css_file): ?>
			<link rel="stylesheet" href="<?= base_url(); ?>/css/<?= $css_file; ?>.css?v=1.1"/>
		<?php endforeach; ?>
	<?php endif; ?>
</head>
<body class="bg-light">

