<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Models\Comida;
use App\Utils\Re;

class ComidaController {

public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $comida = new Comida();
        
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        if($data->tipo == 'socio')
        {
            $comida->descripcion= $req['descripcion'];
            $comida->precio= $req['precio'] ;
            $rta = json_encode(array("ok" => $comida->save()));
        }
                

        $response->getBody()->write($rta);

        return $response;
    }   

    public function ModificarComida(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $comida = new Comida();
        $comida->id = $args['numero'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $comida->where('id',$comida->id)->first();
        if(!empty($selec))
        {
                if($data->tipo == 'socio' )
                {
                    
                    if(isset($req['descripcion']))
                    {
                        $selec->descripcion=$req['descripcion'];
                    }
                    if(isset($req['precio']))
                    {
                        $selec->precio=$req['precio'];
                    }
                    
                    $rta = json_encode(array("ok" => $selec->save()));
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

    public function BorrarComida(Request $request, Response $response, $args)
    {
       // var_dump('paso');
        $req= $request->getParsedBody();
        $comida = new Comida();
        $comida->id=$req['numero'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $comida->where('id',$comida->id);
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
            $rta = json_encode(array("Esrror esta mesa no existe" ));
        }       
        

        $response->getBody()->write($rta);

        return $response;
    }

    public function getPrecio($id){
        $comida = new Comida();
        $comida = Comida::where('id','=',$id)->first();
        //var_dump($comida);
        return $comida->precio;       

    }

    public function getDescripcion($id){
        $comida = new Comida();
        $comida = Comida::where('id','=',$id)->first();
        return $comida->descripcion;     
    }
    

}