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

}