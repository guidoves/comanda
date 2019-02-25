<?php
require_once 'AccesoDatos.php';
require_once 'Util.php';

class Comanda{
    public $id;
    public $client_name;
    public $orders;
    public $amount;
    public $opinion;
    public $identifier;
    public $table_id;
    public $date;
    public $photo;
    public $mozo_id;
    public $status;

    public function new_comanda(){

        $this->date = $login_date = date("j F, Y, g:i a");
        $this->identifier = generarCodigo(5);

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into comandas (table_id, date, mozo_id, identifier)values('$this->table_id','$this->date','$this->mozo_id','$this->identifier')");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();    

    }

    public static function all_active_comandas(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from comandas WHERE status =:status");
        $consulta->bindValue(':status','ABIERTA', PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Table");    
    }


}