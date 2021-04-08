<?php namespace App\Controllers;

use App\Models;
use App\Models\DBHelp;
use Config;
use CodeIgniter\HTTP\RedirectResponse;


class Production extends BaseController
{
	public function index()
	{
		if (!empty($this->user_id)) {
			return view('production',
				[
					'user' => $this->user,
					'access' => $this->access,
					'projects' => Models\Projects::get_department_projects_in_work(1),
					'js' => [
						'production',
						'query-ui'
					],
					'css' => [
						'production',
						'jquery-ui'
					]
				]
			);
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}
//
//	public function specification_new($specification_id = 0)
//	{
//		if (!empty($this->user_id)) {
//			if ($specification_id == 0) {
//				return $this->view('production/products_list',
//					[
//						'user' => $this->user,
//						'access' => $this->access,
//						'products' => Models\Production::get_production_products_with_parts(),
//						'spec_items_counts' => Models\Production::get_specification_items_count()
//					]
//				);
//			} else {
//				return $this->view('production/specification_new',
//					[
//						'user' => $this->user,
//						'access' => $this->access,
//						'storages' => Models\Storage::get_storages(/*['where' => ['account_id = ' . $this->account_id]]*/),
//						'names' => Models\Production::get_specification($specification_id),
//						'product' => Models\Products::get_product_name($specification_id),
//						'storage' => Models\Storage::get_storage_names(1),
//						'specifications' => Models\Production::get_product_specifications($specification_id),
//						'js' => ['production'],
//						'css' => ['production']
//					]
//				);
//			}
//		} else {
//			header('Location: ' . base_url() . 'user/login');
//			exit;
//		}
//	}

	public function specification($specification_id = 0)
	{
		if (!empty($this->user_id)) {
			if ($specification_id == 0) {
				return $this->view('production/products_list',
					[
						'user' => $this->user,
						'access' => $this->access,
						'products' => Models\Production::get_production_products_with_parts(),
						'spec_items_counts' => Models\Production::get_specification_items_count()
					]
				);
			} else {
				$specifications = Models\Production::get_specifications(['where' => [
					'account_id = ' . Models\Account::get_current_account_id()
				]]);

				$storages = Models\Storage::get_storages([
                    'where' => [
                        'account_id = ' . Models\Account::get_current_account_id(),
                        'type = "materials"'
                    ],
                ]);


				$storage_names = [];
				foreach ($storages as $storage){
				    $storage_names[$storage['id']] = Models\Storage::get_storage_names([
				        'where' => [
                            'storage_id = ' . $storage['id']
                        ]
                    ]);
                }

				$parents = Models\Production::get_specification_parents($specification_id);

				return $this->view('production/specification',
					[
						'user' => $this->user,
						'access' => $this->access,
						'storage_names' => $storage_names,
						'storages' => $storages,
                        'parents' => array_reverse($parents),

//						'product' => Models\Products::get_product_name($production_product_id),
//						'blocks' => Models\Production::get_machine_blocks(),
//						'storage' => Models\Storage::get_storage_names(1),
						'this_specification' => Models\Production::get_specification(['where' => [
							'fs.id = ' . $specification_id
						]], [], ['fin_specification_items']),
						'specifications' => $specifications,
						'js' => ['upload', 'production'],
						'css' => ['production']
					]
				);
			}
		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function process($project_id = 0)
	{
		if (!empty($this->user_id)) {
			$project = Models\Projects::get_project($project_id);
			$items = Models\Production::get_specification_with_amounts($project->products_id);
			$project_items = Models\Projects::get_project_items($project_id);
			$blocks = Models\Production::get_machine_blocks();
			$blocks[] = ['id' => 0, "name" => "Інше"];

			$result_project_items = [];
			$project_block_ids = [];
			$project_ids = [];
			foreach ($project_items as $project_item) {
				foreach ($blocks as $block) {
					if ($project_item['block_id'] == $block['id']) {
						$result_project_items[$block['id']]['items'][] = $project_item;
						$result_project_items[$block['id']]['name'] = $block['name'];
					}
				}
				if ($project_item['amount'] == $project_item['spec_amount']) {
					$project_ids[$project_item['block_id'] . '_' . $project_item['storage_name_id']] = $project_item['amount'];
				}
			}

			$result_items = [];
			$missing = [];
			foreach ($items as $item) {
				$missing[$item['storage_name_id']] = 0;
				$key = $item['block_id'] . '_' . $item['storage_name_id'];
				if (($item['amount'] > $item['storage_amount'] && !key_exists($key, $project_ids)) ||
					(key_exists($key, $project_ids) && $project_ids[$key] < $item['amount'])) {
					$missing[$item['storage_name_id']] += $item['amount'];
				}
				foreach ($blocks as $block) {
					if ($item['block_id'] == $block['id'] && !key_exists($key, $project_ids)) {
						$result_items[$block['id']]['items'][] = $item;
						$result_items[$block['id']]['name'] = $block['name'];
					}
				}
			}

			$missing_items = array_filter($missing, function ($element) {
				return !empty($element);
			});

			return view('production_process',
				[
					'user' => $this->user,
					'access' => $this->access,
					'project' => $project,
					'project_items' => $result_project_items,
					'project_items_id' => $project_ids,
					'progress' => (int)(count($project_items) / count($items) * 100),
					'items' => $result_items,
//					'block_ids' => $block_ids,
					'missing_items' => $missing_items,
					'project_block_ids' => $project_block_ids,
					'js' => [
						'production',
						'query-ui'
					],
					'css' => [
						'production',
						'jquery-ui'
					]
				]
			);

		} else {
			header('Location: ' . base_url() . 'user/login');
			exit;
		}
	}

	public function change_block_ajax($item_id)
	{
		if (!empty($_POST)) {
			$block_id = $_POST['block_id'];
			echo json_encode(Models\Production::change_block_in_specification($item_id, $block_id));
		}
	}

	public function move_to_project_ajax($project_id)
	{
		if (!empty($_POST)) {
			$specification_items_ids = $_POST['ids'];
			$storage_id = 1; // склад виробництва, якщо специфікаії будуть ще в якомусь департаменті - переписати
			echo json_encode(Models\Production::move_to_project($storage_id, $project_id, $specification_items_ids));
		}
	}

	public function change_amount_ajax($item_id)
	{
		if (!empty($_POST)) {
			$amount = $_POST['amount'];
			echo json_encode(Models\Production::change_amount_in_specification_item($item_id, $amount));
		}
	}

    public function change_specification_name_ajax($specification_id)
    {
        if (!empty($_POST)) {
            $name = $_POST['name'];
            echo json_encode(Models\Production::change_name_in_specification($specification_id, $name));
        }
    }

	public function delete_item_from_specification_ajax($item_id)
	{
		echo json_encode(Models\Production::delete_specification_item($item_id));
	}

//	public function to_spec_ajax()
//	{
//
//		if (!empty($_POST)) {
//			$atts = $_POST;
//			$id_arr = json_decode($atts['id_arr']);
//			$product_id = $atts['product_id'];
////			Models\Applications::send_application_mail($id_arr);
//			echo json_encode(Models\Production::add_to_specification($id_arr, $product_id));
//		}
//	}

	public function copy_spec_ajax()
	{

		if (!empty($_POST)) {
			$from_id = $_POST['from_id'];
			$to_id = $_POST['to_id'];
			echo json_encode(Models\Production::copy_specification($from_id, $to_id));
		}
	}

	public function generate_app_from_spec_ajax($project_id)
	{
		if (!empty($_POST)) {
			$missing = $_POST['missing'];
			echo json_encode(Models\Production::generate_app_from_spec($missing, $this->user_id, $project_id));
		}
	}

	public function open_specification_ajax($project_id)
	{
		if (!empty($project_id)) {
			$project = Models\Projects::get_project(['where' => ['fp.id = ' . $project_id]]);
			if (!empty($project['result']->specification_id)) {
				echo json_encode([
					'status' => 'ok',
					'link' => '/production/specification/' . $project['result']->specification_id
				]);
			} else {
				$new_spec = Models\Production::add_specification([
					'name' => $project['result']->name,
					'comment' => 'Специфікація під проект ' . $project['result']->name,
					'is_virtual' => 1
				]);
				Models\Projects::update($project['result']->id, [
					'specification_id' => $new_spec['id']
				]);

				echo json_encode([
					'status' => 'ok',
					'link' => '/production/specification/' . $new_spec['id']
				]);
			}
		}
		die();
	}

	public function upload_spec_ajax($specification_id)
	{
		if (isset($_POST['spec'])) {
			$dir = './uploads/excel/spec';
			$uploaded = Models\DirFiles::upload_files($dir);
			if ($uploaded['status'] == 'ok') {
				$spec_file = Models\DirFiles::scan_dir($dir)[0];
				$get_mime = explode('.', $spec_file);
				$ext = end($get_mime);
				if ($ext == 'xlsx') {
					echo json_encode(Models\Excel::upload_spec($dir . '/' . $spec_file, $specification_id));
				} else {
					echo json_encode(['status' => 'error', 'message' => 'Неправильне розширення файлу']);
				}
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Не вдалося завантажити файл']);
			}
		}
	}

    /**
     * @param $specification_id
     *
     * Створює запис в fin_specification_items і повертає його для виводу в таблиці
     */
	public function add_specification_item_ajax($specification_id){
	    if((isset($_POST['subspecification_id']) && $_POST['type'] == 'specification')
        || (isset($_POST['storage_name_id']) && $_POST['type'] == 'storage_name'))
	    {
	        $result = Models\Production::add_update_specification_item($specification_id, $_POST);
	        if($result['status'] == "ok"){
	            if($_POST['type'] == 'specification'){
                    $result['new_record'] = Models\Production::get_subspecification_item($result['id'])[0];
                }
	            elseif($_POST['type'] == 'storage_name'){
                    $result['new_record'] = Models\Production::get_storage_name_subspecification_item($result['id'])[0];
                }
            }
            echo json_encode($result);
        }
	    else{
            echo ("Wrong parameters");
        }
    }

    public function add_new_subspecification_ajax($specification_id){
	    $new_specification = Models\Production::add_specification(['name' => "Нова специфікація"]);
	    if($new_specification['status'] == "ok"){
            $new_specification_item = Models\Production::add_update_specification_item($specification_id, [
                'subspecification_id' => $new_specification['id'],
                'type' => "specification",
                "amount" => 1,
                'is_virtual' => 1
            ]);
            if($new_specification_item['status'] == "ok"){
                echo json_encode($new_specification);
            }
        }
    }
}
