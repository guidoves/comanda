<?php
require_once 'AccesoDatos.php';

class Usuario{
    
    public $id;
    public $user_name;
    public $user_pass;
    public $ppt;
    public $vaa;
    public $ans;
    public $ana;
    public $ttt;
    public $pra;

    // DATOS : ALTA - BAJA - MODIFICAR - LISTAR - ETC.
    
    public function Alta(){
        $pass = sha1($this->user_pass);
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (user_name, user_pass)values('$this->user_name','$pass')");
        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
        
    }
    public function Baja(){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE usuarios set
         estado=:estado WHERE id=:id");	
            $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
            $consulta->bindValue(':estado', false, PDO::PARAM_BOOL);		
            $consulta->execute();
            return $consulta->rowCount();
    }
    public function Modificar(){
        /* $adm = (boolean)$this->adm; */
       /*  $pass = sha1($this->clave); */ // encripta la clave en sha;
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios
        set ppt=:ppt,
        vaa=:vaa,
        ans=:ans,
        ana=:ana,
        ttt=:ttt,
        pra=:pra
        WHERE id=:id");
        $consulta->bindValue(':ppt',$this->id,PDO::PARAM_INT);
        $consulta->bindValue(':vaa',$this->nombre,PDO::PARAM_INT);
        $consulta->bindValue(':ans',$this->email,PDO::PARAM_INT);
        $consulta->bindValue(':ana',$this->email,PDO::PARAM_INT);
        $consulta->bindValue(':ttt',$this->email,PDO::PARAM_INT);
        $consulta->bindValue(':pra',$this->email,PDO::PARAM_INT);
        return $consulta->execute();
    }
    /* public static function BajaPorId($id){
        
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE Helado set
         estado=:estado WHERE id=:id");	
            $consulta->bindValue(':id',$id, PDO::PARAM_INT);
            $consulta->bindValue(':estado', false, PDO::PARAM_BOOL);		
            $consulta->execute();
            return $consulta->rowCount();
    } */
    public static function TraerTodos(){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("SELECT id,user_name,ppt,vaa,ans,ana,ttt,pra FROM usuarios");
        $consulta->execute();			
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Usuario");
    }
    /* public static function BuscarPorId($id){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT id,sabor,tipo,kilos,estado FROM Helado WHERE id=:id");
        $consulta->bindValue(':id',$id,PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS,"Helado");
    } */
}
?>
