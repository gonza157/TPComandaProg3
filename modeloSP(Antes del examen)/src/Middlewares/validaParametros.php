<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;

use Exception;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Utils\Re;


class  validaParametros{

    public function valParamEmpleado(Request $request, RequestHandler $handler): Response
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

    public function valParamAddPedido(Request $request, RequestHandler $handler): Response
    {
        try{
            $response = new Response();

            $req= $request->getParsedBody();

            if(isset($req['mesa']) && isset($req['tiempoEstimado']) ){

                $response = $handler->handle($request);
                //$existingContent = (string) $response->getBody();
                //$response->getBody()->write($existingContent);
            }else {
                $rta ="Debe setear los parametros tiempo estimado y mesa en el body";
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