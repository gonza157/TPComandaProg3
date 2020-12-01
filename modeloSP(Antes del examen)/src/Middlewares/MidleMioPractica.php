<?php
namespace App\Middlewares;

//use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class  MidleMioPractica{

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $response->getBody()->write("Antes");

        if(true){

            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
           // $response->getBody()->write('BEFORE---' . $existingContent);
        }else{
            $response->getBody()->write('No entro');
        }
    
        $response->getBody()->write("Despues");
        return $response;
    }

}