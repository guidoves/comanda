<?php
require_once 'AccesoDatos.php';

class User{

    public $id;
    public $user_name;
    public $password;
    public $role;
    public $created;
    public $updated;
    public $is_activated;

    public function new_user(){
        $role = $this->role;
        $pass = sha1($this->password);
        $created = date("j F, Y, g:i a");
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into users (user_name, password, role, created, is_activated)values('$this->user_name','$pass','$this->role','$created','1')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }


}



?>