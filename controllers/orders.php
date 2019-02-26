<?php

require_once '../models/Order.php';
require_once '../models/Menu.php';
require_once '../models/Comanda.php';

class OrderController{

    public function new_order($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['order_type']) || !isset($body['estimated_time']) 
        || !isset($body['menu_id']) || !isset($body['comanda_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $menu = Menu::all_by_type($body['order_type']);
        if( count($menu) == 0 ){
            $msj = array("ok" => "false", "msj" => "No existe el plato");
            return $response->withJson($msj, 400);
        }

        $menu_name = '';

        for ($i=0; $i < count($menu); $i++) { 
            if( $menu[$i]->id == $body['menu_id'] ){
                $menu_name = $menu[$i]->name;
            }
        }

        if($menu_name = ''){
            $msj = array("ok" => "false", "msj" => "No existe el plato");
            return $response->withJson($msj, 400);
        }
        
        /* $order = new Order();
        $order->user_id = $request->getAttribute('user_id');
        $order->order_type = $body['order_type'];
        $order->estimated_time = $body['estimated_time'];
        $order->name = $menu_name;

        $order->new_order(); */
        $comanda = Comanda::find_by_id($body['comanda_id']);
        
        var_dump($comanda->orders);

        $test = json_decode($comanda->orders);

        die();

        $msj = array("ok" => "true", "msj" => "nuevo pedido!", "order" => $order);
        return $reponse->withJson($msj, 200);
    }

    public function get_orders($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['status'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $orders = Order::get_orders($params['start_date'], $params['end_date'], $params['status']);

        $msj = array("ok" => "true", "orders" => $orders);
        return $response->withJson($msj, 200);
        
    }

    public function get_best_seller($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['query'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $sales = Order::get_sales($params['start_date'], $params['end_date'], $params['query']);

        $data = array();

        for ($i=0; $i < count($sales) ; $i++) { 
            if($sales[0]->total == $sales[$i]->total){
                array_push($data,  array("plato" => $sales[$i]->name , "ventas" => $sales[$i]->total ));   
            }
        }

        $msj = array("ok" => "true", "data" => $data);
        return $response->withJson($msj, 200);

    }

}