<?php
require_once 'AccesoDatos.php';

class Login{

    $id;
    $user_id;
    $login;

    public function new_login(){

        $login = date("j F, Y, g:i a");
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into logins (user_id, login)values('$this->user_id','$login')");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

}


?>