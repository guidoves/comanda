<?php
require_once 'AccesoDatos.php';
require_once 'Util.php';

class Table{
    public $id;
    public $status;
    public $identifier; 

    public function new_table(){
        
        $tables = count(Table::all());
        $identifier = 'ME' . generarCodigo(3);
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into tables (identifier)values('$identifier')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }

    public static function update($table){
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();        
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE tables set status=:status WHERE id=:id");
        $consulta->bindValue(':status', $table->status, PDO::PARAM_STR);
        $consulta->bindValue(':id', $table->id, PDO::PARAM_INT);
        $consulta->execute();

        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function all(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id, status, identifier FROM tables WHERE is_activated=:activated");
        $consulta->bindValue(':activated', 1, PDO::PARAM_INT);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Table");
    }

}