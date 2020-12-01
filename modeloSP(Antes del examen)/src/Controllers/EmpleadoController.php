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

    public function getAll(Request $request, Response $response, $args)
    {
        $rta = json_encode(Alumno::all());

        // $response->getBody()->write("Controller");
        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function getId(Request $request, Response $response, $args)
    {
        
        $rta = json_encode("sad");

        // $response->getBody()->write("Controller");
        $response->getBody()->write($rta);

        return $response
        ->withHeader('Content-Type','application/json');
    }


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
            if($selec->clave == $req['clave'] && $selec->estado != 'suspendido')
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
            //$selec = $empleado->where('email',$empleado->email)->first();
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
            //$selec = $empleado->where('email',$empleado->email)->first();
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




    public function FichajeCompleto(Request $request, Response $response, $args)
    {
        $selec = $fichaje = Fichaje::get();
        $rta = json_encode($selec);

        $response->getBody()->write($rta);

        return $response;
    }


     // public function logout(Request $request, Response $response, $args)
    // {
    //     $req= $request->getParsedBody();
    //     $empleado = new Empleado();
    //     $empleado->email=$req['email'];
    //     $fecha = date('Y-m-d');

    //     $selec = $empleado->where('email',$empleado->email)->first();
    //     if(!empty($selec)){
    //         var_dump($selec->fecha);
    //         var_dump($fecha);
    //         $fichaje = new fichaje();
    //             $selec2 = $fichaje->where('email',$empleado->email)->first();
    //             $fichaje = $selec2;
    //         if($selec->fecha == $fecha)
    //        {
    //             $hora = date('H:i:s');
                
    //             $fichaje->horaS = $hora; 
    //             $fichaje->save();
    //             $rta = Re::Respuesta(1,"salida cargada");
    //         }else{
    //         $rta = Re::Respuesta(0,"no coincide la fecha");
    //        }
    //             //aca cargo la info del fichaje             

    //     }
    //     else{
    //         $rta = Re::Respuesta(0,"Mail no registrado");
    //     }        
    //     $response->getBody()->write($rta);

    //     return $response 
    //     ->withHeader('Content-Type','application/json');

       
    // }

}