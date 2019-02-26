<?php

require_once 'AccesoDatos.php';

class Menu{
    public $id;
    public $type;
    public $name;
    public $amount;

    public function new(){
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into menu (type, name, amount)values('$this->type','$this->name','$this->amount')");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

    public static function all_by_type($type){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id, name, type, amount FROM menu WHERE type=:type");
        $consulta->bindValue(':type', $type, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Menu");
    }

    public static function delete_menu($menu_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM menu WHERE id=:id");
        $consulta->bindValue(':id', $menu_id, PDO::PARAM_INT);
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
}