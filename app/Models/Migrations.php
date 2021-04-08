<?php


namespace App\Models;

use Config;

class Migrations
{
	public static function update_tables()
	{
		$queries = [
//			'ALTER TABLE `fin_accounts` ADD `api_key` VARCHAR(50) NOT NULL AFTER `ref`;',
//			'INSERT INTO `fin_accesses` (`id`, `name`, `type`, `parent_id`) VALUES (NULL, \'new lead\', \'notification\', \'\');',
//			'CREATE TABLE `fin_tasks` (
//			  `id` int(11) NOT NULL,
//			  `date` int(11) NOT NULL,
//			  `task` varchar(255) NOT NULL,
//			  `statistics` int(11) NOT NULL,
//			  `comment` text NOT NULL,
//			  `date_to` int(11) NOT NULL,
//			  `lead_id` int(11) NOT NULL,
//			  `user_id` int(11) NOT NULL,
//			  `status` varchar(50) NOT NULL
//			) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
//			'ALTER TABLE `fin_tasks` ADD `notify` INT NOT NULL AFTER `date_to`;',
//			'ALTER TABLE `fin_tasks` ADD `account_id` INT NOT NULL AFTER `status`;',
//			'CREATE TABLE `fin_lead_contacts` (
//			  `id` int(11) NOT NULL,
//			  `name` varchar(255) NOT NULL,
//			  `type` varchar(20) NOT NULL,
//			  `value` varchar(50) NOT NULL,
//			  `lead_id` int(11) NOT NULL
//			) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
//			'CREATE TABLE `fin_product_groups` (
//				  `id` int(11) NOT NULL,
//				  `name` varchar(255) NOT NULL,
//				  `account_id` int(11) NOT NULL
//				) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
//			'CREATE TABLE `fin_lead_answers` (
//				  `id` int(11) NOT NULL,
//				  `lead_id` int(11) NOT NULL,
//				  `question_id` int(11) NOT NULL,
//				  `answer` text NOT NULL
//				) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
//			'CREATE TABLE `fin_product_group_questions` (
//			  `id` int(11) NOT NULL,
//			  `question` varchar(255) NOT NULL,
//			  `product_group_id` int(11) NOT NULL
//			) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
//			'ALTER TABLE `fin_products` ADD `group_id` INT NOT NULL AFTER `name`;',
//			'INSERT INTO `fin_menu` (`id`, `name`, `link`, `access_id`, `icon`, `parent_id`, `is_shown`, `order_num`) VALUES (NULL, \'leads\', \'/marketing/leads\', \'\', \'icons/bootstrap/cart-check.svg \', \'5\', \'1\', \'3\')',
//			'INSERT INTO `fin_menu` (`id`, `name`, `link`, `access_id`, `icon`, `parent_id`, `is_shown`, `order_num`) VALUES (NULL, \'products\', \'/sales/products\', \'\', \'icons/bootstrap/cart-check.svg \', \'5\', \'1\', \'2\')
//'
//		'ALTER TABLE `fin_contractors` ADD `lead_id` INT NOT NULL AFTER `user_id`;',
//		'ALTER TABLE `fin_products` ADD `payment_type` VARCHAR(10) NOT NULL COMMENT \'pre, post, pre&post\' AFTER `type`',
//		'ALTER TABLE `fin_products` ADD `first_payment` INT NOT NULL AFTER `payment_type`, ADD `average_sale_time` INT NOT NULL AFTER `first_payment`, ADD `average_project_time` INT NOT NULL AFTER `average_sale_time`;',
//		'ALTER TABLE `fin_products` ADD `article_id` INT NOT NULL AFTER `type_id`;',
//			'ALTER TABLE `fin_products` ADD `comment` TEXT NOT NULL AFTER `currency`;',
//			'RENAME TABLE `ekonombd_fineko`.`fin_product_groups` TO `ekonombd_fineko`.`fin_question_groups`;',
//			'RENAME TABLE `ekonombd_fineko`.`fin_product_group_questions` TO `ekonombd_fineko`.`fin_questions`;',
//			'ALTER TABLE `fin_products` ADD `question_group_ids` VARCHAR(255) NOT NULL AFTER `department_id`;',
//            'ALTER TABLE `fin_tasks` ADD `author_id` int(11) NOT NULL AFTER `notify`;'

//			"ALTER TABLE `fin_settings` ADD `type` VARCHAR(10) NOT NULL AFTER `value`, ADD `comment` TEXT NOT NULL AFTER `type`;",
//			"ALTER TABLE `fin_settings` ADD `field_type` VARCHAR(10) NOT NULL AFTER `type`;",
//			"INSERT INTO `fin_settings` (`id`, `name`, `value`, `type`, `field_type`, `comment`, `account_id`, `business_unit_id`) VALUES (NULL, 'finance day', '', 'default', 'day_of_week', 'День для фінансового планування і видачі ресурсів по заявках', '', '');",
//			'INSERT INTO `fin_settings` (`id`, `name`, `value`, `type`, `field_type`, `comment`, `account_id`, `business_unit_id`) VALUES (NULL, \'salary date\', \'5\', \'default\', \'day_of_month\', \'Дата виплати зарплати співробітникам\', \'\', \'\'), (NULL, \'advance_date\', \'15\', \'default\', \'day_of_month\', \'Дата виплати авансів співробітникам\', \'\', \'\');',
//			'ALTER TABLE `fin_positions` ADD `advance_percent` INT NOT NULL AFTER `work_days`;',
//			'ALTER TABLE `fin_positions` ADD `advance_amount` INT NOT NULL AFTER `advance_percent`;',

//            'INSERT INTO `fin_menu` (`name`, `link`, `access_id`, `icon`, `parent_id`, `is_shown`, `order_num`) SELECT \'clients\', \'/marketing/clients\', \'17\', \'icons/bootstrap/cart-check.svg\', \'43\', \'1\', \'5\' FROM `fin_menu` WHERE ((`id` = \'44\'));',
//            'ALTER TABLE `fin_question_groups` ADD `from_status` varchar(255) COLLATE \'utf8_general_ci\' NOT NULL AFTER `name`;',
//            'ALTER TABLE `fin_specification` ADD `name` varchar(250) NOT NULL AFTER `id`, ADD `comment` varchar(800) NOT NULL AFTER `name`, ADD `photo` varchar(250) NOT NULL AFTER `amount`;',
//            'CREATE TABLE `fin_specification_items` (
//              `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
//              `specification_id` int NOT NULL,
//              `subspecification_id` int NULL,
//              `storage_name_id` int NULL,
//              `type` varchar(50) COLLATE \'utf8_general_ci\' NOT NULL
//            ) ENGINE=\'InnoDB\' COLLATE \'utf8_general_ci\';',
//            'ALTER TABLE `fin_specification`
//            ADD `project_id` int(11) NOT NULL AFTER `amount`,
//            ADD `account_id` int(11) NOT NULL AFTER `project_id`;',
//            'ALTER TABLE `fin_question_groups` ADD `from_status` varchar(255) COLLATE \'utf8_general_ci\' NOT NULL AFTER `name`;',

//            'INSERT INTO  `fin_menu` (`name`, `link`, `access_id`, `icon`, `parent_id`, `is_shown`, `order_num`) SELECT \'clients\', \'/marketing/clients\', \'17\', \'icons/bootstrap/cart-check.svg\', \'43\', \'1\', \'5\' FROM `fin_menu` WHERE ((`id` = \'44\'));',
//            'ALTER TABLE `fin_question_groups` ADD `from_status` varchar(255) COLLATE \'utf8_general_ci\' NOT NULL AFTER `name`;'

//			"ALTER TABLE `fin_projects` ADD `lead_id` INT NOT NULL AFTER `options`;",
//			"ALTER TABLE `fin_projects` ADD `is_virtual` INT NOT NULL AFTER `name`;"
			"ALTER TABLE `fin_specification`
			  DROP `storage_name_id`,
			  DROP `production_product_id`,
			  DROP `amount`,
			  DROP `project_id`,
			  DROP `block_id`;",
			"ALTER TABLE `fin_specification_items` ADD `amount` INT NOT NULL AFTER `storage_name_id`;",
			"ALTER TABLE `fin_storage_names` ADD `article` VARCHAR(50) NOT NULL AFTER `name`;",
			"ALTER TABLE `fin_specification_items` CHANGE `type` `type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'storage_name, specification, service';",

			"ALTER TABLE `fin_storage_names` ADD `photo` VARCHAR(255) NOT NULL AFTER `type_id`;",
			"ALTER TABLE `fin_storage_names` DROP `type_id`;",
			"ALTER TABLE `fin_storage_names` ADD `account_id` INT NOT NULL AFTER `supplier_id`;",
			"CREATE TABLE `fin_instruction` (
              `id` int(11) NOT NULL,
              `text` text NOT NULL,
              `position_id` int(11) NOT NULL,
              `type` varchar(30) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
			"ALTER TABLE `fin_tasks`CHANGE `id` `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;",
			"ALTER TABLE `fin_users` ADD `photo` VARCHAR(255) NOT NULL AFTER `surname`;",
			"ALTER TABLE fin_operations ADD budget_Id INT NOT NULL AFTER plan_id;",
			'CREATE TABLE `fin_budgets` (
              `id` int(11) NOT NULL,
              `amount` int(11) NOT NULL,
              `name` varchar(255) NOT NULL,
              `article_id` int(11) NOT NULL,
              `comment` text NOT NULL,
              `type` varchar(20) NOT NULL,
              `date` int(11) NOT NULL,
              `account_id` int(11) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;',
			'ALTER TABLE `fin_budgets`
              ADD PRIMARY KEY (`id`);',
			'ALTER TABLE `fin_budgets`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;',
			"ALTER TABLE `fin_budgets` CHANGE `type` `time_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;",
			'ALTER TABLE `fin_leads` ADD `question_groups_ids` text COLLATE \'utf8_general_ci\' NOT NULL AFTER `docs`;',
			'ALTER TABLE `fin_users` ADD `type` INT(20) NOT NULL COMMENT \'user - користувач, bussiness_unit - ТОВ, ФОП і т.д\' AFTER `login_time`;',
			'ALTER TABLE `fin_wallets` ADD `department_id` INT NOT NULL AFTER `create_date`;',
			'UPDATE `fin_tasks` SET status= \'active\' WHERE `status` = \'new\'',
			'ALTER TABLE `fin_wallets` ADD `for_income` INT NOT NULL DEFAULT \'0\' AFTER `is_default`;',
			'ALTER TABLE `fin_wallets` ADD `for_expence` INT NOT NULL AFTER `for_income`;',
			'ALTER TABLE `fin_wallets` CHANGE `for_income` `for_income` INT(11) NOT NULL DEFAULT \'0\' COMMENT \'якщо 1 - цей гаманець можна використовувати для прийому оплат\';',
			'ALTER TABLE `fin_wallets` CHANGE `for_expence` `for_expence` INT(11) NOT NULL COMMENT \'Якщо 1 - гаманець можна використовувати для оплат\';',
			'ALTER TABLE `fin_wallets` CHANGE `for_expence` `for_expense` INT(11) NOT NULL COMMENT \'Якщо 1 - гаманець можна використовувати для оплат\';',
			'ALTER TABLE `fin_leads` ADD `wallet_id` INT NOT NULL AFTER `source_id`;',
			"ALTER TABLE `fin_wallets` CHANGE `department_id` `department_id` INT(11) NOT NULL COMMENT 'використовується в cashflow';",
			"ALTER TABLE `fin_users` CHANGE `type` `type` VARCHAR(20) NOT NULL COMMENT 'user - користувач, bussiness_unit - ТОВ, ФОП і т.д';",
			"UPDATE `fin_users` SET `type`=\"user\"",
			'ALTER TABLE `fin_storage` ADD `comment` TEXT NOT NULL AFTER `type`;'

		];

		if (!empty($queries)) {
			foreach ($queries as $query) {
				try {
					DBHelp::change_table($query);
				} catch (\Exception $ex) {
					continue;
				}
			}
		}
	}
}
