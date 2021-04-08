<?php

use App\Models\Departments;
use App\Models\Modules;

$locale = !empty($locale) ? $locale : 'ru';
$menu = [
	['name' => lang_('Menu.operations', $locale), 'link' => '/operation', 'id' => 'operation', 'access' => 'can_see_operations', 'children' => [
		['name' => lang_('Menu.add expense', $locale), 'link' => '/operation/add_expenses', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense'],
		['name' => lang_('Menu.add income', $locale), 'link' => '/operation/add_income', 'id' => 'operation_add_income', 'access' => 'can_add_income'],
		['name' => lang_('Menu.add transfer', $locale), 'link' => '/operation/add_transfer', 'id' => 'operation_add_transfer', 'access' => 'can_add_transfer'],
		['name' => lang_('Menu.my operations', $locale), 'link' => '/operation', 'id' => 'operation', 'access' => 'can_see_self_operations'],
		['name' => lang_('Menu.my wallets', $locale), 'link' => '/wallet', 'id' => 'wallet', 'access' => 'can_see_self_wallets'],
		['name' => lang_('Menu.department operations', $locale), 'link' => '/operation/list', 'id' => '', 'access' => 'can_see_department_operations'],
//		['name' => 'Операції директора', 'link' => '#', 'id' => '', 'access' => 'can_see_director_operations'],
		['name' => lang_('Menu.update data', $locale), 'link' => '/operation/update_later_operations', 'id' => '', 'access' => 'can_refresh_operations'],
		['name' => lang_('Menu.charts', $locale), 'link' => '/operation/charts', 'id' => 'operation_charts', 'access' => 'can_see_operation_charts'],
		['name' => lang_('Menu.credits', $locale), 'link' => '/credit', 'id' => 'credits', 'access' => 'can_see_credits'],
	]],
	['name' => lang_('Menu.plan operations', $locale), 'link' => '/operation/plan', 'id' => 'accruals', 'access' => 'can_see_operations', 'children' => [
		['name' => "шаблони операцій", 'link' => '/operation/templates', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense'],
		['name' => "мої шаблони операцій", 'link' => '#', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense'],
		['name' => "планування по статтях", 'link' => '#', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense']
	]],
	['name' => lang_('Menu.applications', $locale), 'link' => '/application/', 'id' => 'application', 'access' => 'can_see_applications', 'children' => [
		['name' => lang_('Menu.add app', $locale), 'link' => '/application/add', 'id' => 'application_add', 'access' => 'can_add_applications'],
		['name' => lang_('Menu.my apps', $locale), 'link' => '/application', 'id' => 'application', 'access' => 'can_see_self_applications'],
		['name' => lang_('Menu.department apps', $locale), 'link' => '/application/department', 'id' => 'application_all', 'access' => 'can_see_department_applications'],
		['name' => lang_('Menu.department approved', $locale), 'link' => '/application/department/approved', 'id' => 'application_approved', 'access' => 'can_see_department_applications_approved'],
		['name' => lang_('Menu.department payed', $locale), 'link' => '/application/department/payed', 'id' => 'application_payed', 'access' => 'can_see_department_applications_payed'],
		['name' => lang_('Menu.to pay on TOV', $locale), 'link' => '/application/approved_tov', 'id' => 'application_approved_tov', 'access' => 'can_see_application_approved_tov'],
//		['name' => lang_('Menu.update statuses', $locale), 'link' => '/application/refresh', 'id' => 'application_refresh', 'access' => 'can_refresh_applications'],
//		['name' => lang_('Menu.charts', $locale), 'link' => '/application/charts', 'id' => 'application_charts', 'access' => 'can_view_application_charts'],
	]],
	['name' => lang_('Menu.accruals', $locale), 'link' => '#', 'id' => 'accruals', 'access' => 'can_see_operations', 'children' => [
		['name' => lang_('Menu.add expense', $locale), 'link' => '/operation/add_expenses', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense']
	]],
	['name' => 'Відділ продаж', 'link' => '/accruals', 'id' => 'accruals', 'access' => 'can_see_operations', 'children' => [
		['name' => 'Відділ продаж', 'link' => '/sales', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense'],
//		['name' => lang_('Menu.add expense', $locale), 'link' => '/operation/add_expenses', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense']
	]]];

$menu_departments = Departments::get_departments(['where' => ['is_shown = 1']]);

if (!empty($menu_departments)) {
	foreach ($menu_departments as $menu_department) {
		$menu_item = [
			'name' => lang_('Menu.' . $menu_department['name'], $locale),//todo замінити на прив'язані до акаунтів
			'link' => '#',
			'id' => $menu_department['name'] . '_department',
//			'access' => 'can_see_' . $menu_department['name']
			'access' => 'can_see_production'
		];

		$menu_modules = Modules::get_modules(['where' => ['id IN (' . $menu_department['modules'] . ')']]);
		if (!empty($menu_modules)) {
			foreach ($menu_modules as $menu_module) {
				$menu_sub_item = [
					'name' => lang_('Menu.' . $menu_module['name'], $locale),//todo замінити на прив'язані до акаунтів
					'link' => $menu_module['link'] . '/department/' . $menu_department['id'],
					'id' => $menu_department['name'] . '_' . $menu_department['id'],
//					'access' => 'can_see_' . $menu_department['name']
					'access' => 'can_see_production'
				];
				$menu_item['children'][] = $menu_sub_item;
			}
		}
		$menu[] = $menu_item;
	}
}

//\App\Models\Dev::var_dump($menu);
//['name' => lang_('Menu.production', $locale), 'link' => '/production', 'id' => 'production_department', 'access' => 'can_see_production', 'children' => [
//	['name' => lang_('Menu.project add', $locale), 'link' => '/project/add/1', 'id' => 'production_project_add', 'access' => 'can_add_production_projects'],
//	['name' => lang_('Menu.projects', $locale), 'link' => '/project/department/1', 'id' => 'production', 'access' => 'can_see_production_projects'],
//	['name' => lang_('Menu.specifications', $locale), 'link' => '/production/specification', 'id' => 'specification', 'access' => 'can_see_production_specification'],
//	['name' => lang_('Menu.storage', $locale), 'link' => '/storage/department/1', 'id' => 'production_storage', 'access' => 'can_see_production_storage'],
//	['name' => lang_('Menu.edit prices', $locale), 'link' => '/price/edit/1', 'id' => 'production_price_edit', 'access' => 'can_edit_production_prices'],
//]],
//	['name' => lang_('Menu.services', $locale), 'link' => '#', 'id' => 'service_department', 'access' => 'can_see_service_department', 'children' => [
//		['name' => lang_('Menu.project add', $locale), 'link' => '/project/add/8', 'id' => 'service_project_add', 'access' => 'can_add_service_projects'],
//		['name' => lang_('Menu.projects', $locale), 'link' => '/project/department/8', 'id' => 'service_projects', 'access' => 'can_see_service_projects'],
//		['name' => lang_('Menu.storage', $locale), 'link' => '/storage/department/8', 'id' => 'service_storages', 'access' => 'can_see_service_storages'],
//		['name' => lang_('Menu.edit prices', $locale), 'link' => '/price/edit/8', 'id' => 'service_price_edit', 'access' => 'can_edit_service_prices'],
//	]],
//
//	['name' => lang_('Menu.storage', $locale), 'link' => '/storage/department/9', 'id' => 'storage_department', 'access' => 'can_see_storage_department', 'children' => [
//		['name' => lang_('Menu.storages', $locale), 'link' => '/storage/department/9', 'id' => 'production_storage', 'access' => 'can_see_storage_storages'],
//		['name' => lang_('Menu.edit prices', $locale), 'link' => '/price/edit/9', 'id' => 'price_edit', 'access' => 'can_edit_storage_prices'],
//	]],
//
//	['name' => lang_('Menu.franchise', $locale), 'link' => '#', 'id' => 'franchise_department', 'access' => 'can_see_franchise_department', 'children' => [
//		['name' => lang_('Menu.project add', $locale), 'link' => '/project/add/2', 'id' => 'franchise_project_add', 'access' => 'can_add_franchise_projects'],
//		['name' => lang_('Menu.projects', $locale), 'link' => '/project/department/2', 'id' => 'franchise_projects', 'access' => 'can_see_franchise_projects'],
//		['name' => lang_('Menu.edit prices', $locale), 'link' => '/price/edit/2', 'id' => 'price_edit', 'access' => 'can_edit_franchise_prices'],
//	]],

$menu[] =
	['name' => lang_('Menu.company', $locale), 'link' => '/production', 'id' => 'production', 'access' => 'can_see_company', 'children' => [
		['name' => lang_('Menu.projects', $locale), 'link' => '/production', 'id' => 'production', 'access' => 'can_see_company_projects'],
		['name' => lang_('Menu.project add', $locale), 'link' => '/project/add', 'id' => 'project_add', 'access' => 'can_add_projects'],
		['name' => "Договори", 'link' => '/contract', 'id' => 'project_add', 'access' => 'can_add_projects'],
		['name' => lang_('Menu.storages', $locale), 'link' => '/storage', 'id' => 'production_storage', 'access' => 'can_see_company_storages'],
		['name' => lang_('Menu.company results', $locale), 'link' => '/plan_fact/company_result', 'id' => 'plan_fact_company_result', 'access' => 'can_see_plan_fact_company_result'],
		['name' => 'P&l', 'link' => '/plan_fact/profit_and_loss', 'id' => 'plan_fact_company_result', 'access' => 'can_see_plan_fact_company_result'],
		['name' => lang_('Menu.department wallets', $locale), 'link' => '/wallet/department', 'id' => 'wallet_department', 'access' => 'can_see_department_wallets'],
		['name' => lang_('Menu.workers', $locale), 'link' => '/user/list', 'id' => 'users', 'access' => 'can_see_users'],
		['name' => lang_('Menu.contractors', $locale), 'link' => '/contractor', 'id' => 'users', 'access' => 'can_see_users'],
		['name' => lang_('Menu.salary pay', $locale), 'link' => '/salary/pay', 'id' => 'users', 'access' => 'can_pay_salary'],
		['name' => lang_('Menu.updates', $locale), 'link' => '/info', 'id' => 'info', 'access' => 'can_see_updates', 'children' => [
			['name' => 'Stacktrace', 'link' => '/info/stacktrace', 'id' => 'info_stacktrace', 'access' => 'can_see_errors']]
		]]];
$menu[] =
	['name' => 'Налаштування', 'link' => '#', 'id' => 'settings', 'access' => 'can_see_company', 'children' => [
		['name' => 'Старт', 'link' => '/excel', 'id' => 'start', 'access' => 'can_see_articles'],
		['name' => 'Опції', 'link' => '#', 'id' => 'start', 'access' => 'can_see_articles'], // дата виплати зп, фінансовий день
		['name' => 'Департаменти', 'link' => '/department', 'id' => 'department', 'access' => 'can_see_articles'],
		['name' => lang_('Menu.access', $locale), 'link' => '/access', 'id' => 'access', 'access' => 'can_see_access'],
		['name' => lang_('Menu.articles', $locale), 'link' => '/articles', 'id' => 'articles', 'access' => 'can_see_articles'],
		['name' => 'Оновлення', 'link' => '/info', 'id' => 'info', 'access' => 'can_see_articles']
	]];


