<?php include_once 'head.php' ?>
<?php include_once 'header.php' ?>
<?php
$username = !empty($user) ? $user['name'] . ' ' . $user['surname'] : '';
?>


<div class="container" id="container_operation_upload">
	<span>Тут можна завантажити ваші операції одним файлом Excel</span>
	<input type="file" class="form-control" />

	<span>Скачати шаблон можна <a href="#" >тут</a></span>

</div>

<?php include_once 'popup.php' ?>
<?php include_once 'footer.php' ?>
