<div class="new_task_block">
    <form id="new_task_form">
        <p>Введіть нову задачу</p>
        <textarea class="form-control task_name" placeholder="назва*" name="task"></textarea>
        <textarea class="form-control task_description" placeholder="детальний опис" name="description"></textarea>
        <select class="form-control task_user_id" name="user_id">
            <option value="<?= $user->id; ?>"><?= $user->name . " " . $user->surname ?></option>
        </select>
        <input type="number" class="form-control task_statistics" placeholder="статистика" name="statistic">
        <div class="row">
            <div class="col-md-6"><input type="date" class="form-control task_date_to" name="date_to"></div>
            <div class="notify_div col-md-6"><input type="checkbox" class="form-control the_task_notify" name="notify" id="the_task_notify"><label for="the_task_notify">Сповістити</label></div>
        </div>
        <input type="submit" class="form-control btn btn-info add_new_task_btn" value="Відправити">
    </form>
</div>