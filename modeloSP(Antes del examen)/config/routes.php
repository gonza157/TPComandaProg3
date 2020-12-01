<?php

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\AlumnosController;
use App\Controllers\JWTController;
use App\Controllers\UsuariosController;
use App\Middlewares\BeforeMiddleware;
use App\Middlewares\AlumnoValidateMiddleware;
use App\Middlewares\MidleMioPractica;
use App\Middlewares\validaParametros;
use App\Middlewares\ValidaTokenMiddle;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\UsuariosContoller;
use App\Controllers\EmpleadoController;
use App\Controllers\MesaController;
use App\Controllers\PedidoController;
use App\Controllers\ComidaController;
use App\Controllers\BebidaController;
use App\Controllers\CervezaController;
use App\Controllers\EncuestaController;

return function ($app) {

    $app->addErrorMiddleware(true,true,true);

    
    

        //->add(validaParametros::class.':valParamLogin');
        //->add(ValidaTokenMiddle::class.':validaSoloToken');
        

   
    $app->group('/JWT', function (RouteCollectorProxy $group) {   
        $group->get('/obtenerToken', JWTController::class .':obtenerToken');
        $group->get('/validarToken', JWTController::class .':validarToken');
      
     });


  //Routs Empleados

  $app->post('/empleado', EmpleadoController::class .':add')
  ->add(validaParametros::class.':valParamEmpleado');

  $app->post('/fichaje', EmpleadoController::class .':login')
  ->add(validaParametros::class.':valParamLogin');

  $app->post('/modificar', EmpleadoController::class .':ModificarEmpleado');

  $app->post('/listadoFC', EmpleadoController::class .':FichajeCompleto');

  $app->post('/borrar', EmpleadoController::class .':BorrarUnEmpleado');

  //listados Empleados

  $app->post('/empleados', EmpleadoController::class .':ListaEmpleados');

  $app->post('/empleadosSuspendidos', EmpleadoController::class .':ListaEmpleadosSuspendidos');

  $app->post('/empleadosBorrados', EmpleadoController::class .':ListaEmpleadosBorrados');

  $app->post('/operacionesC', EmpleadoController::class .':OperacionesporCocinero');

  $app->post('/operacionesB', EmpleadoController::class .':OperacionesporBartender');

  $app->post('/operacionesCer', EmpleadoController::class .':OperacionesporCervesero');

  //Routs Mesa

  $app->post('/nuevaM', MesaController::class .':add');

  $app->post('/modificarMesa/{idMesa}', MesaController::class .':ModificarMesa');

  $app->post('/borrarM/{idMesa}', MesaController::class .':BorrarMesa');

  //listados Mesas

  $app->post('/mesaMfacturacion', MesaController::class .':MesaQueMasFacturo');

  $app->post('/mesamfacturacion', MesaController::class .':MesaQueMenosFacturo');

  $app->post('/lamenosU', MesaController::class .':MesaMenosUsada');

  $app->post('/lamasU', MesaController::class .':MesaMasUsada');

  $app->post('/MconMasFacturacion', MesaController::class .':MesaFacturaMasGrande');

  $app->post('/MconMenosFacturacion', MesaController::class .':MesaFacturaMasChica');

  //routs pedidos

  $app->post('/pedido', PedidoController::class .':add')
  ->add(validaParametros::class.':valParamAddPedido');

  $app->post('/modificarPedido/{codigo}', PedidoController::class .':ModificarPedido');

  $app->post('/borrarP', PedidoController::class .':BorrarPedido');

  $app->post('/servir', PedidoController::class .':ServirPedido');
     
  $app->post('/cuenta', PedidoController::class .':pedirCuenta');

  $app->post('/preparar', PedidoController::class .':PrepararPedido');
 
  //listados Pedidos 
  $app->post('/lomas', PedidoController::class .':LoMasVendido');

  $app->post('/pendientes', PedidoController::class .':PedidosPendientes');

  $app->post('/finalizados', PedidoController::class .':PedidosFinalizados');

  $app->post('/pasados', PedidoController::class .':PedidosPasados');

  $app->post('/cancelados', PedidoController::class .':PedidosCancelados');

  //Encuesta

  $app->post('/encuesta', EncuestaController::class .':addRut');

  

  //Productos
  //bar
  $app->post('/bebida', BebidaController::class .':add');
  $app->post('/modificarBebida', BebidaController::class .':ModificarBebida');
  $app->post('/borrarBebida', BebidaController::class .':BorrarBebida');

  //comida
  $app->post('/comida', ComidaController::class .':add');
  $app->post('/modificarComida', ComidaController::class .':ModificarComida');
  $app->post('/borrarComida', ComidaController::class .':BorrarComida');

  //cerveza
  $app->post('/cerveza', CervezaController::class .':add');
  $app->post('/modificarCerveza', CervezaController::class .':ModificarCerveza');
  $app->post('/borrarCerveza', CervezaController::class .':BorrarCerveza');


  $app->post('/usuario/{legajo}', UsuariosContoller::class .':ModificarLegajo')
  ->add(ValidaTokenMiddle::class .':ModificarPorTipo');  


};

