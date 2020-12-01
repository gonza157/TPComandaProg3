<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Models\Cuatrimestre;
use App\Utils\Re;

class UsuariosContoller {

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
        $usuario = new Usuario();
        $usuario->email=$req['email'];
        $usuario->nombre=$req['nombre'];
        if(strlen($req['clave'])>=4)
        {
            $selec = $usuario->where('email',$usuario->email)->first();
            $selec2 = $usuario->where('nombre',$usuario->nombre)->first();
                if(empty($selec) )
                {

                    $usuario->nombre=$req['nombre'];
                    $usuario->email=$req['email'];
                    $usuario->clave=$req['clave'];
                    $usuario->tipo=$req['tipo'];
                    $usuario->clave =$req['clave'];
                    $rta = json_encode(array("ok" => $usuario->save()));
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
        $usuario = new Usuario();
        $usuario->email=$req['email'];
        $usuario->clave =$req['clave'];

        $selec = $usuario->where('email',$usuario->email)->first();
        if(!empty($selec)){
            if($usuario->clave == $req['clave'])
            {
                $Objeto = new \stdClass();

                $Objeto->id = $selec->ID;
                $Objeto->nombre = $selec->nombre;
                $Objeto->email = $selec->email;
                $Objeto->tipo = $selec->tipo;

                $rta = Re::Respuesta(1, "Token: ".AutentificadorJWT::CrearToken($Objeto));          
            
            

            }else{
                $rta = Re::Respuesta(0,"clave incorrecto");
            }

        }
        else{
            $rta = Re::Respuesta(0,"Mail no registrado");
        }


        $response->getBody()->write($rta);

        return $response 
        ->withHeader('Content-Type','application/json');

       
    }

}