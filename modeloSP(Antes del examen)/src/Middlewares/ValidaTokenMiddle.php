<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use App\Utils\AutentificadorJWT;
use App\Utils\Re;
use Exception;

class  ValidaTokenMiddle{

    public function validaVeterinario(Request $request, RequestHandler $handler): Response
    {

        try{
            
            $response = new Response();
            $token =  $request->getHeader('token');

            if(!empty ($token) ){
                $stringToken = $token[0]; 
                //$msg ="";

                AutentificadorJWT::VerificarToken($stringToken);
                $data = AutentificadorJWT::ObtenerData($stringToken);

                if($data->tipo == "veterinario"){
        
                    //$msg ="Veterinario";
                    $response = $handler->handle($request);
        
                }else{
                    $rta ="tipo de usuario incorrecto";
                    $response->getBody()->write( Re::Respuesta(0,$rta));
                }
            }
            else{
                $rta = "Debe ingresar el header token";
                 $response->getBody()->write(Re::Respuesta(0,$rta));
            }
        }catch(Exception $e){
            $response->getBody()->write( Re::Respuesta(-1,"ERRRORR! token invalido"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }


    public function validaCliente(Request $request, RequestHandler $handler): Response
    {

        try{
            
            $response = new Response();
            $token =  $request->getHeader('token');

            if(!empty ($token) ){
                $stringToken = $token[0]; 
                $msg ="";

                AutentificadorJWT::VerificarToken($stringToken);
                $data = AutentificadorJWT::ObtenerData($stringToken);

                if($data->tipo == "cliente"){
        
                    //$msg ="cliente";
                    $response = $handler->handle($request);
        
                }else{
                    $rta ="tipo de usuario incorrecto";
                    $response->getBody()->write(Re::Respuesta(0,$rta));
                }
            }
            else{
                $rta ="Debe ingresar el header token";
                 $response->getBody()->write(Re::Respuesta(0,$rta));
            }
        }catch(Exception $e){
            $response->getBody()->write( Re::Respuesta(-1,"ERRRORR! token invalido"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }


    public function validaSoloToken(Request $request, RequestHandler $handler): Response
    {

        try{
            
            $response = new Response();
            $token =  $request->getHeader('token');

            if(!empty ($token) ){
                $stringToken = $token[0]; 
              

                AutentificadorJWT::VerificarToken($stringToken);
                $data = AutentificadorJWT::ObtenerData($stringToken);

                if($data->tipo == "cliente" || $data->tipo == "veterinario" ){
        
                  
                    $response = $handler->handle($request);
        
                }else{
                    $rta ="tipo de usuario incorrecto";
                    $response->getBody()->write(Re::Respuesta(0,$rta));
                }
            }
            else{
                $rta ="Debe ingresar el header token";
                 $response->getBody()->write(Re::Respuesta(0,$rta));
            }
        }catch(Exception $e){
            $response->getBody()->write( Re::Respuesta(-1,"ERRRORR! token invalido".$token[0]));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function validaAdmin(Request $request, RequestHandler $handler): Response
    {

        try{
            
            $response = new Response();
            $token =  $request->getHeader('token');

            if(!empty ($token) ){
                $stringToken = $token[0]; 
                $msg ="";

                AutentificadorJWT::VerificarToken($stringToken);
                $data = AutentificadorJWT::ObtenerData($stringToken);

                if($data->tipo == "admin"){
        
                    //$msg ="cliente";
                    $response = $handler->handle($request);
        
                }else{
                    $rta ="tipo de usuario incorrecto";
                    $response->getBody()->write(Re::Respuesta(0,$rta));
                }
            }
            else{
                $rta ="Debe ingresar el header token";
                 $response->getBody()->write(Re::Respuesta(0,$rta));
            }
        }catch(Exception $e){
            $response->getBody()->write( Re::Respuesta(-1,"ERRRORR! token invalido"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

    public function ModificarPorTipo(Request $request, RequestHandler $handler): Response
    {

        try{
            
            $response = new Response();
            $token =  $request->getHeader('token');

            if(!empty ($token) ){
                $stringToken = $token[0]; 

                AutentificadorJWT::VerificarToken($stringToken);
                $data = AutentificadorJWT::ObtenerData($stringToken);

                if($data->tipo == "admin"){
        
                    
                    $response = $handler->handle($request);
        
                }
                if($data->tipo == "alumno"){
        
                    $req= $request->getParsedBody();
                    $req2 = $request->getUploadedFiles();

                    if(isset($req['email']) && isset($req2['foto']) ){
        
                        $response = $handler->handle($request);
                   
                    }else {
                        $rta ="Debe setear los parametros mail y foto";
                        $response->getBody()->write( Re::Respuesta(0,$rta));
                    }
        
                }
                if($data->tipo == "profesor"){
        
                    $req= $request->getParsedBody();

                    if(isset($req['email']) && isset($req['materias']) ){
        
                        $response = $handler->handle($request);
                   
                    }else {
                        $rta ="Debe setear los parametros mail y materias";
                        $response->getBody()->write( Re::Respuesta(0,$rta));
                    }
        
                }

            }
            else{
                $rta ="Debe ingresar el header token";
                 $response->getBody()->write(Re::Respuesta(0,$rta));
            }
        }catch(Exception $e){
            $response->getBody()->write( Re::Respuesta(-1,"ERRRORR! token invalido"));
        }
        
        return $response
        ->withHeader('Content-Type','application/json');
    }

}