<?php

require_once '../models/Table.php';

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


}