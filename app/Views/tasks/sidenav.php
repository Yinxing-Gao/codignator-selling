<div id="tasks_sidenav" class="sidenav">
    <a href="javascript:void(0)" class="close_nav_btn">
        <img src="../../icons/bootstrap/x.svg"/>
    </a>
    <h4 class="text-center title">Мої задачі</h4>
    <div class="text-center tasks_sidenav_btns">
        <a class="btn btn-info btn-dark" id="tasks_sidenav_mine" href="#my_tasks">Мої задачі</a>
        <a class="btn btn-info" id="tasks_sidenav_new" href="#new">Нова задача</a>
    </div>
    <div class="tasks_panels">
        <div id="panel_my_tasks" class="text-center panel">
            <?php
            include ("list.php");
            ?>
        </div>
        <div id="panel_new" class="panel">
            <?php
            include ("new_task_form.php");
            ?>
        </div>
    </div>
</div>
