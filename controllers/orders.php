<?php

require_once '../models/Order.php';
require_once '../models/Menu.php';
require_once '../models/Comanda.php';

class OrderController{

    public function new_order($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['menu_id']) || !isset($body['comanda_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $menu = Menu::find_by_id($body['menu_id'])[0];
        if(!$menu){
            $msj = array("ok" => "false", "msj" => "No existe el plato");
            return $response->withJson($msj, 400);
        }
        
        $order = new Order();
        $order->order_type = $menu->type;
        $order->name = $menu->name;
        $order->amount = $menu->amount;

        $id = $order->new_order();
        $comanda = Comanda::find_by_id($body['comanda_id'])[0];
        $orders = json_decode($comanda->orders);
        array_push($orders, $id);
        $order_to_save = json_encode($orders); 
        Comanda::update($body['comanda_id'], 'orders', $order_to_save);


        $msj = array("ok" => "true", "msj" => "nuevo pedido!", "order" => $order);
        return $response->withJson($msj, 200);
    }

    public function close($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['order_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $order = Order::find_by_id($body['order_id'])[0];
        
        if($order->status == 'FINALIZADO' || $order->status == 'FINALIZADO CON DEMORA'){
            $msj = array("ok" => "false", "msj" => 'El pedido ya se encuentra finalizado');
            return $response->withJson($msj, 400);
        }
        
        $order_start_date = new DateTime($order->date_start);
        $now = new DateTime(date('Y-m-d h:i:s'));
        $min_diff = $order_start_date->diff($now)->i;
        
        if($min_diff > $order->estimated_time){
            Order::update_status($order->id, 'FINALIZADO CON DEMORA');
        }
        else{
            Order::update_status($order->id, 'FINALIZADO');
        }
        
        $msj = array("ok" => "true", "msj" => "estado modificado!");
        return $response->withJson($msj, 200);
    }

    public function update_status($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['status']) || !isset($body['order_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        if($body['status'] != 'EN PREPARACION' && $body['status'] != 'LISTO PARA SERVIR'){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $order = Order::find_by_id($body['order_id'])[0];

        if($order->status == 'PENDIENTE'){
            if(!isset($body['estimated_time'])){
                $msj = array("ok" => "false");
                return $response->withJson($msj, 400);
            }
            Order::set_order($order->id, 'EN PREPARACION', $request->getAttribute('user_id'), $body['estimated_time'] );
        }
        else{
            Order::update_status($order->id, $body['status']);
        }


        $msj = array("ok" => "true", "msj" => "pedido actualizado!");
        return $response->withJson($msj, 200);

    }

    public function all_by_type($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['order_type'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $orders = Order::all_by_sector($body['order_type']);

        $msj = array("ok" => "true", "orders" => $orders);
        return $response->withJson($msj, 200);
    }

    public function all_activate($request, $response){
        $orders = Order::all_activate();
        $msj = array("ok" => "true", "orders" => $orders);
        return $response->withJson($msj, 200);
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

    public function get_delayed_orders($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $orders = Order::get_orders($params['start_date'], $params['end_date'], 'FINALIZADO CON DEMORA');
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