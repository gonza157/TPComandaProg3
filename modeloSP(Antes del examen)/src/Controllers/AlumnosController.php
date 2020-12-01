<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Alumno;
use App\Models\Cuatrimestre;

class AlumnosController {

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



    public function holaMundo(Request $request, Response $response, $args){
        //ORM
        //fichero::where('legajo','=',$legajo)->get(
        $miAlum = Alumno::where('legajo','=',1234)->first();

        $miAlum->alumno = "gonzalo";

        $miAlum->save();

        $response->getBody()->write(json_encode($miAlum));
        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        $req= $request->gegetParsedBody();
        $alumno = new Alumno();
        $alumno->alumno=$req['alumno'];
        $alumno->legajo=$req['legajo'];
        $alumno->localidad=$req['localidad'];
        $alumno->cuatrimestre=$req['cuatrimestre'];

        // $alumno = new Alumno;
        // $alumno->alumno = "Eloquent";
        // $alumno->legajo = 4201;
        // $alumno->localidad = 2;
        // $alumno->cuatrimestre = 1;
        
        $rta = json_encode(array("ok" => $alumno->save()));

        $response->getBody()->write($rta);

        return $response;
    }

    // public function validaParametrosNuevoAlumno($request,$response,$next){
    //     $req= $request->getParsedBody();
    //     if(isset($req['alumno']) && isset($req['legajo']) && isset($req['localidad']) && isset($req['cuatrimestre'])){
    //         $newResp = $next($request,$response);
    //     }else
    //     {
    //         $newResp = $response->withJson("Debe setear los parametros nombre, cuatrimestre y cupos",200);
    //     }
    //     return $newResp;
    // }


    public function estoNoVaAca(Request $request, Response $response, $args)
    {
        
        $rta = json_encode(Cuatrimestre::all());
        echo( json_encode("hdsa"));
      
        $response->getBody()->write($rta);

        return $response;
    }


}