<?php defined('SYSTEMPATH') || exit('No direct script access allowed'); ?>

CRITICAL - 2020-01-14 04:25:02 --> Unknown column 'B' in 'field list'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(73): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(155): App\Models\Applications::change_category('24', 'B')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->change_category_ajax('24')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 04:28:17 --> 
#0 /home/ekonombd/ekonombud.in.ua/fin/system/HTTP/RedirectResponse.php(92): CodeIgniter\HTTP\Exceptions\HTTPException::forInvalidRedirectRoute('')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(929): CodeIgniter\HTTP\RedirectResponse->route(false)
#2 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(107): redirect('/user/login')
#3 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->add()
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#6 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#7 {main}
CRITICAL - 2020-01-14 04:41:51 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(147): App\Models\Applications::get_app('undefined')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 04:41:57 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(147): App\Models\Applications::get_app('undefined')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 04:50:59 --> Undefined variable: app
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(33): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 33, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(147): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 04:56:18 --> Undefined property: stdClass::$type
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(35): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined prope...', '/home/ekonombd/...', 35, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(147): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 04:56:22 --> Undefined property: stdClass::$type
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(35): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined prope...', '/home/ekonombd/...', 35, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(147): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:03:48 --> Undefined property: stdClass::$project
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(52): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined prope...', '/home/ekonombd/...', 52, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(148): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:12:27 --> You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'as expence_item, situation, data, decision, status, category, project_id,fp.name' at line 1
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(148): App\Models\Applications::get_app('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 05:14:57 --> Column 'date' in field list is ambiguous
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(148): App\Models\Applications::get_app('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 05:15:52 --> Undefined property: stdClass::$project
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(52): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined prope...', '/home/ekonombd/...', 52, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(148): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:15:56 --> Undefined property: stdClass::$project
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(52): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined prope...', '/home/ekonombd/...', 52, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(148): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('3')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:36:43 --> Undefined variable: op_style
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(82): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 82, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(150): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('24')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:36:45 --> Undefined variable: op_style
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(82): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 82, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(150): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('24')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:38:50 --> Undefined variable: currencies
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(83): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 83, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(156): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('24')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 05:38:57 --> Undefined variable: currencies
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/app_info.php(83): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 83, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('app_info', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(156): view('app_info', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('24')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 06:05:44 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(161): App\Models\Applications::get_app('undefined')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 06:24:44 --> Undefined variable: can_pay
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/application_list.php(41): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 41, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('application_lis...', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(45): view('application_lis...', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->index()
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 06:24:49 --> Undefined variable: can_pay
#0 /home/ekonombd/ekonombud.in.ua/fin/app/Views/application_list.php(41): CodeIgniter\Debug\Exceptions->errorHandler(8, 'Undefined varia...', '/home/ekonombd/...', 41, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/system/View/View.php(236): include('/home/ekonombd/...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Common.php(176): CodeIgniter\View\View->render('application_lis...', Array, NULL)
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(45): view('application_lis...', Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->index()
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 06:27:26 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(163): App\Models\Applications::get_app('undefined')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 06:29:34 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(21): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(163): App\Models\Applications::get_app('undefined')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->infoAjax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 07:49:45 --> App\Controllers\Application::pdf(): Failed opening required '../Libraries/FPDF/fpdf.php' (include_path='.:/usr/local/pear/php72')
#0 [internal function]: CodeIgniter\Debug\Exceptions->shutdownHandler()
#1 {main}
CRITICAL - 2020-01-14 08:27:46 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(73): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(173): App\Models\Applications::change_category('undefined', '')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->change_category_ajax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 08:29:50 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(73): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(173): App\Models\Applications::change_category('undefined', '')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->change_category_ajax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 08:29:57 --> Unknown column 'undefined' in 'where clause'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(73): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(173): App\Models\Applications::change_category('undefined', '')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->change_category_ajax('undefined')
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 09:36:34 --> You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')' at line 1
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(90): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(182): App\Models\Applications::change_status(Array, '5')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->ready_to_pay_ajax()
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 09:39:18 --> Unknown column 'status' in 'field list'
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('UPDATE `fin_app...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('UPDATE `fin_app...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('UPDATE `fin_app...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(90): CodeIgniter\Database\BaseConnection->query('UPDATE `fin_app...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(182): App\Models\Applications::change_status(Array, '5')
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->ready_to_pay_ajax()
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#8 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#9 {main}
CRITICAL - 2020-01-14 11:01:43 --> implode(): Invalid arguments passed
#0 [internal function]: CodeIgniter\Debug\Exceptions->errorHandler(2, 'implode(): Inva...', '/home/ekonombd/...', 34, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Mail.php(34): implode('\r\n', 'MIME-Version: 1...')
#2 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(134): App\Models\Mail::send_mail('olexandrmatsuk@...', '\xD0\x97\xD0\xB0\xD1\x8F\xD0\xB2\xD0\xBA\xD0\xB8 \xD0\xB4...', '<h2>\xD0\xA0\xD0\xB0\xD1\x85\xD1\x83\xD0\xBD\xD0...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(183): App\Models\Applications::send_application_mail(Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->ready_to_pay_ajax()
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 11:02:05 --> implode(): Invalid arguments passed
#0 [internal function]: CodeIgniter\Debug\Exceptions->errorHandler(2, 'implode(): Inva...', '/home/ekonombd/...', 34, Array)
#1 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Mail.php(34): implode('\r\n', 'MIME-Version: 1...')
#2 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(134): App\Models\Mail::send_mail('olexandrmatsuk@...', '\xD0\x97\xD0\xB0\xD1\x8F\xD0\xB2\xD0\xBA\xD0\xB8 \xD0\xB4...', '<h2>\xD0\xA0\xD0\xB0\xD1\x85\xD1\x83\xD0\xBD\xD0...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(183): App\Models\Applications::send_application_mail(Array)
#4 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->ready_to_pay_ajax()
#5 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#7 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#8 {main}
CRITICAL - 2020-01-14 11:03:57 --> You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ')' at line 1
#0 /home/ekonombd/ekonombud.in.ua/fin/system/Database/MySQLi/Connection.php(330): mysqli->query('SELECT fo.id,  ...')
#1 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(738): CodeIgniter\Database\MySQLi\Connection->execute('SELECT fo.id,  ...')
#2 /home/ekonombd/ekonombud.in.ua/fin/system/Database/BaseConnection.php(666): CodeIgniter\Database\BaseConnection->simpleQuery('SELECT fo.id,  ...')
#3 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(107): CodeIgniter\Database\BaseConnection->query('SELECT fo.id,  ...')
#4 /home/ekonombd/ekonombud.in.ua/fin/app/Models/Applications.php(114): App\Models\Applications::get_apps_by_ids(Array)
#5 /home/ekonombd/ekonombud.in.ua/fin/app/Controllers/Application.php(183): App\Models\Applications::send_application_mail(Array)
#6 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(847): App\Controllers\Application->ready_to_pay_ajax()
#7 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(338): CodeIgniter\CodeIgniter->runController(Object(App\Controllers\Application))
#8 /home/ekonombd/ekonombud.in.ua/fin/system/CodeIgniter.php(246): CodeIgniter\CodeIgniter->handleRequest(NULL, Object(Config\Cache), false)
#9 /home/ekonombd/ekonombud.in.ua/fin/index.php(50): CodeIgniter\CodeIgniter->run()
#10 {main}
