<?php

require_once '../models/Table.php';
require_once '../models/Comanda.php';
require_once '../models/Util.php';
require_once '../models/Order.php';

class TableController{

    public function new_table($request, $reponse){
        $table = new Table();
        $table->new_table();

        $msj = array("ok" => "true", "msj" => "nueva mesa dada de alta!", "mesa" => $table->id);
        return $reponse->withJson($msj, 200);

    }

    public function update_table($request, $response){
        
        $body = $request->getParsedBody();
        
        if(!isset($body['id']) || !isset($body['status'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        if($body['status'] != "CLIENTE ESPERANDO" && $body['status'] != "CLIENTE COMIENDO"
         && $body['status'] && "CLIENTE PAGANDO" ){
            $msj = array("ok" => "false", "msj" => "estado inválido");
            return $response->withJson($msj, 400);
        }

        $table = new Table();
        $table->id = $body['id'];
        $table->status = $body['status'];
        Table::update($table);
        
        $msj = array("ok" => "true", "msj" => " modificado!");
        return $response->withJson($msj, 200);

    }

    public function disable_table($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        Table::disable_table($body['id']);

        $msj = array("ok" => "true", "msj" => "mesa dada de baja");
        return $response->withJson($msj, 200);
    }

    public function open_table($request, $response){
        $body = $request->getParsedBody();

        if(!isset($body['id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $table = Table::find_by_id($body['id'])[0];
        if($table->status != 'CERRADA' || !$table  ){
            $msj = array("ok" => "false", "msj" => "No existe la mesa o la mesa ya se encuentra abierta");
            return $response->withJson($msj, 400);
        }

        $table->status = 'ESPERANDO PEDIDO';
        Table::update($table);
        
        $comanda = new Comanda();
        $comanda->mozo_id = $request->getAttribute('user_id');
        $comanda->table_id = $body['id'];
        $comanda->new_comanda();

        $msj = array("ok" => "true", "msj" => "mesa abierta");
        return $response->withJson($msj, 200);

    }

    public function close_table($request, $response){
        $body = $request->getParsedBody();

        if(!isset($body['table_id']) || !isset($body['comanda_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }
        $table = Table::find_by_id($body['table_id'])[0];
        $comanda = Comanda::find_by_id($body['comanda_id'])[0];
        $orders = json_decode($comanda->orders);
        $amount = 0;

        for ($i=0; $i < count($orders); $i++) { 
            $o = Order::find_by_id($orders[$i])[0];
            if($o->status != 'FINALIZADO' && $o->status != 'FINALIZADO CON DEMORA'){
                Order::update_status($o->id, 'CANCELADO');
            }
            else{
                $amount += floatval($o->amount);
            }
        }

        if( $table->status == 'CLIENTE PAGANDO' ){
            Comanda::update($comanda->id, 'status', 'CANCELADO');
        }
        else{
            Comanda::update($comanda->id, 'status', 'FINALIZADO');
        }

        Comanda::update($comanda->id, 'amount', truncateFloat($amount, 2));

        $table->status = 'CERRADA';
        Table::update($table);

        $msj = array("ok" => "true", "msj" => "mesa cerrada");
        return $response->withJson($msj, 200);

    }

    

    public function all_tables($request, $response){
        $tables = Table::all_activate();
        $msj = $msj = array("ok" => "true", "tables" => $tables);
        return $response->withJson($msj, 200);
    }

    public function get_used_tables($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['query'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $tables = Comanda::get_tables($params['start_date'], $params['end_date'], $params['query']);

        $data = array();

        for ($i=0; $i < count($tables) ; $i++) { 
            if($tables[0]->total == $tables[$i]->total){
                array_push($data,  array("mesa_id" => $tables[$i]->table_id , "veces usada" => $tables[$i]->total ));   
            }
        }

        $msj = array("ok" => "true", "data" => $data);
        return $response->withJson($msj, 200);

    }

    public function get_tables_by_amount($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['query'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $tables = Comanda::get_tables_by_amount($params['start_date'], $params['end_date'], $params['query']);

        $data = array();

        for ($i=0; $i < count($tables) ; $i++) { 
            if($tables[0]->total == $tables[$i]->total){
                array_push($data,  array("mesa_id" => $tables[$i]->table_id , "importe total facturado" => truncateFloat($tables[$i]->total,2) ));   
            }
        }

        $msj = array("ok" => "true", "data" => $data);
        return $response->withJson($msj, 200);

    }

    public function get_tables_by_amount_import($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['query'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $tables = Comanda::get_tables_by_amount_import($params['start_date'], $params['end_date'], $params['query']);

        $data = array();

        for ($i=0; $i < count($tables) ; $i++) { 
            if($tables[0]->amount == $tables[$i]->amount){
                array_push($data,  array("mesa_id" => $tables[$i]->table_id , "importe" => $tables[$i]->amount ));   
            }
        }

        $msj = array("ok" => "true", "data" => $data);
        return $response->withJson($msj, 200);

    }

    public function get_table_by_amount($request, $response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date']) 
        || !isset($params['table_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $table = Comanda::get_table_by_amount($params['start_date'], $params['end_date'], $params['table_id']);
        if(!$table[0]->table_id){
            $msj = array("ok" => "false", "msj" => "no existe la mesa");
        return $response->withJson($msj, 400);
        }


        $data = array( "mesa_id" => $table[0]->table_id , "importe" => truncateFloat($table[0]->total,2));

        $msj = array("ok" => "true", "data" => $data);
        return $response->withJson($msj, 200);

    }

    public function get_opinion($request,$response){
        $params = $request->getParams();

        if(!isset($params['start_date']) || !isset($params['end_date'] )){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $opinion = Comanda::get_opinion_tables($params['start_date'], $params['end_date'] );

        $best = array();
        $worst = array();

        for ($i=0; $i < count($opinion); $i++) { 
            $op = json_decode($opinion[$i]->opinion);
            if($op->mesa < 4){
                array_push($worst, array("mesa_id" => $opinion[$i]->table_id, "puntuación" => $op->mesa,
                "comentarios" => $op->comentarios));
            }
            elseif ($op->mesa > 7) {
                array_push($best, array("mesa_id" => $opinion[$i]->table_id, "puntuación" => $op->mesa,
                "comentarios" => $op->comentarios));
            }
        }

        $msj = array("ok" => "true", "mejores comentarios" => $best, "peores comentarios" => $worst);
        return $response->withJson($msj, 200);
    }


}