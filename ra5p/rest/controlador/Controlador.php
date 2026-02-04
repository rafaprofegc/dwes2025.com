<?php
namespace ra5p\rest\controlador;

use ra5p\mvc\error\ErrorAplicacion;
use ra5p\seguridad\Auth;

class Controlador {

  protected array $peticiones;
  protected Peticion $peticion;

  protected const NS_MODELOS = "ra5p\\rest\\modelo\\";
  protected const NS_VISTAS = "ra5p\\rest\\vista\\";

  protected string $vistaError = "VError";
  
  public function __construct() {
    $this->peticiones = [
      new Peticion("GET", "#^/$#", "MMain", "VMain", false),
      new Peticion("GET", "#^/articulos$#", "MGetArticulos", "VGetArticulos"),
      new Peticion("GET", "#^/articulos/(\w+)$#", "MGetArticulo", "VGetArticulo"),
      new Peticion("GET", "#^/articulos/new$#", "MNewArticulo", "VNewArticulo"),
      new Peticion("GET", "#^/articulos/(\w+)/edit#", "MEditArticulo", "VEditArticulo"),
      new Peticion("POST", "#^/articulos$#", "MSaveArticulo", "VSaveArticulo"),
      new Peticion("PUT", "#^/articulos/(\w+)$#", "MUpdateArticulo", "VUpdateArticulo"),
      new Peticion("DELETE", "#^/articulos/(\w+)$#", "MDeleteArticulo", "VDeleteArticulo"),

      new Peticion("GET", "#^/login$#", null, "VFormLogin"),
      new Peticion("POST", "#^/login$#", "MLogin", null),

      new Peticion("GET", "#^/resenas/(\w+)/new$#", "MNewReseña", "VNewReseña"),
      new Peticion("POST", "#^/resenas$#", "MSaveReseña", "VSaveReseña", true )
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
    
      // Comprobar si la petición requiere autenticación
      if( $this->peticion->getRequiereAuth() ) {
        if( !Auth::check() ) {
          header("Location: /login");
          exit;
        }
      }

      // Comprobamos si las clases existen
      // Obtenemos las clases modelo y vista de la petición
      $claseModelo = $this->peticion->getClaseModelo() ;
      $claseVista = $this->peticion->getClaseVista();

      if( $claseModelo !== null  && !class_exists(self::NS_MODELOS . $claseModelo) ) {
        throw new ErrorAplicacion("La clase modelo $claseModelo no existe", 3, 
          ['url' => "/", 'texto' => "Ir al inicio de la aplicación"]);
      }

      if( $claseVista !== null && !class_exists(self::NS_VISTAS . $claseVista) ) {
        throw new ErrorAplicacion("La clase vista $claseVista no existe", 4, 
          ['url' => "/", 'texto' => "Ir al inicio de la aplicación"]);
      }

      // Instanciar las clases, ejecutar lógica de negocio y generar salida
      if( $claseModelo ) {
        $modelo = new (self::NS_MODELOS . $claseModelo)();
        $uriPeticion = $this->peticion->getExpRegURI();
        $parametros = $this->getParametros($pathPeticion, $uriPeticion);
        $datos = $modelo->procesaPeticion($parametros);  // /articulos/acin0001
      }
      
      if( $claseVista ) {
        $vista = new (self::NS_VISTAS . $claseVista)();
        $vista->generaSalida($datos ?? null);
      }
    }
    catch( \Exception $e ) {
      $vista = new (self::NS_VISTAS . $this->vistaError)();
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