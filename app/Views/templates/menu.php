<?php

use App\Models\Departments;
use App\Models\Modules;

$locale = !empty($locale) ? $locale : 'ru';
$menu = [
	[
		'name' => lang_('Menu.operations', $locale),
		'link' => '/operation',
		'id' => 'operation',
		'access' => 'can_see_operations',
		'icon' => "../../../icons/bootstrap/cash-stack.svg",
		'children' => [
//			['name' => lang_('Menu.quick operations', $locale),
//			 'link' => '/operation/quick',
//			 'id' => 'operation',
//			 'access' => 'can_see_self_operations'],

			//		[
			//'name' => lang_('Menu.add expense', $locale),
			// 'link' => '/operation/add_expenses',
			// 'id' => 'operation_add_expenses',
			// 'access' => 'can_add_expense'],
//		[
//		'name' => lang_('Menu.add income', $locale),
//		 'link' => '/operation/add_income',
//		 'id' => 'operation_add_income',
//		 'access' => 'can_add_income'
//],
//		[
//'name' => lang_('Menu.add transfer', $locale),
// 'link' => '/operation/add_transfer',
// 'id' => 'operation_add_transfer',
// 'access' => 'can_add_transfer'
//],
			[
				'name' => lang_('Menu.my operations', $locale),
				'link' => '/operation',
				'id' => 'operation',
				'access' => 'can_see_self_operations',
				'icon' => "../../../icons/bootstrap/cash-stack.svg",
			],
			[
				'name' => lang_('Menu.my wallets', $locale),
				'link' => '/wallet',
				'id' => 'wallet',
				'access' => 'can_see_self_wallets',
				'icon' => "../../../icons/bootstrap/wallet2.svg"
			],
//		['name' => lang_('Menu.department operations', $locale), 'link' => '/operation/list', 'id' => '', 'access' => 'can_see_department_operations'],
//		['name' => 'Операції директора', 'link' => '#', 'id' => '', 'access' => 'can_see_director_operations'],
//		['name' => lang_('Menu.update data', $locale), 'link' => '/operation/update_later_operations', 'id' => '', 'access' => 'can_refresh_operations'],
			[
				'name' => lang_('Menu.charts', $locale),
				'link' => '/operation/charts',
				'id' => 'operation_charts',
				'access' => 'can_see_operation_charts',
				'icon' => "../../../icons/bootstrap/bar-chart-line.svg"
			],
			[
				'name' => lang_('Menu.credits', $locale),
				'link' => '/credit',
				'id' => 'credits',
				'access' => 'can_see_credits',
				'icon' => "../../../icons/bootstrap/credit-card-fill.svg"
			],
		]
	],
	[
		'name' => lang_('Menu.plan operations', $locale),
		'link' => '#',
		'id' => 'accruals',
		'access' => 'can_see_operations',
		'icon' => "../../../icons/bootstrap/cash.svg",
		'children' => [
			[
				'name' => lang_('Menu.plan operations', $locale),
				'link' => '/operation/plan',
				'id' => 'operation_add_expenses',
				'access' => 'can_add_expense',
				'icon' => "../../../icons/bootstrap/cash.svg"
			],
			[
				'name' => lang_('Menu.operation templates', $locale),
				'link' => '/operation/templates',
				'id' => 'operation_add_expenses',
				'access' => 'can_add_expense',
				'icon' => "../../../icons/bootstrap/clipboard-plus.svg"
			],
//		['name' => "мої шаблони операцій", 'link' => '#', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense'],
//		['name' => "планування по статтях", 'link' => '#', 'id' => 'operation_add_expenses', 'access' => 'can_add_expense']
		]
	],
	[
		'name' => lang_('Menu.applications', $locale),
		'link' => '#',
		'id' => 'application',
		'access' => 'can_see_applications',
		'icon' => "../../../icons/bootstrap/journal-text.svg",
		'children' => [
			[
				'name' => lang_('Menu.add app', $locale),
				'link' => '/application/add',
				'id' => 'application_add',
				'access' => 'can_add_applications',
				'icon' => "../../../icons/bootstrap/journal-plus.svg"
			],
			[
				'name' => lang_('Menu.my apps', $locale),
				'link' => '/application',
				'id' => 'application',
				'access' => 'can_see_self_applications',
				'icon' => "../../../icons/bootstrap/journal-text.svg"
			],
//		['name' => lang_('Menu.department apps', $locale), 'link' => '/application/department', 'id' => 'application_all', 'access' => 'can_see_department_applications'],
//		['name' => lang_('Menu.department approved', $locale), 'link' => '/application/department/approved', 'id' => 'application_approved', 'access' => 'can_see_department_applications_approved'],
//		['name' => lang_('Menu.department payed', $locale), 'link' => '/application/department/payed', 'id' => 'application_payed', 'access' => 'can_see_department_applications_payed'],
			[
				'name' => lang_('Menu.to pay on TOV', $locale),
				'link' => '/application/approved_tov',
				'id' => 'application_approved_tov',
				'access' => 'can_see_application_approved_tov',
				'icon' => "../../../icons/bootstrap/journal-richtext.svg"
			],
//		['name' => lang_('Menu.update statuses', $locale), 'link' => '/application/refresh', 'id' => 'application_refresh', 'access' => 'can_refresh_applications'],
			[
				'name' => lang_('Menu.charts', $locale),
				'link' => '/application/charts',
				'id' => 'application_charts',
				'access' => 'can_view_application_charts',
				'icon' => "../../../icons/bootstrap/bar-chart-line.svg"
			],
		]
	],
	[
		'name' => lang_('Menu.accruals', $locale),
		'link' => '#',
		'id' => 'accruals',
		'access' => 'can_see_operations',
		'icon' => "../../../icons/bootstrap/clipboard-data.svg",
		'children' => [
			[
				'name' => lang_('Menu.accruals', $locale),
				'link' => '/accruals',
				'id' => 'operation_add_expenses',
				'access' => 'can_add_expense',
				'icon' => "../../../icons/bootstrap/clipboard-data.svg"
			],
			[
				'name' => lang_('Menu.fixed costs', $locale),
				'link' => '/accruals/templates',
				'id' => 'operation_add_expenses',
				'access' => 'can_add_expense',
				'icon' => "../../../icons/bootstrap/hourglass-split.svg"
			]
		]
	],
	[
		'name' => lang_('Menu.sales', $locale),
		'link' => '#',
		'id' => 'accruals',
		'access' => 'can_see_operations',
		'icon' => "../../../icons/bootstrap/cart-check.svg",
		'children' => [
			[
				'name' => lang_('Menu.sales', $locale),
				'link' => '/sales',
				'id' => 'operation_add_expenses',
				'access' => 'can_add_expense',
				'icon' => "../../../icons/bootstrap/cart-check.svg"
			],
		]]];

