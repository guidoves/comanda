<?php

require_once '../models/Menu.php';

class MenuController{

    public function new_menu($request, $response){

        $body = $request->getParsedBody();
        
        if(!isset($body['type']) || !isset($body['name']) || !isset($body['amount']) ){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);    
        }
        
        $menu = new Menu();
        $menu->type = $body['type'];
        $menu->name = $body['name'];
        $menu->amount = $body['amount'];

        $menu->new();

        $msj = array("ok" => "true", "msj" => "nuevo menu");
        return $response->withJson($msj, 200);

    }

    public function delete_menu($request, $response){
        $body = $request->getParsedBody();
        
        if(!isset($body['menu_id'])){
            $msj = array("ok" => "false");
            return $response->withJson($msj, 400);    
        }
        
        Menu::delete_menu($body['menu_id']);
        $msj = array("ok" => "true", "msj" => "menu eliminado");
        return $response->withJson($msj, 200);    
    }

    public function all_menus($request, $response){
        $menus = Menu::all();
        $msj = array("ok" => "true", "menus" => $menus);
        return $response->withJson($msj, 200);
    }




}