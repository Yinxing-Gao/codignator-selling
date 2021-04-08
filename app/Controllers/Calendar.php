<?php namespace App\Controllers;

use App\Models;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Calendar extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
            $tasksJson = array();
            $projectsJson = array();
            $tasks = Models\Tasks::get_tasks(['where' => [
                //'user_id = ' . $this->user_id,
                'status = "new"'
            ]]);

            $myProjects = Models\Projects::get_projects(['where' => [
                'fp.author_id = ' . $this->user_id
            ]]);

            $accountProjects = Models\Projects::get_projects(['where' => [
                'fp.account_id = ' . Models\Account::get_current_account_id()
            ]]);
            $observedProjects = [];
            if (!empty($accountProjects)) {
                foreach ($accountProjects as $project) {
                    if (!empty($project['observers_ids'])) {
                        $observerIdsArray = explode(',', $project['observers_ids']);
                        foreach ($observerIdsArray as $observerId) {
                            if ($observerId === $this->user_id) {
                                $observedProjects[] = $project;
                            }
                        }
                    }
                }
            }

            foreach ($tasks as $task){
                $taskObj = new \stdClass();
                $taskObj->title = $task["task"];
                $taskObj->start = date("Y-m-d", $task["date_to"]);
                $taskObj->end = date("Y-m-d H:i:s", $task["date_to"]);
                $tasksJson[] = $taskObj;
            }

            foreach ($myProjects as $project){
                $projectObj = new \stdClass();
                $projectObj->title = $project["name"];
                $projectObj->start = date("Y-m-d H:i:s", $project["date"]);
                $projectObj->end = date("Y-m-d H:i:s", $project["end_date"]);
                $projectsJson[] = $projectObj;
            }
            foreach ($observedProjects as $project){
                $projectObj = new \stdClass();
                $projectObj->title = $project["name"];
                $projectObj->start = date("Y-m-d H:i:s", $project["date"]);
                $projectObj->end = date("Y-m-d H:i:s", $project["end_date"]);
                $projectsJson[] = $projectObj;
            }
            $jsonData = array_merge($tasksJson, $projectsJson);
            $jsonData = json_encode($jsonData);
			return $this->view('calendar/index',
				[
					'jsonData' => $jsonData,
                    'title' => "Календар",
					'css' => ['calendar'],
					'js' => ['calendar']
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

    public function operations()
    {
        if (!empty($this->user_id)) {
            $operationsJson = array();
            $operations = Models\Operation::get_user_operations($this->user_id, ['where' => [
                'time_type = "plan"'
            ]]);

            foreach ($operations as $operation){
                $operationObj = new \stdClass();
                $operationObj->title = $operation["comment"];
                $operationObj->start = date("Y-m-d", $operation["planned_on"]);
                $operationObj->end = date("Y-m-d", $operation["planned_on"]);
                $operationsJson[] = $operationObj;
            }

            $jsonData = json_encode($operationsJson);
            return $this->view('calendar/index',
                [
                    'jsonData' => $jsonData,
                    'title' => "Календар платежів",
                    'css' => ['calendar'],
                    'js' => ['calendar']
                ]
            );
        } else {
            header('Location: ' . base_url() . 'user/login');
            exit;
        }
    }
}
