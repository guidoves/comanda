<?php
require_once 'AccesoDatos.php';

class Order{

    public $id;
    public $user_id;
    public $order_type;
    public $status;
    public $estimated_time;
    public $name;
    public $date;
    public $date_start;

    public function new_order(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into orders (user_id, order_type, estimated_time, name, date, date_start)values('$this->user_id','$this->order_type','$this->estimated_time','$this->name', NOW(), NOW())");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public static function get_orders($start, $end, $status){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM orders WHERE status=:status AND date BETWEEN '$start' AND '$end' ");
        $consulta->bindValue(':status', $status, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Order");
    }

    public static function get_orders_by_sector($start, $end, $order_type){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM orders WHERE order_type=:order_type AND (status='FINALIZADO' OR status='FINALIZADO CON DEMORA') AND date BETWEEN '$start' AND '$end' ");
        $consulta->bindValue(':order_type', $order_type, PDO::PARAM_STR);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Order");
    }

    public static function get_orders_by_sector_user($start, $end, $order_type, $user_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM orders WHERE order_type=:order_type AND (status='FINALIZADO' OR status='FINALIZADO CON DEMORA') AND user_id=:user_id AND date BETWEEN '$start' AND '$end' ");
        $consulta->bindValue(':order_type', $order_type, PDO::PARAM_STR);
        $consulta->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Order");
    }

    public static function get_orders_by_user($start, $end, $user_id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM orders WHERE user_id=:user_id AND (status='FINALIZADO' OR status='FINALIZADO CON DEMORA') AND date BETWEEN '$start' AND '$end'  ");
        $consulta->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Order");
    }

    public static function get_sales($start, $end, $query){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT name, COUNT(name) as total FROM orders WHERE (status='FINALIZADO' OR status='FINALIZADO CON DEMORA') AND date BETWEEN '$start' AND '$end' GROUP BY name ORDER BY total $query");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Order");
    }

    
}