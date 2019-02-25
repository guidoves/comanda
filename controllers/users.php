<?php

require_once '../models/User.php';
require_once '../models/Login.php';
require_once '../models/Order.php';

class UserController{

    public function new_user($request, $response){

        $body = $request->getParsedBody();
        
        if(!isset($body['user_name']) || !isset($body['password']) || !isset($body['role'])
        || !isset($body['full_name']) ){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);    
        }
        
        $user = new User();
        $user->user_name = $body['user_name'];
        $user->password = $body['password'];
        $user->role = $body['role'];
        $user->full_name = $body['full_name'];

        $user->new_user();

        $msj = array("ok" => "true", "msj" => "nuevo usuario dado de alta!", "user" => $user->user_name);
        return $response->withJson($msj, 200);


    }

    public function update_user($request, $response){
        
        $body = $request->getParsedBody();
        
        if(!isset($body['user_name']) || !isset($body['id']) || !isset($body['role'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);    
        }
        
        $user = new User();
        $user->id = $body['id'];
        $user->user_name = $body['user_name'];
        $user->role = $body['role'];

        $up_user = User::update($user);
        
        $msj = array("ok" => "true", "msj" => "usuario modificado!", "user" => $up_user );
        return $response->withJson($msj, 200);

    }

    public function update_user_status($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['user_id']) || !isset($body['status'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);    
        }

        $up_user = User::update_status($body['user_id'], $body['status']);

        if($up_user < 1){
            $msj = array("ok" => "false", "msj" => "id inexistente" );
            return $response->withJson($msj, 400);
        }
        
        $msj = array("ok" => "true", "msj" => "usuario modificado!", "user_id" => $body['user_id'] );
        return $response->withJson($msj, 200);
    }

    public function all_users($request, $response){
        $users = User::all();
        $msj = array("ok" => "true", "users" => $users );
        return $response->withJson($msj, 200);
    }

    public function all_activate_users($request, $response){
        $users = User::all_activate();
        $msj = array("ok" => "true", "users" => $users );
        return $response->withJson($msj, 200);
    }

    public function log_login($request, $response){
        $params = $request->getParams();

        if(!isset($params['user_id']) || !isset($params['start_date']) || !isset($params['end_date'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $logins = Login::get_logins($params['start_date'], $params['end_date'], $params['user_id'] );

        $msj = array("ok" => "true", "data" => $logins);
        return $response->withJson($msj, 200);
    }

    public function operations($request, $response){
        $params = $request->getParams();

        if( !isset($params['start_date']) || !isset($params['end_date'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }


        if( isset($params['sector']) && isset($params['user_id']) ){
            $orders = Order::get_orders_by_sector_user($params['start_date'], $params['end_date'], $params['sector'], $params['user_id'] );
            $operations = count($orders);

            $msj = array("ok" => "true", "operaciones" => $operations);
            return $response->withJson($msj, 200);
        }
        elseif(isset($params['sector'])) {
            $orders = Order::get_orders_by_sector($params['start_date'], $params['end_date'], $params['sector'] );
            $operations = count($orders);

            $msj = array("ok" => "true", "operaciones" => $operations);
            return $response->withJson($msj, 200);

        }
        elseif(isset($params['user_id'])){
            $orders = Order::get_orders_by_user($params['start_date'], $params['end_date'], $params['user_id'] );
            $operations = count($orders);

            $msj = array("ok" => "true", "operaciones" => $operations);
            return $response->withJson($msj, 200);
        }
        else{
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        
    }

}



?>