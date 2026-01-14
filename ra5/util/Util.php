<?php

namespace ra5\util;

use Exception;

class Util {
  public static function MuestraExcepcion(Exception $e) {
    echo "<h3>Error de la aplicación</h3>";
    echo "<p>Código de error: {$e->getCode()}<br>";
    echo "Mensaje de error: {$e->getMessage()}<br>";
    echo "Script: {$e->getFile()}<br>";
    echo "Línea: {$e->getLine()}</p>";
  }
}