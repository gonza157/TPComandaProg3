<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Models\Bebida;
use App\Utils\Re;

class BebidaController {

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $bebida = new Bebida();
        
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        if($data->tipo == 'socio')
        {
            $bebida->descripcion= $req['descripcion'];
            $bebida->precio= $req['precio'] ;
            $rta = json_encode(array("ok" => $bebida->save()));
        }
                

        $response->getBody()->write($rta);

        return $response;
    }   

    public function ModificarBebida(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $bebida = new Bebida();
        $bebida->id = $args['numero'];
        //$empleado->email=$req['email'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $bebida->where('id',$bebida->id)->first();
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
        $bebida = new bebida();
        $bebida->id=$req['numero'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $bebida->where('id',$bebida->id);
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
        $bebida = new Bebida();
        $bebida = Bebida::where('id','=',$id)->first();
        return $bebida->precio;       

    }
    public function getDescripcion($id){
        $bebida = new Bebida();
        $bebida = Bebida::where('id','=',$id)->first();
        return $bebida->descripcion;       

    }
    
    // public function devuelveMesaLibre(){
    //     $mesaLibre=mesa::where('estado','7')->first();
    //     if(isset($mesaLibre)){
    //         return $mesaLibre->id;
    //     }else
    //     {
    //         return 0;
    //     }
    // }

    // public function cambiaEstado($codMesa,$nuevoEstado)
    // {
    //         $mesa=mesa::where('id','=',$codMesa)->first();
    //         $mesa->estado=$nuevoEstado;
    //         $mesa->save();
    // }
    

}