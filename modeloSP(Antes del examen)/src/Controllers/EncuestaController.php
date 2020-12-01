<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Models\Encuesta;
use App\Utils\Re;

class EncuestaController {

    public function add($mesa,$resto,$mozo,$cocinero,$descripcion)
    {
        $flag = false;
        $encuesta = new Encuesta();
        $encuesta->mesa=$mesa;
        $encuesta->restaurante=$resto;
        $encuesta->mozo=$mozo;
        $encuesta->cocinero=$cocinero;
        $encuesta->resenia = $descripcion;
                if($encuesta->mesa >0 && $encuesta->mesa < 11 )
                {
                    $flag = true;
                } 
                if($encuesta->restaurante >0 && $encuesta->restaurante < 11 )
                {
                    $flag = true;
                }
                if($encuesta->cocinero >0 && $encuesta->cocinero < 11 )
                {
                    $flag = true;
                }
                if($encuesta->mozo >0 && $encuesta->mozo < 11 )
                {
                    $flag = true;
                }
                if(strlen($encuesta->resenia)>=0 && strlen($encuesta->resenia)<=66 )
                {
                    $flag = true;
                }
                if($flag == true)
                {
                    $rta = json_encode(array("ok" => $encuesta->save()));
                }else{
                    $rta = json_encode(array("calificacion fuera de los rangos" ));
                }                
                
        $response->getBody()->write($rta);

        return $response;
    }

    public function addRut(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $flag = false;
        $encuesta = new Encuesta();
        $encuesta->mesa=$req['mesa'];
        $encuesta->restaurante=$req['resto'];
        $encuesta->mozo=$req['mozo'];
        $encuesta->cocinero=$req['cocinero'];
        $encuesta->resenia=$req['resenia'];
                if($encuesta->mesa >0 && $encuesta->mesa < 11 )
                {
                    $flag = true;
                } 
                if($encuesta->restaurante >0 && $encuesta->restaurante < 11 )
                {
                    $flag = true;
                }
                if($encuesta->cocinero >0 && $encuesta->cocinero < 11 )
                {
                    $flag = true;
                }
                if($encuesta->mozo >0 && $encuesta->mozo < 11 )
                {
                    $flag = true;
                }
                if(strlen($encuesta->resenia)>=0 && strlen($encuesta->resenia)<=66 )
                {
                    $flag = true;
                }
                if($flag == true)
                {
                    $rta = json_encode(array("ok" => $encuesta->save()));
                }else{
                    $rta = json_encode(array("calificacion fuera de los rangos" ));
                }
                
                
        $response->getBody()->write($rta);

        return $response;
    }
   

    public function ModificarEncuesta(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $encuesta->where('id','=',$req['id'])->first();
        if(!empty($selec))
        {
                if($data->tipo == 'socio')
                {
                    
                    if(isset($req['mesa']))
                    {
                        $selec->mesa=$req['mesa'];
                    }
                    if(isset($req['resto']))
                    {
                        $selec->restaurante=$req['resto'];
                    }
                    if(isset($req['mozo']))
                    {
                        $selec->mozo=$req['mozo'];
                    }
                    if(isset($req['cocinero']))
                    {
                        $selec->cocinero=$req['cocinero'];
                    }
                    if(isset($req['resenia']))
                    {
                        $selec->resenia=$req['resenia'];
                    }
                    $rta = json_encode(array("ok" => $selec->save()));
                }else{
                    $rta = json_encode(array("Error No tienes lo permisos suficientes" ));
                }
        }else
        {
            $rta = json_encode(array("Esrror esta encuesta no existe" ));
        }       
        

        $response->getBody()->write($rta);

        return $response;
    }

    public function BorrarUnEmpleado(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $encuesta->where('id','=',$req['email']);
        if(!empty($selec))
        {
                if($data->tipo == 'socio' )
                {                
                    $rta = json_encode(array("ok" => $selec->delete()));

                }else{
                    $rta = json_encode(array("Error No tienes lo permisos suficientes" ));
                }
        }else
        {
            $rta = json_encode(array("Esrror esta mail no existe" ));
        }       
        

        $response->getBody()->write($rta);

        return $response;
    }

    public function ListaEncuestas(Request $request, Response $response, $args)
    {
        $selec = Encuestas::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
                array_push($lista,$selec[$i]);

        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }
}