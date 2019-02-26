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
    public $date_stats;

    public function new_comanda(){

        $this->date = $login_date = date("j F, Y, g:i a");
        $this->identifier = generarCodigo(5);

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into comandas (table_id, date, mozo_id, identifier, date_stats)values('$this->table_id','$this->date','$this->mozo_id','$this->identifier', NOW())");
        $consulta->execute();
        
        return $objetoAccesoDato->RetornarUltimoIdInsertado();    

    }

    public static function all_active_comandas(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from comandas WHERE status =:status");
        $consulta->bindValue(':status','ABIERTA', PDO::PARAM_STR);
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");    
    }

    public static function update($id, $col, $val){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();        
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE comandas set $col=:val WHERE id=:id");
        $consulta->bindValue(':val', $val, PDO::PARAM_STR);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function finish_comanda($table_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();        
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE comandas set status=:status WHERE table_id=:id AND status='CLIENTE PAGANDO'");
        $consulta->bindValue(':status', 'FINALIZADO', PDO::PARAM_STR);
        $consulta->bindValue(':id', $table_id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->rowCount();
    }

    public static function get_tables($start, $end, $query){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT table_id, COUNT(table_id) as total FROM comandas WHERE (status='FINALIZADO') AND date_stats BETWEEN '$start' AND '$end' GROUP BY table_id ORDER BY total $query");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function get_tables_by_amount($start, $end, $query){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT table_id, SUM(amount) as total FROM comandas WHERE (status='FINALIZADO') AND date_stats BETWEEN '$start' AND '$end' GROUP BY table_id ORDER BY total $query");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function get_table_by_amount($start, $end, $table_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT table_id, SUM(amount) as total FROM comandas WHERE table_id=:table_id AND (status='FINALIZADO') AND date_stats BETWEEN '$start' AND '$end'");
        $consulta->bindValue(':table_id',$table_id, PDO::PARAM_INT);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function get_tables_by_amount_import($start, $end, $query){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT table_id, amount FROM comandas WHERE (status='FINALIZADO') AND date_stats BETWEEN '$start' AND '$end' ORDER BY amount $query");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function get_opinion_tables($start, $end){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT table_id, opinion FROM comandas WHERE (status='FINALIZADO') AND date_stats BETWEEN '$start' AND '$end'");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function find_by_id($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM comandas WHERE id=:id");
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }

    public static function find_by_identifier($identifier){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM comandas WHERE identifier=:identifier");
        $consulta->bindValue(':identifier', $identifier, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Comanda");
    }


}