<?php

require_once '../models/Table.php';
require_once '../models/Comanda.php';
require_once '../models/Util.php';

class TableController{

    public function new_table($request, $reponse){

        $body = $request->getParsedBody();
        $table = new Table();
        $table->new_table();

        $msj = array("ok" => "true", "msj" => "nueva tabla dada de alta!", "table" => $table->id);
        return $reponse->withJson($msj, 200);

    }

    public function update_table($request, $reponse){
        
        $body = $request->getParsedBody();
        $table = new Table();
        $table->id = $body['id'];
        $table->status = $body['status'];
        Table::update($table);
        
        $msj = array("ok" => "true", "msj" => " modificado!");
        return $reponse->withJson($msj, 200);

    }

    public function all_tables($request, $response){
        $tables = Table::all();
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