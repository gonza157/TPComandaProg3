<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Mesa;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Utils\Re;

class MesaController {

    public function add(Request $request, Response $response, $args)
    {
        $mesa = new Mesa();
        $mesa->estado='vacia';
        $mesa->facturacion= 0 ;
        $mesa->MaxImporte= 0 ;
        $mesa->MinImporte= 0 ;
        $mesa->usos= 0 ;
        $mesa->codigo = mt_rand(1,99999);
        $rta = json_encode(array("ok" => $mesa->save()));        

        $response->getBody()->write($rta);

        return $response;
    }   

    public function ModificarMesa(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $mesa = new Mesa();
        $mesa->id = $args['idMesa'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $mesa->where('id',$mesa->id)->first();
        if(!empty($selec))
        {
                if($data->tipo == 'socio' )
                {
                    
                    if(isset($req['estado']))
                    {
                        $selec->estado=$req['estado'];
                    }
                    if(isset($req['facturacion']))
                    {
                        $selec->facturacion=$req['facturacion'];
                    }
                    if(isset($req['maximporte']))
                    {
                        $selec->MaxImporte=$req['maximporte'];
                    }
                    if(isset($req['minimporte']))
                    {
                        $selec->MinImporte=$req['minimporte'];
                    }
                    if(isset($req['usos']))
                    {
                        $selec->usos=$req['usos'];
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

    public function BorrarMesa(Request $request, Response $response, $args)
    {
       // var_dump('paso');
        $req= $request->getParsedBody();
        $mesa = new Mesa();
        $mesa->id=$args['idMesa'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $mesa->where('id',$mesa->id);
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

    public function MesaQueMasFacturo(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $max = -2;
        $idM = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->facturacion > $max)
            {
                $max = $selec[$i]->facturacion;
                $idM = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $idM, 'con facturacion :' => $max));

        $response->getBody()->write($rta);

        return $response;
    }

    public function MesaQueMenosFacturo(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $min = 999999;
        $idm = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->facturacion < $min)
            {
                $min = $selec[$i]->facturacion;
                $idm = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $idm, 'con facturacion :' => $min));

        $response->getBody()->write($rta);

        return $response;
    }

    public function MesaMenosUsada(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $min = 9999;
        $id = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->usos < $min)
            {
                $min = $selec[$i]->usos;
                $id = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $id, 'se uso :' => $min));

        $response->getBody()->write($rta);

        return $response;
    }

    public function MesaMasUsada(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $max = -1;
        $id = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->usos > $max)
            {
                $max = $selec[$i]->usos;
                $id = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $id, 'se uso :' => $max));

        $response->getBody()->write($rta);

        return $response;
    }

    public function MesaFacturaMasGrande(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $max = -1;
        $id = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->MaxImporte > $max)
            {
                $max = $selec[$i]->MaxImporte;
                $id = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $id, 'con la factura por :' => $max));

        $response->getBody()->write($rta);

        return $response;
    }

    public function MesaFacturaMasChica(Request $request, Response $response, $args)
    {
        $selec = Mesa::get();
        $min = -1;
        $id = 0;
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->MinImporte < $min)
            {
                $min = $selec[$i]->MinImporte;
                $id = $selec[$i]->id;
            }
        }
        $rta = json_encode(array('La mesa:' => $id, 'con la factura por :' => $min));

        $response->getBody()->write($rta);

        return $response;
    }
    
    public function devuelveMesaLibre(){
        $mesaLibre=mesa::where('estado','7')->first();
        if(isset($mesaLibre)){
            return $mesaLibre->id;
        }else
        {
            return 0;
        }
    }

    public function cambiaEstado($codMesa,$nuevoEstado)
    {
            $mesa=mesa::where('id','=',$codMesa)->first();
            $mesa->estado=$nuevoEstado;
            $mesa->save();
    }

    public function facturacionNueva($codMesa)
    {
            $mesa=mesa::where('id','=',$codMesa)->first();
            $mesa->facturacion=0;
            $mesa->save();
    }

}