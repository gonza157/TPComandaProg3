<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Models\Cuatrimestre;
use App\Utils\Re;

Class CuatrimestreController 
{
    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $cuatrimestre = new Cuatrimestre();
        $cuatrimestre->idalumno=$req['alumno'];        
        $cuatrimestre->idprofesor=$req['profesor'];
        $cuatrimestre->idmateria=$req['materia'];
        $rta = json_encode(array("ok" => $cuatrimestre->save())); 
        //$cuatrimestre->save();
        $response->getBody()->write($rta);

        return $response;
                     
    }

    public function inscripcionMateria(Request $request, Response $response, $args)
    {
        
        $cuatrimestre = Cuatrimestre::where('id',$args['idMateria'])->first();
        if($cuatrimestre->cupo > 0)
        {
            $cuatrimestre->cupo = $cuatrimestre->cupo -1;
            $cuatrimestre->save();
        }
        $rta = json_encode(array("ok" => "Se inscribio")); 
        
        //$cuatrimestre->save();
        $response->getBody()->write($rta);

        return $response;
                     
    }

    // public function inscripcionMateria(Request $request, Response $response, $args)
    // {
        
    //     $cuatrimestre = Cuatrimestre::where('id',$args['idMateria'])->first();
    //     if($cuatrimestre->cupo > 0)
    //     {
    //         $cuatrimestre->cupo = $cuatrimestre->cupo -1;
    //         $cuatrimestre->save();
    //     }
    //     $rta = json_encode(array("ok" => "Se inscribio")); 
        
    //     //$cuatrimestre->save();
    //     $response->getBody()->write($rta);

    //     return $response;
                     
    // }
}