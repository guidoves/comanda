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

    public function all_activate_comandas($request, $response){
        $comandas = Comanda::all_active_comandas();
        $msj = $msj = array("ok" => "true", "comandas" => $comandas);
        return $response->withJson($msj, 200);
    }

    



}