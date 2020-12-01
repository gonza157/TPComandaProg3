<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Models\Bebida;
use App\Models\Cerveza;
use App\Utils\Re;

class CervezaController {

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $cerveza = new Cerveza();
        
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        if($data->tipo == 'socio')
        {
            $cerveza->descripcion= $req['descripcion'];
            $cerveza->precio= $req['precio'] ;
            $rta = json_encode(array("ok" => $cerveza->save()));
        }
                

        $response->getBody()->write($rta);

        return $response;
    }   

    public function ModificarBebida(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $cerveza = new Cerveza();
        $cerveza->id = $args['numero'];
        //$empleado->email=$req['email'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $cerveza->where('id',$cerveza->id)->first();
        if(!empty($selec))
        {
            //$selec = $empleado->where('codigo',->codigo)->first();
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

    public function BorrarBebida(Request $request, Response $response, $args)
    {
       // var_dump('paso');
        $req= $request->getParsedBody();
        $cerveza = new Cerveza();
        $cerveza->id=$req['numero'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $cerveza->where('id',$cerveza->id);
        if(!empty($selec))
        {
            //$selec = $empleado->where('email',$empleado->email)->first();
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
        $cerveza = new Cerveza();
        $cerveza = Cerveza::where('id','=',$id)->first();
        return $cerveza->precio;       

    }
    public function getDescripcion($id){
        $cerveza = new Cerveza();
        $cerveza = Cerveza::where('id','=',$id)->first();
        return $cerveza->descripcion;       

    }
    
    

}