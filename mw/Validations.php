<?php

require_once '../models/User.php';

class Validations{
    public function validate_new_user($request,$response,$next){

        $body = $request->getParsedBody();

        $errors = array();
        $dataOk = array();
        
        $reg_user = "/^[a-zA-Z0-9\s]{1,50}+$/";
        

        if ( !isset($body['full_name']) || !isset($body['user_name']) || !isset($body['password']) || !isset($body['role']) ) {
            $msj = array("ok" => "false", "msj" => "No se enviaron los campos requeridos");
            return $response->withJson($msj, 400);
        }

        $checkUsr = User::find_by_username($body['user_name']);

        if(count($checkUsr) > 0){
            $msj = array("ok" => "false", "msj" => "Ya existe el usuario.");
            return $response->withJson($msj, 400);
        }
        
        if(preg_match($reg_user,$body['user_name'])){
            
            array_push($dataOk,'user_name');
        }
        else{
            //$errores['nombre'] = 'Ingreso invalido. Solo letras';
            array_push($errors,'user_name');
        }

        if(strlen($body['password']) > 3) {
            array_push($dataOk,'password');
        }
        else{
            array_push($errors,'password');
        }

        if($body['role'] == 'SOCIO' || $body['role'] == 'MOZO' || $body['role'] == 'BARTENDER' || 
        $body['role'] == 'CERVECERO' || $body['role'] == 'COCINERO') {
            array_push($dataOk,'role');
        }
        else{
            array_push($errors,'role');
        }

        if(count($dataOk) != 3){
            $msj = array("ok" => "false", "err" => $errors);
            return $response->withJson($msj, 400);
        }

        return $next($request, $response);
        
    }
}

?>