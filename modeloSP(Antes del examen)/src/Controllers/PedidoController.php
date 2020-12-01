<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Models\Comida;
use App\Models\Bebida;
use App\Models\Cerveza;
use App\Models\Encuesta;
use App\Utils\Re;

class PedidoController {


    public function add(Request $request, Response $response, $args)
    {
        //var_dump($foto);
        $req= $request->getParsedBody();
        $rta = '';
        $pedido = new Pedido();
        $pedido->codigo=$req['codigo'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        try{
            var_dump('p1');
            $selec = $pedido->where('codigo',$pedido->codigo)->first();
                if(empty($selec) )
                {
                    var_dump('p2');
                    if($data->tipo == 'mozo' )
                    {
                        var_dump('p3');
                        $pedido->codigo = PedidoController::generateRandomTicket();
                        $pedido->idMesa= $req['mesa'];
                        $pedido->estado= 'en preparacion';
                        $pedido->comida=$req['comida'];
                        $pedido->bebida=$req['bebida'];
                        $pedido->cerveza=$req['cerveza'];
                        $pedido->tiempoEstimado=$req['tiempoEstimado'];
                        MesaController::cambiaEstado($pedido->idMesa,'cliente esperando el pedido');
                        $mesa= Mesa::where('id','=',$req['mesa'])->first();
                        $mesa->usos = $mesa->usos + 1 ;
                        $mesa->save();
                        $rta = json_encode(array("ok" => $pedido->save()));
                    }else{
                        $rta = json_encode(array("No puede llevar a cabo esta accion revise su estado actual" ));
                    }
                   
                }else{
                    $rta = json_encode(array("Error este pedido existe" ));
                }
        }catch(\Exception $e){
            $rta= json_encode(array("Fallo al querer crear pedido" ));
        }     

        $response->getBody()->write($rta);

        return $response;
    }
    public function generateRandomTicket() {
        $length=5;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }   

    public function ModificarPedido(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $pedido = new Pedido();
        $pedido->codigo = $args['codigo'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        try{
            $selec = $pedido->where('codigo',$pedido->codigo)->first();
            if(!empty($selec))
            {
                    if($data->tipo == 'socio' || ($data->tipo == 'mozo' && $data->estado == 'activo'))
                    {
                        
                        if(isset($req['estado']))
                        {
                            $selec->estado=$req['estado'];
                        }
                        if(isset($req['mesa']))
                        {
                            $selec->idMesa=$req['mesa'];
                        }
                        if(isset($req['comida']))
                        {
                            $selec->comida=$req['comida'];
                        }
                        if(isset($req['bebida']))
                        {
                            $selec->bebida=$req['bebida'];
                        }
                        if(isset($req['cerveza']))
                        {
                            $selec->cerveza=$req['cerveza'];
                        }
                        
                        $rta = json_encode(array("ok" => $selec->save()));
                    }else{
                        $rta = json_encode(array("Error No tienes lo permisos suficientes" ));
                    }
            }else
            {
                $rta = json_encode(array("Esrror este pedido no existe" ));
            }
        }catch(\Exception $e){
            $rta= json_encode(array("Fallo al querer modificar pedido" ));
        }      
        

        $response->getBody()->write($rta);

        return $response;
    }

    public function BorrarPedido(Request $request, Response $response, $args)
    {
       // var_dump('paso');
        $req= $request->getParsedBody();
        $pedido = new Pedido();
        $pedido->codigo=$req['codigo'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        try{
            $selec = $pedido->where('codigo',$pedido->codigo)->first();
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
                $rta = json_encode(array("Esrror este pedido no existe" ));
            }       
        }catch(\Exception $e){
            $rta= json_encode(array("Fallo al querer eliminar pedido" ));
        }

        $response->getBody()->write($rta);

        return $response;
    }

    public function PrepararPedido($request,$response,$args){
        $req= $request->getParsedBody();
        $pedido= Pedido::where('codigo','=',$req['codigo'])->first();
        $mesa= Mesa::where('id','=',$pedido->idMesa)->first();
        $token=$request->getHeader('token');
        $data=AutentificadorJWT::ObtenerData($token[0]);
        $precio = 0;
        $lista = [];
        if($data->tipo == 'socio' || ($data->tipo == 'mozo' && $data->estado == 'activo'))
        {
            if($pedido->comida >= 0)
            {
                $pedido->tiempoEstimado = $pedido->tiempoEstimado + 20; 
                $cocinero = EmpleadoController::CocineroRandom();
                $cocinero->operaciones = $cocinero->operaciones +1;
                $cocinero->save();
            }
            if($pedido->bebida >= 0)
            {
                $pedido->tiempoEstimado = $pedido->tiempoEstimado + 10; 
                $bartender = EmpleadoController::BartenderRandom();
                $bartender->operaciones = $bartender->operaciones +1;
                $bartender->save();
            }
            if($pedido->cerveza >= 0)
            {
                $pedido->tiempoEstimado = $pedido->tiempoEstimado + 10; 
                $cervesero = EmpleadoController::CerveseroRandom();
                $cervesero->operaciones = $cervesero->operaciones +1;
                $cervesero->save();
            }
            $mesa->save();
            var_dump($mesa->facturacion);
            MesaController::cambiaEstado($pedido->idMesa,'clientes esperando pedido');
        }else
        {
            $rta=json_encode(array("Faltan permisos para realizar esta solicitud" ));
        }
        
        $rta = json_encode('el pedido esta ciendo preparado');
        $response->getBody()->write($rta);
        return $response;
    }

    public function servirPedido($request, $response, $args){
        $req= $request->getParsedBody();
        $token=$request->getHeader('token');
        $data=AutentificadorJWT::ObtenerData($token[0]);
        if($data->tipo == 'mozo' && $data->estado == 'activo'){
            
            $pedido=PedidoController::getPedido($req['codigo']);
            if($pedido->estado == 'en preparacion'){

                PedidoController::cambiarEstado($req['codigo'],'pedido servido');
                $pedido->tiempoResolucion = $req['tiempo'];
                $pedido->save();
                MesaController::cambiaEstado($pedido->idMesa,'con clientes comiendo');

                $rta=json_encode(array("Pedido entregado" ));
            }else{
                $rta=json_encode(array("El Pedido no se encuentra finalizado o ya fue servido, por favor chequear estado" ));
            }
        }else{
            $rta=json_encode(array("Solo un Mozo puede realizar esta actividad" ));
        }

        $response->getBody()->write($rta);
        return $response;
    }

    public function pedirCuenta($request,$response,$args){
        $req= $request->getParsedBody();
        $pedido= Pedido::where('codigo','=',$req['codigo'])->first();
        $mesa= Mesa::where('id','=',$pedido->idMesa)->first();
        $token=$request->getHeader('token');
        $data=AutentificadorJWT::ObtenerData($token[0]);
        $precio = 0;
        $lista = [];
        if($data->tipo == 'socio' || ($data->tipo == 'mozo' && $data->estado == 'activo'))
        {
            $mozo= Empleado::where('email','=',$data->email)->first();
            $mozo->operaciones = $mozo->operaciones + 1;
            $mozo->save();
            if($pedido->comida >= 0)
            {
                $descripcion = ComidaController::getDescripcion($pedido->comida);
                array_push($lista,$descripcion);
                $precio =  ComidaController::getPrecio($pedido->comida);
                $mesa->facturacion = $mesa->facturacion + $precio;
                if($precio > $pedido->max ){

                }
                array_push($lista,$precio);
            }
            if($pedido->bebida >= 0)
            {
                $descripcion = BebidaController::getDescripcion($pedido->bebida);
                array_push($lista,$descripcion);
                $precio =  BebidaController::getPrecio($pedido->bebida);
                $mesa->facturacion = $mesa->facturacion + $precio;
                array_push($lista,$precio);
            }
            if($pedido->cerveza >= 0)
            {
                $descripcion = CervezaController::getDescripcion($pedido->cerveza);
                array_push($lista,$descripcion);
                $precio =  CervezaController::getPrecio($pedido->cerveza);
                $mesa->facturacion = $mesa->facturacion + $precio;
                array_push($lista,$precio);
            }
            if($mesa->facturacion > $mesa->MaxImporte)
            {
                $mesa->MaxImporte = $mesa->facturacion;
                

            }elseif($mesa->facturacion < $mesa->MinImporte)
            {
                $mesa->MinImporte = $mesa->facturacion;
                
            }
            $mesa->save();
            var_dump($mesa->facturacion);
            MesaController::cambiaEstado($pedido->idMesa,'clientes pagando');
        }else
        {
            $rta=json_encode(array("Faltan permisos para realizar esta solicitud" ));
        }
        
        $rta = json_encode($lista);
        $response->getBody()->write($rta);
        return $response;
    }


    public function cobrarPedido($request,$response,$args){

        $req= $request->getParsedBody();
        $pedido= Pedido::where('codigo','=',$req['codigo'])->first();
        $token=$request->getHeader('token');
        $data=AutentificadorJWT::ObtenerData($token[0]);
        $precio = 0;
        if($data->tipo == 'socio')
        {
            if($pedido!=null){
                PedidoController::cambiarEstado($req['codigo'],'cobrado');//cobrado
                MesaController::cambiaEstado($pedido->idMesa,'cerrada');//cerrada
                EncuestaController::add($req['mesa'],$req['resto'],$req['mozo'],$req['cocinero']);
                $rta=json_encode(array("El pedido fue cobrado y la mesa cerrada" ));
            }else{
                $rta=json_encode(array("No se encontro el pedido enviado" ));
            }
        }     

        $response->getBody()->write($rta);
        return $response;
    }
    public function cancelarPedido($request,$response,$args){

        $req= $request->getParsedBody();
        $pedido= Pedido::where('codigo','=',$req['codigo'])->first();
        $token=$request->getHeader('token');
        $data=AutentificadorJWT::ObtenerData($token[0]);
        $precio = 0;
        if($data->tipo == 'socio' || $data->tipo == 'mozo')
        {
            if($pedido!=null){
                PedidoController::cambiarEstado($req['codigo'],'cancelado');//cobrado
                $rta=json_encode(array("El pedido fue cancelado" ));
            }else{
                $rta=json_encode(array("No se encontro el pedido enviado" ));
            }
        }     
        $response->getBody()->write($rta);
        return $response;
    }

    public function LoMasVendido($request,$response,$args){
        $lista = [];
        $selec = Pedido::get();
        $comida1 = 0 ; $comida2 = 0; $comida3 = 0;$bebida1 = 0; $bebida2 = 0; $bebida3 = 0;$cerveza1 = 0;$cerveza2 = 0;$cerveza3 = 0;
        $idCM = 0;$idBM = 0;$idCerM = 0;
        $idCm = 0;$idBm = 0;$idCerm = 0;
        
        for ($i=0; $i <$selec->count(); $i++) { 
            switch ($selec[$i]->comida) {
                case '1':
                    $comida1++ ;
                    break;

                case '2':
                    $comida2++;
                    break;

                case '3':
                    $comida3++;
                    break;
                
            }
            switch ($selec[$i]->bebida) {
                case '1':
                    $bebida1++ ;
                    break;

                case '2':
                    $bebida2++;
                    break;

                case '3':
                    $bebida3++;
                    break;
                
            }
            switch ($selec[$i]->cerveza) {
                case '1':
                    $cerveza1++ ;
                    break;

                case '2':
                    $cerveza2++;
                    break;

                case '3':
                    $cerveza3++;
                    break;
                
            }
                        
        }

            $idCM = PedidoController::Mayor($comida1,$comida2,$comida3);
            $idCm = PedidoController::Menor($comida1,$comida2,$comida3);
            $comidaM = Comida::where('id','=',$idCM)->first();
            array_push($lista,'comida mas vendida');
            array_push($lista,$comidaM->descripcion);
            $comidam = Comida::where('id','=',$idCm)->first();
            array_push($lista,'comida menos vendida');
            array_push($lista,$comidam->descripcion);

            $idBM = PedidoController::Mayor($bebida1,$bebida2,$bebida3);
            $idBm = PedidoController::Menor($bebida1,$bebida2,$bebida3);
            $bebidaM = Bebida::where('id','=',$idBM)->first();
            array_push($lista,'bebida mas vendida');
            array_push($lista,$bebidaM->descripcion);
            $bebidam = Bebida::where('id','=',$idBm)->first();
            array_push($lista,'bebida menos vendida');
            array_push($lista,$bebidam->descripcion);

            $idCerM = PedidoController::Mayor($cerveza1,$cerveza2,$cerveza3);
            $idCerm = PedidoController::Menor($cerveza1,$cerveza2,$cerveza3);
            $cervezaM = Cerveza::where('id','=',$idCerM)->first();
            array_push($lista,'cerveza mas vendida');
            array_push($lista,$cervezaM->descripcion);
            $cervezam = Cerveza::where('id','=',$idCerm)->first();
            array_push($lista,'cerveza menos vendida');
            array_push($lista,$cervezam->descripcion);
        
         $rta = json_encode($lista);
         $response->getBody()->write($rta);
         return $response;
    }

    public function Mayor($num1,$num2,$num3){
        $max = $num1;
        $id = 1;
        if($max < $num2)
            {
                $max = $num2;
                $id = 2;
            }elseif($max < $num3)
            {
                $max = $num3;
                $id=3;
            } 
            return $id;           
    }

    public function Menor($num1,$num2,$num3){
        $min = $num1;
        $id = 1;
        if($min > $num2)
            {
                $min = $num2;
                $id = 2;
            }elseif($min > $num3)
            {
                $min = $num3;
                $id=3;
            } 
            return $id;           
    }

    public function PedidosPendientes(Request $request, Response $response, $args)
    {
        $selec = Pedido::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'en preparacion')
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }
    public function PedidosCancelados(Request $request, Response $response, $args)
    {
        $selec = Pedido::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'cancelados')
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function PedidosPasados(Request $request, Response $response, $args)
    {
        $selec = Pedido::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->tiempoEstimado < $selec[$i]->tiempoResolucion)
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function PedidosFinalizados(Request $request, Response $response, $args)
    {
        $selec = Pedido::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'pedido servido')
            {
                array_push($lista,$selec[$i]->tiempoEstimado);
                array_push($lista,$selec[$i]->tiempoResolucion);
                array_push($lista,$selec[$i]->facturacion);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function cambiarEstado($Codigo,$estado){

        $pedido = Pedido::where('codigo',$Codigo)->first();
        $pedido->estado=$estado;
        //ticketControler::cambiaTiempo($Codigo);

        if($estado == 'listo para servir')
        {
            $pedido->tiempo=0;
        }
        // if($estado==8){//servido
        //     $pedidos=ticket_producto::where('codigo','=',$Codigo)->get();
        //     foreach ($pedidos as $pedido) {
        //         $pedido->estado=$estado;
        //         $pedido->save();
        //     }
        // }
        $pedido->save();
    }

    public function getPedido($codigo){
        return Pedido::where('codigo','=',$codigo)->first();
    }


    
}