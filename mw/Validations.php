<?php

class Validations{
    public function validate_new_user($request,$response,$next){
        
        $body = $request->getParsedBody();

        $errors = array();
        $dataOk = array();
        
        $reg_nombre_apellido = "/^[a-zA-ZÑñáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]{1,50}+$/";
        
        if (!isset($body['user_name']) || !isset($body['password']) || !isset($body['role']) ) {
            $msj = array("ok" => "false", "msj" => "No se enviaron los campos requeridos");
            return $response->withJson($msj, 400);
        }

        var_dump(strlen($body['password']));
        

        if(strlen($body['password'] > 3)) {
            array_push($dataOk,'password');
        }
        else{
            array_push($errors,'password');
        }


        if(preg_match($reg_nombre_apellido,$body['user_name'])){
            //$dataOk[] = 'user_name';
            array_push($dataOk,'user_name');
        }
        else{
            //$errores['nombre'] = 'Ingreso invalido. Solo letras';
            array_push($errors,'user_name');
        }

        if($body['role'] == 'SOCIO' || $body['role'] == 'MOZO' || $body['role'] == 'BARTENDER' || 
        $body['role'] == 'CERVECERO' || $body['role'] == 'COCINERO') {
            array_push($dataOk,'role');
        }
        else{
            array_push($errors,'role');
        }

        if(count($dataOk) != 3){
            $msj = array("ok" => "false", "err" => $errors);
            return $response->withJson($msj, 400);
        }
        
        var_dump($dataOk);
        var_dump($errors);

        die();
        
        $next($request,$response);
        

        /* //Comprueba si llegaron los campos requeridos
        if(isset($request->getParsedBody()['nombre']) && isset($request->getParsedBody()['email']) && 
        isset($request->getParsedBody()['sexo']) && isset($request->getParsedBody()['clave']) &&
        isset($request->getParsedBody()['turno']) && isset($request->getParsedBody()['adm'])){
            //nombre
            if(!empty($request->getParsedBody()['nombre'])){
                if(preg_match($reg_nombre_apellido,$request->getParsedBody()['nombre'])){
                    $dataOk[] = 'nombre';
                }
                else{
                    $errores['nombre'] = 'Ingreso invalido. Solo letras';
                }
            }
            else{
                $errores['nombre'] = "El nombre esta vacio";
            }
            //email
            if(!empty($request->getParsedBody()['email'])){
                if(preg_match($reg_email,$request->getParsedBody()['email'])){
                    if(count(Empleado::BuscarPorEmail($request->getParsedBody()["email"])) == 0)
                    {
                        $dataOk[] = "email";
                    }
                    else{
                        $errores['email'] = "El email ya se encuentra registrado";
                    }
                }
                else{
                    $errores['email'] = "Ingreso invalido. Formato invalido";
                }
            }
            else{
                $errores['email'] = "El email esta vacio";
            }
            //sexo
            if(!empty($request->getParsedBody()['sexo'])){
                if(Sexo::MASCULINO == $request->getParsedBody()['sexo'] || Sexo::FEMENINO == $request->getParsedBody()['sexo'] ){
                    $dataOk[] = "sexo";
                }
                else{
                    $errores['sexo'] = "Ingreso invalido. Formato invalido";
                }
            }
            else{
                $errores['sexo'] = "El sexo esta vacio";
            }
            //turno
            if(!empty($request->getParsedBody()['turno'])){
                if(Turnos::DIURNO == $request->getParsedBody()['turno'] || Turnos::VESPERTINO == $request->getParsedBody()['turno'] || Turnos::NOCTURNO == $request->getParsedBody()['turno'] ){
                    $dataOk[] = "turno";
                }
                else{
                    $errores['turno'] = "Ingreso invalido. Formato invalido";
                }
            }
            else{
                $errores['turno'] = "El turno esta vacio";
            }
            //adm
            if(!empty($request->getParsedBody()['adm'])){
                if($request->getParsedBody()['adm'] == Admin::TRUE || $request->getParsedBody()['adm'] == Admin::FALSE){
                
                    $dataOk[] = "adm";
                }
                else{
                    $errores['adm'] = "Ingreso invalido. Formato invalido";
                }
            }
            else{
                $errores['adm'] = "El adm esta vacio";
            }
            //clave
            if(!empty($request->getParsedBody()['clave'])){
                if(preg_match($reg_clave,$request->getParsedBody()['clave'])){
                    $dataOk[] = "clave";
                }
                else{
                    $errores['clave'] = "Ingreso invalido. Formato invalido";
                }
            }
            else{
                $errores['clave'] = "el campo clave esta vacio";
            }
    
            if(count($dataOk) == 6){
                //TODO OK!
                $response = $next($request,$response);
                return $response;
            }
            else{
                //HAY ERRORES.
                return $response->withJson($errores,500);
            }
        }
        else{
            $response->write('No se han especificado los campos requeridos');
            $response->withStatus(500);
            return $response;
        } */
    }
}

?>