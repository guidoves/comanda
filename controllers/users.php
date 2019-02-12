<?php

require_once '../models/User.php';

class UserController{

    public function new_user($request, $reponse){

        $body = $request->getParsedBody();
        $user = new User();
        $user->user_name = $body['user_name'];
        $user->password = $body['password'];
        $user->role = $body['role'];
        $user->full_name = $body['full_name'];

        $user->new_user();

        $msj = array("ok" => "true", "msj" => "nuevo usuario dado de alta!", "user" => $user->user_name);
        return $reponse->withJson($msj, 200);


    }

    public function update_user($request, $reponse){
        
        $body = $request->getParsedBody();
        $user = new User();
        $user->id = $body['id'];
        $user->user_name = $body['user_name'];
        $user->role = $body['role'];
        $user->is_activated = $body['status'];

        User::update($user);
        
        $msj = array("ok" => "true", "msj" => "usuario modificado!");
        return $reponse->withJson($msj, 200);

    }



}



?>