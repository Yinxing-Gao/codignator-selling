<?php
$base_url = (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME']; ?>

</div>
<span class="notification_block"></span>
<footer style="height: 100px">

</footer>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="<?= $base_url; ?>/js/jquery-3.4.1.min.js"></script>
<script src="<?= $base_url; ?>/js/main.js?v=1.1"></script>
<script src="<?= $base_url; ?>/js/select2.min.js?v=1.1"></script>
<script src="<?= $base_url; ?>/js/jquery.mobile-1.4.5.min.js?v=1.1"></script>
<!--<script src="--><? //= $base_url; ?><!--/js/jqueryui.custom.js"></script>-->
<!--<script src="--><? //= $base_url; ?><!--/js/jquery.ui.touch-punch.min.js"></script>-->
<!--<script src="--><? //= $base_url; ?><!--/js/fastclick.js"></script>-->
<script src="<?= $base_url; ?>/js/sidenav/operation_sidenav.js?v=1.2"></script>
<script src="<?= $base_url; ?>/js/sidenav/project_sidenav.js?v=1.1"></script>
<script src="<?= $base_url; ?>/js/sidenav/tasks_sidenav.js?v=1.0"></script>
<script src="<?= $base_url; ?>/js/chat.js?v=1.1"></script>
<script type="text/javascript" src="<?= $base_url; ?>/js/jquery.tablesorter.js"></script>
<?php if (!empty($js)) : ?>
	<?php foreach ($js as $js_file): ?>
		<script type="text/javascript" src="<?= $base_url; ?>/js/<?= $js_file; ?>.js?v=1.1"></script>
	<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
