<?php

require_once 'AccesoDatos.php';

class Menu{
    public $id;
    public $type;
    public $name;

    public function new(){
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into menu (type, name)values('$this->type','$this->name')");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

    public static function all_by_type($type){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id, name, type FROM menu WHERE type=:type");
        $consulta->bindValue(':type', $type, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Menu");
    }
}