<?php

require_once '../models/Order.php';

class OrderController{

    public function new_order($request, $response){

        $body = $request->getParsedBody();
        $order = new Order();
        $order->user_id = $body['user_id'];
        $order->order_type = $body['order_type'];
        $order->estimated_time = $body['estimated_time'];

        $order->new_order();

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