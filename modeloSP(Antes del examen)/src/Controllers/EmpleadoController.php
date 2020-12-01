<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Empleado;
use App\Models\Fichaje;
use App\Models\Cuatrimestre;
use App\Utils\Re;

class EmpleadoController {

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $empleado = new Empleado();
        $empleado->email=$req['email'];
        $empleado->nombre=$req['nombre'];
        if(strlen($req['clave'])>=4)
        {
            $selec = $empleado->where('email',$empleado->email)->first();
                if(empty($selec) )
                {

                    $empleado->nombre=$req['nombre'];
                    $empleado->apellido=$req['apellido'];
                    $empleado->email=$req['email'];
                    $empleado->clave=$req['clave'];
                    $empleado->tipo=$req['tipo'];
                    $empleado->estado =$req['estado'];
                    $rta = json_encode(array("ok" => $empleado->save()));
                }else{
                    $rta = json_encode(array("Error este mail existe" ));
                }
        }else
        {
            $rta = json_encode(array("La contraseña es muy corta" ));
        }       
        

        $response->getBody()->write($rta);

        return $response;
    }
    

    public function login(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $empleado = new Empleado();
        $empleado->email=$req['email'];
        $empleado->clave =$req['clave'];

        $selec = $empleado->where('email',$empleado->email)->first();
        if(!empty($selec)){
            if($selec->clave == $req['clave'] && $selec->estado == 'activo')
            {
                $Objeto = new \stdClass();

                $Objeto->id = $selec->ID;
                $Objeto->nombre = $selec->nombre;
                $Objeto->apellido = $selec->apellido;
                $Objeto->email = $selec->email;
                $Objeto->tipo = $selec->tipo;
                $Objeto->estado = $selec->estado;

                $rta = Re::Respuesta(1, "Token: ".AutentificadorJWT::CrearToken($Objeto));
                //aca cargo la info del fichaje             
                $fecha = date('Y-m-d');
                $hora = date('H:i:s');
                $fichaje = new fichaje();
                $fichaje->nombre = $selec->nombre;
                $fichaje->email = $selec->email;
                $fichaje->fecha = $fecha;
                $fichaje->hora = $hora; 
                $fichaje->save();            

            }else{
                $rta = Re::Respuesta(0,"clave incorrecto o se encuentra suspendido");
            }

        }
        else{
            $rta = Re::Respuesta(0,"Mail no registrado");
        }        
        $response->getBody()->write($rta);

        return $response 
        ->withHeader('Content-Type','application/json');

       
    }
   

    public function ModificarEmpleado(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $empleado = new Empleado();
        $empleado->email=$req['email'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $empleado->where('email',$empleado->email)->first();
        if(!empty($selec))
        {
                if($data->tipo == 'socio' ||  $data->email == $req['email'])
                {
                    
                    if(isset($req['nombre']))
                    {
                        $selec->nombre=$req['nombre'];
                    }
                    if(isset($req['apellido']))
                    {
                        $selec->apellido=$req['apellido'];
                    }
                    if(isset($req['email']))
                    {
                        $selec->email=$req['email'];
                    }
                    if(isset($req['clave']))
                    {
                        $selec->clave=$req['clave'];
                    }
                    if(isset($req['tipo']))
                    {
                        $selec->tipo=$req['tipo'];
                    }
                    if(isset($req['estado']))
                    {
                        $selec->estado =$req['estado'];
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

    public function BorrarUnEmpleado(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $empleado = new Empleado();
        $empleado->email=$req['email'];
        $token =  $request->getHeader('token');
        $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
        $selec = $empleado->where('email',$empleado->email);
        if(!empty($selec))
        {
                if($data->tipo == 'socio' )
                {
                    $selec->estado = 'borrado';                  
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

    public function CocineroRandom()
    {
        $selec = Empleado::get();
        $empleado = new Empleado();
        $lista = [];
        if($selec != null)
        {           
            
            for ($i=0; $i <$selec->count(); $i++) { 
                if($selec[$i]->tipo == 'cocinero')
                {
                    $empleado = $selec[$i];
                    array_push($lista,$empleado);
                }
            }
            $r = mt_rand(1,count($lista));
            //$empleado = $lista[$r];
        }       

        return $empleado;
    }

    public function BartenderRandom()
    {
        $selec = Empleado::get();
        $empleado = new Empleado();
        $lista = [];
        if($selec != null)
        {
            for ($i=0; $i <$selec->count(); $i++) { 
                if($selec[$i]->tipo == 'bartender')
                {
                    $empleado = $selec[$i];
                    array_push($lista,$empleado);
                }
            }
            $r = mt_rand(1,count($lista));
            //$empleado = $lista[$r];
        }       
        return $empleado;
    }

    public function CerveseroRandom()
    {
        $selec = Empleado::get();
        $empleado = new Empleado();
        $lista = [];
        if($selec != null)
        {
            for ($i=0; $i <$selec->count(); $i++) { 
                if($selec[$i]->tipo == 'cervesero')
                {
                    $empleado = $selec[$i];
                    array_push($lista,$empleado);
                }
            }
            $r = mt_rand(1,count($lista)); 
            //$empleado = $lista[$r];
        }       
        
        return $empleado;
    }
    
    public function ListaEmpleados(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'activo')
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function ListaEmpleadosSuspendidos(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'suspendido')
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function ListaEmpleadosBorrados(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'borrados')
            {
                array_push($lista,$selec[$i]);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function OperacionesporCocinero(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'activo' && $selec[$i]->tipo == 'cocinero')
            {
                array_push($lista,$selec[$i]->email);
                array_push($lista,'operaciones:');
                array_push($lista,$selec[$i]->operaciones);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function OperacionesporBartender(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'activo' && $selec[$i]->tipo == 'bartender')
            {
                array_push($lista,$selec[$i]->email);
                array_push($lista,'operaciones:');
                array_push($lista,$selec[$i]->operaciones);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }

    public function OperacionesporCervesero(Request $request, Response $response, $args)
    {
        $selec = Empleado::get();
        $lista = [];
        for ($i=0; $i <$selec->count(); $i++) { 
            if($selec[$i]->estado == 'activo' && $selec[$i]->tipo == 'cervesero')
            {
                array_push($lista,$selec[$i]->email);
                array_push($lista,'operaciones:');
                array_push($lista,$selec[$i]->operaciones);
            }
        }
        $rta = json_encode($lista);

        $response->getBody()->write($rta);

        return $response;
    }


    public function FichajeCompleto(Request $request, Response $response, $args)
    {
        $selec = $fichaje = Fichaje::get();
        $rta = json_encode($selec);

        $response->getBody()->write($rta);

        return $response;
    }


}