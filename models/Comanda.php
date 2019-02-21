<?php
require_once 'AccesoDatos.php';
require_once 'User.php';

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

    public function new_comanda(){

        $date = $login_date = date("j F, Y, g:i a");
        $mozo = User::find_by_id($this->mozo_id);

        $identifier = substr('a', 0 , 4);

        var_dump($identifier);
        die();

        

    }


}