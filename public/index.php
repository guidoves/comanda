<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

require '../vendor/autoload.php';

//MIDDLEWARE
require_once '../mw/Cors.php';
require_once '../mw/Validations.php';

//CONTROLLERS
require_once '../controllers/users.php';
require_once '../controllers/login.php';
require_once '../controllers/tables.php';
require_once '../controllers/comanda.php';
require_once '../controllers/orders.php';
require_once '../controllers/menues.php';


$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
    ],
];
$c = new \Slim\Container($configuration);

$app = new \Slim\App($c);

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->post('/login', \Logins::class . ':login')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/login/check_token', \Logins::class . ':checkToken')->add(\Cors::class . ':HabilitarCORSTodos');


$app->post('/users/new', \UserController::class . ':new_user')->add(\Validations::class . ':checkAdmin')->add(\Validations::class . ':validate_new_user')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/users/update', \UserController::class . ':update_user')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/users/update_status', \UserController::class . ':update_user_status')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/log_login', \UserController::class . ':log_login')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/operations', \UserController::class . ':operations')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/all', \UserController::class . ':all_users')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/all_activate_users', \UserController::class . ':all_activate_users')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');



$app->post('/tables/new', \TableController::class . ':new_table')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/tables/update', \TableController::class . ':update_table')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/tables/open_table', \TableController::class . ':open_table')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/tables/close_table', \TableController::class . ':close_table')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/tables/disable_table', \TableController::class . ':disable_table')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/list', \TableController::class . ':all_tables')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/get_used', \TableController::class . ':get_used_tables')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/get_table_by_amount', \TableController::class . ':get_table_by_amount')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/get_tables_by_amount', \TableController::class . ':get_tables_by_amount')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/get_tables_by_amount_import', \TableController::class . ':get_tables_by_amount_import')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/get_opinion_tables', \TableController::class . ':get_opinion')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');

//$app->post('/comanda/new', \ComandaController::class . ':new_comanda')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/comanda/all_activate', \ComandaController::class . ':all_activate_comandas')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/comanda/update_client_name', \ComandaController::class . ':update_client_name')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/comanda/opinion', \ComandaController::class . ':opinion')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/comanda/orders_user', \ComandaController::class . ':get_orders_for_user')->add(\Validations::class . ':checkTable')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/comanda/up_photo', \ComandaController::class . ':up_photo')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/comanda/delete_photo', \ComandaController::class . ':delete_photo')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/comanda/print_tkt', \ComandaController::class . ':print_tkt')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');

$app->post('/orders/new', \OrderController::class . ':new_order')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/orders/close', \OrderController::class . ':close')->add(\Validations::class . ':checkUser')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/best', \OrderController::class . ':get_best_seller')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/get_orders', \OrderController::class . ':get_orders')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/orders/update_status', \OrderController::class . ':update_status')->add(\Validations::class . ':checkUser')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/all_by_type', \OrderController::class . ':all_by_type')->add(\Validations::class . ':checkUser')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/get_delayed_orders', \OrderController::class . ':get_delayed_orders')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/all_activate', \OrderController::class . ':all_activate')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');

$app->post('/menu/new', \MenuController::class . ':new_menu')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/menu/delete', \MenuController::class . ':delete_menu')->add(\Validations::class . ':checkAdmin')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/menu/all', \MenuController::class . ':all_menus')->add(\Validations::class . ':checkMozo')->add(\Cors::class . ':HabilitarCORSTodos');

$app->run();

?>