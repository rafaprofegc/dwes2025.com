<?php
namespace ra5p\mvc\controlador;

use ra5p\mvc\error\ErrorAplicacion;
use ra5p\mvc\vista\V_Error;

class Controlador {

  protected const string EN_MODELO = "ra5p\\mvc\\modelo\\";
  protected const string EN_VISTA = "ra5p\\mvc\\vista\\";
  protected string $peticion;
  public static string $vistaError = self::EN_VISTA . "V_Error";

  public function despachaPeticion() {
    // 1º Obtener la petición de un parámetro GET o POST
    $idp = $_GET['idp'] ?? $_POST['idp'] ?? "main";
    $this->peticion = filter_var($idp, FILTER_SANITIZE_SPECIAL_CHARS);

    try {
      // 2º Localizar tanto la clase modelo como la clase vista 
      $claseModelo = self::EN_MODELO . "M_" . ucfirst($this->peticion);
      $claseVista = self::EN_VISTA . "V_" . ucfirst($this->peticion);

      if( !class_exists($claseModelo) ) {
        throw new ErrorAplicacion("La clase modelo $claseModelo no existe", 1,
        ['url' => "/", 'texto' => "Inicio de la aplicación"]);
      }

      if( !class_exists($claseVista) ) {
        throw new ErrorAplicacion("La clase vista $claseVista no existe", 2,
        ['url' => "/", 'texto' => "Inicio de la aplicación"]);
      }

      $modelo = new $claseModelo();
      $datos = $modelo->procesaPeticion();
      $vista = new $claseVista();
      $vista->generaSalida($datos);
    }
    catch(\Exception $e) {
      $ve = self::$vistaError;
      $vistaError = new $ve();
      $vistaError->generaSalida($e);
    }
  }
}
?>