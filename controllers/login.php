<?php

require_once 'autentificadorJWT.php';
require_once '../models/User.php';
require_once '../models/Login.php';

class Logins{
    
    public function login($request, $response){
        $body = $request->getParsedBody();

        if(!isset($body['user']) || !isset($body['password'])){
            $msj = array("ok" => "false", "err" => "campos inválidos");
    
            return $response->withJson($msj, 400);
        }

        $user = User::find_by_username($body['user']);


        if(!$user){
            $msj = array("ok" => "false", "err" => "Usuario o contraseña incorrectos");
            return $response->withJson($msj, 400);  
        }

        
        $password = sha1($body['password']);
        
        if($password != $user[0]->password){
            $msj = array("ok" => "false", "err" => "Usuario o contraseña incorrectos");
            return $response->withJson($msj, 400);
        }
        
        $user = array("id" => $user[0]->id, "user_name" => $user[0]->user_name, 
        "role" => $user[0]->role);

        $token = AutentificadorJWT::CrearToken($user);

        $msj = array("ok" => "true", "token" => $token, "user" => $user);

        $login = new Login();
        $login->user_id = $user['id'];
        $login->new_login();

        return $response->withJson($msj, 200);
    }

    public function checkToken($request, $response){

        $body = $request->getParsedBody();
        $token = $body['token'];
        
        try{
            AutentificadorJWT::VerificarToken($token);
            $data = AutentificadorJWT::ObtenerData($token);
            $msj = array("ok" => "true", "user" => $data->user_name, "role" => $data->role);
            return $response->withJson($msj, 200);
        } catch( Exception $e ){
            $msj = array("ok" => "false", "err" => "token invalido");
            return $response->withJson($msj, 400);
        }

    }
    
}



?>