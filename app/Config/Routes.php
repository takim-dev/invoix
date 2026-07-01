<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'LandingController::index');
$routes->get('/about', 'PageController::about');
$routes->get('/contact', 'PageController::contact');
$routes->get('/help', 'PageController::help');
$routes->get('/language/(:segment)', 'LanguageController::switch/$1');
$routes->get('/dashboard', 'DashboardController::index');

// Auth
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::login');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/verify-email/(:any)', 'AuthController::verifyEmail/$1');

// Account
$routes->get('/account', 'AccountController::index');
$routes->post('/account/password', 'AccountController::updatePassword');
$routes->post('/account/language', 'AccountController::updateLanguage');

// Companies
$routes->get('/companies', 'CompanyController::index');
$routes->get('/companies/create', 'CompanyController::create');
$routes->post('/companies/store', 'CompanyController::store');
$routes->get('/companies/(:num)/edit', 'CompanyController::edit/$1');
$routes->post('/companies/(:num)/update', 'CompanyController::update/$1');
$routes->post('/companies/(:num)/delete', 'CompanyController::delete/$1');

// Item Categories
$routes->get('/items/categories', 'ItemCategoryController::index');
$routes->post('/items/categories/store', 'ItemCategoryController::store');
$routes->post('/items/categories/(:num)/delete', 'ItemCategoryController::delete/$1');

// Items
$routes->get('/items', 'ItemController::index');
$routes->get('/items/datatables', 'ItemController::datatables');
$routes->get('/items/create', 'ItemController::create');
$routes->post('/items/store', 'ItemController::store');
$routes->get('/items/(:num)/edit', 'ItemController::edit/$1');
$routes->post('/items/(:num)/update', 'ItemController::update/$1');
$routes->post('/items/(:num)/delete', 'ItemController::delete/$1');

// Invoices
$routes->get('/invoices', 'InvoiceController::index');
$routes->get('/invoices/datatables', 'InvoiceController::datatables');
$routes->get('/invoices/export', 'InvoiceController::export');
$routes->get('/invoices/generate-number', 'InvoiceController::generateNumber');
$routes->get('/invoices/create', 'InvoiceController::create');
$routes->post('/invoices/store', 'InvoiceController::store');
$routes->get('/invoices/(:num)', 'InvoiceController::show/$1');
$routes->get('/invoices/(:num)/edit', 'InvoiceController::edit/$1');
$routes->post('/invoices/(:num)/update', 'InvoiceController::update/$1');
$routes->post('/invoices/(:num)/delete', 'InvoiceController::delete/$1');
$routes->post('/invoices/(:num)/status', 'InvoiceController::updateStatus/$1');
$routes->post('/invoices/(:num)/toggle-public', 'InvoiceController::togglePublic/$1');
$routes->get('/share/(:any)/pdf', 'InvoiceController::sharePdf/$1');
$routes->get('/share/(:any)', 'InvoiceController::share/$1');

// Admin
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/users/datatables', 'AdminController::usersDatatables');
$routes->get('/admin/user/(:num)/edit', 'AdminController::editUser/$1');
$routes->post('/admin/user/(:num)/update', 'AdminController::updateUser/$1');
$routes->post('/admin/user/(:num)/delete', 'AdminController::deleteUser/$1');

// App Settings
$routes->get('/admin/settings', 'AdminController::settings');
$routes->post('/admin/settings', 'AdminController::settings');
$routes->get('/admin/invoice-config', 'AdminController::invoiceConfig');
$routes->post('/admin/invoice-config', 'AdminController::invoiceConfig');
$routes->get('/admin/auth-settings', 'AdminController::authSettings');
$routes->post('/admin/auth-settings', 'AdminController::authSettings');
$routes->get('/admin/email-config', 'AdminController::emailConfig');
$routes->post('/admin/email-config', 'AdminController::emailConfig');
$routes->post('/admin/email-config/test', 'AdminController::testEmail');

$routes->get('/admin/pages', 'AdminController::pages');
$routes->post('/admin/pages', 'AdminController::pages');

// Print & PDF
$routes->get('/invoices/(:num)/print', 'PdfController::print/$1');
$routes->get('/invoices/(:num)/pdf', 'PdfController::pdf/$1');
