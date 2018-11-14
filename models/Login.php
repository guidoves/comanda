<?php
require_once 'AccesoDatos.php';

class Login{

    public $id;
    public $user_id;
    public $login_date;

    public function new_login(){

        $login_date = date("j F, Y, g:i a");
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into logins (user_id, login_date)values('$this->user_id','$login_date')");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

}


?>