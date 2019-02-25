<?php
require_once 'AccesoDatos.php';

class Login{

    public $id;
    public $user_id;
    public $login_date;
    public $date;

    public function new_login(){

        $login_date = date("j F, Y, g:i a");
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into logins (user_id, login_date, date)values('$this->user_id',NOW(), NOW())");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

    public static function get_logins($start, $end, $user_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM logins WHERE date BETWEEN '$start' AND '$end' AND user_id=:user_id");
        $consulta->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Login");
    }

}


?>