$menu_departments = Departments::get_departments(['where' => ['is_shown = 1']]);

if (!empty($menu_departments)) {
	foreach ($menu_departments as $menu_department) {
		$menu_item = [
			'name' => $menu_department['name'],//todo замінити на прив'язані до акаунтів
			'link' => '#',
			'id' => $menu_department['name'] . '_department',
//			'access' => 'can_see_' . $menu_department['name']
			'access' => 'can_see_production',
			'icon' => "../../../icons/bootstrap/house.svg"
		];

		if (strlen(trim($menu_department['modules'])) > 0) {
			$menu_modules = Modules::get_modules(['where' => ['id IN (' . $menu_department['modules'] . ')']]);
			if (!empty($menu_modules)) {
				foreach ($menu_modules as $menu_module) {
					$menu_sub_item = [
						'name' => lang_('Menu.' . $menu_module['name'], $locale),//todo замінити на прив'язані до акаунтів
						'link' => $menu_module['link'] . '/department/' . $menu_department['id'],
						'id' => $menu_department['name'] . '_' . $menu_department['id'],
//					'access' => 'can_see_' . $menu_department['name']
						'access' => 'can_see_production',
						'icon' => "../../../icons/bootstrap/play.svg"
					];
					$menu_item['children'][] = $menu_sub_item;
				}
			}
		}
		$menu[] = $menu_item;
	}
}


