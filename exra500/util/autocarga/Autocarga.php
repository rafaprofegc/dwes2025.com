<?php
namespace exra500\util\autocarga;

class Autocarga {
  public static function registraAutocarga(): void {
    try {
      spl_autoload_register(self::class . "::autocarga");
    }
    catch(\Exception $e) {
      echo "<header>Error de la aplicación</header>";
      echo $e->getMessage();
    }
  }

  public static function autocarga(string $clase): void {
    // Cambiar \ del espacio de nombres por / del sistema de archivos
    $archivo = str_replace("\\", "/", $clase);
    if( file_exists( $_SERVER['DOCUMENT_ROOT'] . "/$archivo.php")) {
      require_once($_SERVER['DOCUMENT_ROOT'] . "/$archivo.php");
    }
    else {
      throw new \Exception("La clase $clase no se dispone de su definiciión");
    }
  }
}
?>