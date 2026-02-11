<?php
namespace ra7p\restful\enrutador;

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
                                          
DELETE /articulos/{id}   Eliminar artículo     200 Ok (entidad)          Los mismos que antes
                                               204 No Content
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
    $this->rutas[] = new Ruta('DELETE', "#^/articulos/(\w+)$#", \ra7p\restful\modelo\MSArticulos::class, true, ['admin']);   
  }

  protected function DespachaPeticion(): void {
    $metodoPeticion = $this->getMetodoHTTP();
    $pathPeticion = $this->getPath();


  }

  protected function getMetodoHTTP(): string {
    $metodoHTTP = $_SERVER['REQUEST_METHOD'];
    if( $metodoHTTP === "POST" ) {
      if( isset($_POST['_method']) ) {
        $metodoHTTP = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }
    
  }


}