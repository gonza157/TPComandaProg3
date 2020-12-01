<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Utils\AutentificadorJWT;
use App\Models\Usuario;
use App\Models\Materia;
use App\Models\Cuatrimestre;
use App\Utils\Re;

Class MateriaController 
{
    public function add(Request $request, Response $response, $args)
    {
        $req= $request->getParsedBody();
        $materia = new materia();
        $materia->nombre=$req['materia'];        
        $materia->cuatrimestre=$req['cuatrimestre'];
        $materia->cupo=$req['cupo'];
        $rta = json_encode(array("ok" => $materia->save())); 
        $response->getBody()->write($rta);

        return $response;
                     
    }
    public function inscripcionMateria(Request $request, Response $response, $args)
    {
        $cuatrimestre = new Cuatrimestre();
        $req= $request->getParsedBody();
        //$id = $req['legajo'];
        
        $materia = Materia::where('id',$args['idMateria'])->first();
        $token =  $request->getHeader('token');
                $stringToken = $token[0]; 
                $data = AutentificadorJWT::ObtenerData($stringToken);
                var_dump($data->id);
        
        if($materia->cupo > 0)
        {
            $cuatrimestre->idalumno = $data->id;
            $cuatrimestre->idmaterias = $args['idMateria'];
            $cuatrimestre->save();
            $materia->cupo = $materia->cupo -1;
            $materia->save();
        }
        $rta = json_encode(array("ok" => "Se inscribio")); 
        
        //$cuatrimestre->save();
        $response->getBody()->write($rta);

        return $response;
                     
    }

    public function MateriariasCargadas(Request $request, Response $response, $args)
    {
        $selec = $materia = Materia::get();
        $rta = json_encode($selec);

        $response->getBody()->write($rta);

        return $response;
    }
}