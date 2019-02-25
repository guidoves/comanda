<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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


$app->post('/users/new', \UserController::class . ':new_user')->add(\Validations::class . ':validate_new_user')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/users/update', \UserController::class . ':update_user')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/users/update_status', \UserController::class . ':update_user_status')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/log_login', \UserController::class . ':log_login')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/operations', \UserController::class . ':operations')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/all', \UserController::class . ':all_users')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/users/all_activate_users', \UserController::class . ':all_activate_users')->add(\Cors::class . ':HabilitarCORSTodos');



$app->post('/tables/new', \TableController::class . ':new_table')->add(\Cors::class . ':HabilitarCORSTodos');
$app->post('/tables/update', \TableController::class . ':update_table')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/tables/list', \TableController::class . ':all_tables')->add(\Cors::class . ':HabilitarCORSTodos');

$app->post('/comanda/new', \ComandaController::class . ':new_comanda')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/comanda/all_activate', \ComandaController::class . ':all_activate_comandas')->add(\Cors::class . ':HabilitarCORSTodos');

$app->post('/orders/new', \OrderController::class . ':new_order')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/best', \OrderController::class . ':get_best_seller')->add(\Cors::class . ':HabilitarCORSTodos');
$app->get('/orders/get_orders', \OrderController::class . ':get_orders')->add(\Cors::class . ':HabilitarCORSTodos');

$app->run();

?>