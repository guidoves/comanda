<?php
require_once 'AccesoDatos.php';

class Order{

    public $id;
    public $user_id;
    public $order_type;
    public $status;
    public $finalized;
    public $estimated_time;

    public function new_order(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into orders (user_id, order_type, estimated_time)values('$this->user_id','$this->order_type','$this->estimated_time')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

}