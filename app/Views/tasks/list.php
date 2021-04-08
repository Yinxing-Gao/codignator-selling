<?php if (!empty($sidenav_tasks)): ?>
    <?php foreach ($sidenav_tasks as $task): ?>

        <div class="task <?= $task['date_to'] < time() ? "expired" : '' ?>">
            <div class=" task_actions">
                <img src="../../../icons/bootstrap/pencil.svg" class="edit_task"/>
                <img src="../../../icons/bootstrap/check-square.svg"
                     class="finish_task"/>
            </div>
            <b><?= date('d.m.Y H:i', $task['date_to']); ?></b>
            <?= $task['task']; ?>
        </div>

    <?php endforeach; ?>
<?php endif; ?>