$menu[] =
	[
		'name' => lang_('Menu.company', $locale),
		'link' => '#',
		'id' => 'production',
		'access' => 'can_see_company',
		'icon' => "../../../icons/bootstrap/building.svg",
		'children' =>
			[
//		['name' => lang_('Menu.projects', $locale), 'link' => '/production', 'id' => 'production', 'access' => 'can_see_company_projects'],
//		['name' => lang_('Menu.project add', $locale), 'link' => '/project/add', 'id' => 'project_add', 'access' => 'can_add_projects'],
				[
					'name' => lang_('Menu.contracts', $locale),
					'link' => '/contract',
					'id' => 'project_add',
					'access' => 'can_add_projects',
					'icon' => "../../../icons/bootstrap/file-earmark-text.svg",
				],
				[
					'name' => lang_('Menu.storages', $locale),
					'link' => '/storage',
					'id' => 'production_storage',
					'access' => 'can_see_company_storages',
					'icon' => "../../../icons/bootstrap/layers-fill.svg",
				],
				[
					'name' => lang_('Menu.company results', $locale),
					'link' => '/report/company_result',
					'id' => 'report_company_result',
					'access' => 'can_see_plan_fact_company_result',
					'icon' => "../../../icons/bootstrap/bar-chart-steps.svg",
				],
				[
					'name' => 'P&l',
					'link' => '/report/profit_and_loss',
					'id' => 'plan_fact_company_result',
					'access' => 'can_see_plan_fact_company_result',
					'icon' => "../../../icons/bootstrap/bar-chart-line.svg",
				],
//		[
//'name' => lang_('Menu.department wallets', $locale), 'link' => '/wallet/department', 'id' => 'wallet_department', 'access' => 'can_see_department_wallets'],
				[
					'name' => lang_('Menu.departments', $locale),
					'link' => '/department',
					'id' => 'department',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/house-fill.svg",
				],
				[
					'name' => lang_('Menu.positions', $locale),
					'link' => '/position',
					'id' => 'users',
					'access' => 'can_see_users',
					'icon' => "../../../icons/bootstrap/file-person.svg",
				],
				[
					'name' => lang_('Menu.workers', $locale),
					'link' => '/user/list',
					'id' => 'users',
					'access' => 'can_see_users',
					'icon' => "../../../icons/bootstrap/file-person-fill.svg",
				],
				[
					'name' => lang_('Menu.contractors', $locale),
					'link' => '/contractor',
					'id' => 'users',
					'access' => 'can_see_users',
					'icon' => "../../../icons/bootstrap/people.svg",
				],
//		['name' => lang_('Menu.salary pay', $locale), 'link' => '/salary/pay', 'id' => 'users', 'access' => 'can_pay_salary'],
//		['name' => lang_('Menu.updates', $locale), 'link' => '/info', 'id' => 'info', 'access' => 'can_see_updates', 'children' => [
//			['name' => 'Stacktrace', 'link' => '/info/stacktrace', 'id' => 'info_stacktrace', 'access' => 'can_see_errors']]
//		]
			]];
$menu[] =
	[
		'name' => lang_('Menu.settings', $locale),
		'link' => '#',
		'id' => '#',
		'access' => 'can_see_company',
		'icon' => "../../../icons/bootstrap/gear.svg",
		'children' =>
			[
				[
					'name' => lang_('Menu.start', $locale),
					'link' => '/start',
					'id' => 'start',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/shift.svg",
				],
				[
					'name' => "Telegram",
					'link' => '/telegram',
					'id' => 'telegram',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/phone.svg",
				],
//		['name' => 'Опції', 'link' => '/options', 'id' => 'start', 'access' => 'can_see_articles'], // дата виплати зп, фінансовий день
//		['name' => lang_('Menu.access', $locale), 'link' => '/access', 'id' => 'access', 'access' => 'can_see_access'],
				[
					'name' => lang_('Menu.articles', $locale),
					'link' => '/articles',
					'id' => 'articles',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/card-list.svg",
				],
//		['name' => "Шаблони статтей", 'link' => '/articles/templates', 'id' => 'articles', 'access' => 'can_see_articles'],
			]
	];
$menu[] =
	[
		'name' => lang_('Menu.info', $locale),
		'link' => '#',
		'id' => 'info',
		'access' => 'can_see_articles',
		'icon' => "../../../icons/bootstrap/info.svg",
		'children' =>
			[
				[
					'name' => lang_('Menu.updates', $locale),
					'link' => 'info',
					'id' => 'info',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/pen.svg",
				],
				[
					'name' => lang_('Menu.suggestions', $locale),
					'link' => 'info/suggestions',
					'id' => 'suggestions',
					'access' => 'can_see_articles',
					'icon' => "../../../icons/bootstrap/star-half.svg",
				],
//		['name' => 'Лог', 'link' => 'info/stacktrace', 'id' => 'stacktrace', 'access' => 'can_see_articles']
			]
	];




