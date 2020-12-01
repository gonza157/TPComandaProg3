<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;

use Exception;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Utils\Re;


class  validaParametros{

    public function valParamAlum(Request $request, RequestHandler $handler): Response
    {
        try{

            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['tipo']) && isset($req['email']) && isset($req['clave']) && isset($req['nombre']) && isset($req['apellido']) && isset($req['estado']) ){
                if($req['tipo']=='mozo' || $req['tipo']=='cocinero' || $req['tipo']=='socio' || $req['tipo']=='bartender' || $req['tipo']=='cervesero')
                {
                    $response = $handler->handle($request);
                }else{
                    $rta ="El tipo de usuario es invalido";
                $response->getBody()->write( Re::Respuesta(0,$rta));
                }
            }else {
                $rta ="Debe setear los parametros tipo, mail y clave";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"Erroorr !"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function valParamLogin(Request $request, RequestHandler $handler): Response
    {
        try{

            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['mail']) && isset($req['clave']) ){

                $response = $handler->handle($request);
            }else {
                $rta ="Debe setear los parametros mail y clave";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"Erroorr !"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function valParamAddMascota(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['nombre']) && isset($req['edad']) ){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros nombre y edad en el body";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"error = >".$e->getMessage()));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }


    public function valParamAddTurno(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['id_mascota']) && isset($req['fecha']) && isset($req['hora']) && isset($req['id_veterinario']) ){
                $tiempo = explode(":", $req['hora']);
                if (($tiempo[0] >= 9 || $tiempo[0] <= 17) && ($tiempo[1] == 00 || $tiempo[1] == 30) && !($tiempo[0] == 17 && $tiempo[1] == 30)) {

                    $response = $handler->handle($request);
                    
                }else{
                    $response->getBody()->write(Re::Respuesta(0, "Los turnos son de 9 a 17 y tienen un periodo de 30 minutos cada uno"));
                }
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros id_mascota, fecha, hora y id_veterinario en el body";
                $response->getBody()->write( Re::Respuesta(0,$rta));
            }
            
        }
        catch(Exception $e){
            $response->getBody()->write(  Re::Respuesta(0,"error = >".$e->getMessage()));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    

}