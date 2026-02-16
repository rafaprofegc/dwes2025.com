<?php
namespace ra7p\restful\enrutador;

use ra7p\restful\error\ErrorServicio;
use ra5p\seguridad\Auth;

/*
Petición                Operación             Código estado (protocolo HTTP)
                                              Petición OK               Error
GET /articulos          Listado de artículos  200 Ok                    404 Not Found
                                                                        500 Internal Server Error

GET /articulos/{id}     Un artículo           200 Ok                    404 Not Found
                                                                        500 Internal Server Error

POST /articulos         Crear un artículo     201 Created (redirección) 403 Forbidden
                                                                        401 Unauthorized
                                                                        409 Conflict (violación regla de integridad)                                                                    
                                                                        400 Bad Request (datos no válidos)
PUT /articulos/{id}
PATCH /articulos/{id}    Update un artículo    200 Ok                   Los mismos que antes
                                               204 No Content             
                                          
DELETE /articulos/{id}   Eliminar artículo     200 Ok (entidad)         Los mismos que antes
                                               204 No Content

Clase no existe

Método HTTP no existe                                                   405
*/

class Enrutador {

  protected array $rutas;

  public function __construct() {
    $this->rutas = [];
    $this->IniciarRutas();
    $this->DespachaPeticion();
  }

  private function IniciarRutas():void {
    // Rellenar el array rutas
    $this->rutas[] = new Ruta('GET', '#^/articulos$#', \ra7p\restful\modelo\MSArticulos::class, 'getAll', false, ['user', 'admin']);
    $this->rutas[] = new Ruta('GET', "#^/articulos/(\w+)$#", \ra7p\restful\modelo\MSArticulos::class, 'get');
    $this->rutas[] = new Ruta('POST', "#^/articulos$#", \ra7p\restful\modelo\MSArticulos::class, 'insert', true, ['admin']);
    $this->rutas[] = new Ruta('PUT', "#^/articulos/(\w+)$#", \ra7p\restful\modelo\MSArticulos::class, 'update', true, ['admin']);
    $this->rutas[] = new Ruta('DELETE', "#^/articulos/(\w+)$#", \ra7p\restful\modelo\MSArticulos::class, 'delete', true, ['admin']);   
  }

  protected function DespachaPeticion(): void {
    $metodoPeticion = $this->getMetodoHTTP();
    $pathPeticion = $this->getPath();

    try {
      $ruta = $this->buscarRuta($metodoPeticion, $pathPeticion);

      if( $ruta === null ) throw new ErrorServicio(404, ErrorServicio::ERROR_404, "Not found");

      // Comprobar si se requiere usuario autenticado
      if( $ruta->getRequiereAuth() ) {
        if( !Auth::check() ) {
          throw new ErrorServicio(401, ErrorServicio::ERROR_401, "Unauthorized");
        }
      }

      /* Comprobar los permisos del usuario autenticado
      if( $ruta->getRequiereAuth() ) {
        $cliente = Auth::cliente();
        if( !in_array($cliente->perfil, $ruta->getPerfiles() ) ) {
          throw new ErrorServicio(403, ErrorServicio::ERROR_403, "Forbidden");
        }
      }
      */

      $datos = $this->ejecutarRuta($ruta, $pathPeticion);

      // Tenemos los datos, se envía la respuesta
      switch( $ruta->getMetodoHTTP() ) {
        case 'GET': {
          RespuestaFactory::ok($datos);
          break;
        }
        case 'POST': {
          RespuestaFactory::created($datos);
          break;
        }
        case 'PUT':
        case 'PATCH':
        case 'DELETE': {

          break;
        }
      }
    }
    catch(ErrorServicio $es) {
      RespuestaFactory::error($es);
    }
    catch(\Exception $e) {
      RespuestaFactory::error( new ErrorServicio(500, ErrorServicio::ERROR_500, "Internal Server Error"));
    }
    


    
  }

  protected function getMetodoHTTP(): string {
    $metodoHTTP = $_SERVER['REQUEST_METHOD'];
    if( $metodoHTTP === "POST" ) {
      if( isset($_POST['_method']) ) {
        $metodoHTTP = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }

    if( in_array($metodoHTTP, ["POST", "GET", "PUT", "PATCH","DELETE"] ) ) {
      return $metodoHTTP;
    }

    throw new ErrorServicio(405, ErrorServicio::ERROR_405, "Method Not Allowed");   
  }

  private function getPath(): string {
    $uriPeticion = $_SERVER['REQUEST_URI'];
    return parse_url($uriPeticion, PHP_URL_PATH);
  }

  private function buscarRuta(string $metodoHTTP, string $uriPeticion): ?Ruta {
    foreach( $this->rutas as $ruta) {
      if( $ruta->esIgual($metodoHTTP, $uriPeticion) ) {
        return $ruta;
      }
    }
    return null;
  }

  private function ejecutarRuta(Ruta $ruta, string $pathPeticion): mixed {
    $clase = $ruta->getClase();
    $metodo = $ruta->getMetodo();

    if( !class_exists($clase) || !method_exists($clase, $metodo) ) {
      throw new ErrorServicio(500, ErrorServicio::ERROR_500, "Internal Server Error");
    }

    $parametros = $this->extraerParametros($ruta->getExpRegURI(), $pathPeticion);
    $modelo = new $clase();
    $datos = call_user_func_array([$modelo, $metodo], $parametros);
    return $datos;
  }

  private function extraerParametros(string $expRegURI, string $pathPeticion): array {
    $parametros = [];
    preg_match($expRegURI, $pathPeticion, $parametros);
    /*
    Petición GET /articulos/acin0010
    $parametros[0] = /articulos/acin0010
    $parametros[1] = acin0010
    Al hacer array_shift se borra el primer elemento
    $parametros[0] = acin0010
    */
    array_shift($parametros);
    return $parametros;   
  }
}