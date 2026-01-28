<?php
namespace ra5p\rest\controlador;

use ra5p\mvc\error\ErrorAplicacion;

class Controlador {

  protected array $peticiones;
  protected Peticion $peticion;

  protected const NS_MODELOS = "rest\\modelo\\";
  protected const NS_VISTAS = "rest\\vista\\";

  protected string $vistaError = "VError";
  
  public function __construct() {
    $this->peticiones = [
      new Peticion("GET", "#^/articulos$#", "MGetArticulos", "VGetArticulos"),
      new Peticion("GET", "#^/articulos/(\w+)$#", "MGetArticulo", "VGetArticulo"),
      new Peticion("GET", "#^/articulos/new$#", "MNewArticulo", "VNewArticulo"),
      new Peticion("GET", "#^/articulos/(\w+)/edit#", "MEditArticulo", "VEditArticulo"),
      new Peticion("POST", "#^/articulos$#", "MSaveArticulo", "VSaveArticulo"),
      new Peticion("PUT", "#^/articulos/(\w+)$#", "MUpdateArticulo", "VUpdateArticulo"),
      new Peticion("DELETE", "#^/articulos/(\w+)$#", "MDeleteArticulo", "VDeleteArticulo")
    ];

    $this->despachaPeticion();
  }

  public function despachaPeticion() {
    try {
      // Obtener la petición de la URL (ruta y método HTTP)
      $pathPeticion = $this->getPath();
      $metodoPeticion = $this->getMetodoHTTP();

      // Buscamos la petición
      $this->peticion = $this->getPeticion($metodoPeticion, $pathPeticion);

      // Obtenemos las clases modelo y vista de la petición
      $claseModelo = self::NS_MODELOS . $this->peticion->getClaseModelo();
      $claseVista = self::NS_VISTAS . $this->peticion->getClaseVista();

      // Comprobamos si las clases existen
      if( !class_exists($claseModelo) ) {
        throw new ErrorAplicacion("La clase modelo $claseModelo no existe", 3, 
          ['url' => "/", 'texto' => "Ir al inicio de la aplicación"]);
      }

      if( !class_exists($claseVista) ) {
        throw new ErrorAplicacion("La clase vista $claseVista no existe", 4, 
          ['url' => "/", 'texto' => "Ir al inicio de la aplicación"]);
      }

      // Instanciar las clases, ejecutar lógica de negocio y generar salida
      $modelo = new $claseModelo();
      $uriPeticion = $this->peticion->getExpRegURI();
      $parametros = $this->getParametros($pathPeticion, $uriPeticion);
      $datos = $modelo->procesaPeticion($parametros);  // /articulos/acin0001

    }
    catch( \Exception $e ) {
      $vista = new ($this->vistaError)();
      $vista->generaSalida($e);
    }
  }

  private function getPath() {
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    return $path;
  }

  private function getMetodoHTTP() {
    $metodosPermitidos = ["POST", "GET", "DELETE", "PUT", "PATCH"];
    $metodoHTTP = $_SERVER['REQUEST_METHOD'] ?? "GET";
    if( $metodoHTTP === "POST" && isset($_POST['_method']) ) {
      $metodoHTTP = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    if( in_array($metodoHTTP, $metodosPermitidos) ) {
      return $metodoHTTP;
    }
    throw new ErrorAplicacion("El método {$metodoHTTP} no está contemplado", 1,
      ['url' => "/", "Ir al inicio de la aplicación"]);
  }

  private function getPeticion(string $metodoHTTP, string $pathPeticion) {
    foreach( $this->peticiones as $peticion ) {
      if( $peticion->esIgual($metodoHTTP, $pathPeticion) ) {
        return $peticion;
      }
    }
    throw new ErrorAplicacion("La petición {$metodoHTTP} {$pathPeticion} no está contemplada", 2,
      ['url' => "/", 'texto' => "Ir al inicio de la aplicación"]);
  }

  private function getParametros(string $pathPeticion, string $expRegURI): array {
    $parametros = [];
    preg_match($expRegURI, $pathPeticion, $parametros);
    array_shift($parametros);
    return $parametros;
  }
}

?>