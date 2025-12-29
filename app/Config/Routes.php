// app/Config/Routes.php
$router->get('/admin', 'Admin\\DashboardController@index');
$router->get('/admin/company', 'Admin\\CompanyController@index');
$router->post('/admin/company/save', 'Admin\\CompanyController@save');

$router->get('/admin/departments', 'Admin\\DepartmentController@index');
$router->post('/admin/departments/save', 'Admin\\DepartmentController@save');

$router->get('/admin/employees', 'Admin\\EmployeeController@index');
$router->get('/admin/employees/create', 'Admin\\EmployeeController@create');
$router->post('/admin/employees/store', 'Admin\\EmployeeController@store');

$router->get('/admin/shifts', 'Admin\\ShiftController@index');
$router->post('/admin/shifts/save', 'Admin\\ShiftController@save');

$router->get('/admin/salary-components', 'Admin\\PayrollController@components');
$router->post('/admin/salary-components/save', 'Admin\\PayrollController@saveComponent');

$router->get('/admin/approvals', 'Admin\\ApprovalController@index');
