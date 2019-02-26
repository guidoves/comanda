<?php

require_once '../models/Comanda.php';

class ComandaController{

    public function new_comanda($request, $response){

        $body = $request->getParsedBody();
        $comanda = new Comanda();
        $comanda->mozo_id = $body['mozo_id'];
        $comanda->table_id = $body['table_id'];
        
        $comanda->new_comanda();
        $comanda_to_response = array('identifier' => $comanda->identifier, 'table_id' => $comanda->table_id, 'date' => $comanda->date);

        $msj = array("ok" => "true", "msj" => "nueva comanda!", "comanda" => $comanda_to_response);
        return $response->withJson($msj, 200);

    }

    public function update_client_name($request, $response){
        $body = $request->getParsedBody();

        if(!isset($body['comanda_id']) || !isset($body['client_name'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);
        }

        $update = Comanda::update($body['comanda_id'], 'client_name', $body['client_name']);

        if($update == 0 ){
            $msj = array("ok" => "false", "msj" => "no existe la comanda");
            return $response->withJson($msj, 400);
        }

        $msj = array("ok" => "true", "msj" => "nombre del cliente acutalizado");
        return $response->withJson($msj, 200);

    }

    public function all_activate_comandas($request, $response){
        $comandas = Comanda::all_active_comandas();
        $msj = $msj = array("ok" => "true", "comandas" => $comandas);
        return $response->withJson($msj, 200);
    }

    



}