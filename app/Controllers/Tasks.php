<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Tasks extends BaseController
{// додати можливість об"єднувати проекти

    public function add_task_ajax()
    {
        echo json_encode(array_merge(['date' => date('d.m.Y H:i')], Models\Tasks::add([
            'lead_id' => 0,
            'user_id' => !empty($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $this->user->id,
            'author_id' => $this->user->id,
            'account_id' => $this->user->account_id,
            'task' => $_REQUEST['task'],
            'comment' => !empty($_REQUEST['description']) ? $_REQUEST['description'] : '',
            'date' => time(),
            'date_to' => !empty($_REQUEST['date_to']) ? strtotime($_REQUEST['date_to']) : strtotime("tomorrow") - 1,
            'notify' => !empty($_REQUEST['notify']) ? 1 : 0,
            'statistics' => !empty($_REQUEST['statistic']) ? $_REQUEST['statistic'] : 0
        ])));
    }
}
