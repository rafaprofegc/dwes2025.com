<?php
namespace ra5p\rest\vista;

use ra5p\rest\error\ErrorAplicacion;

class VError extends Vista {
  public function generaSalida(mixed $error): void {
    ob_clean();
    $this->inicioHtml("Error de la aplicación", ["/estilos/general.css"]);

    $archivo = explode( "/", $error->getFile());
    $archivo = end($archivo);
    
    echo <<<ERROR
    <h3>Error de la aplicación</h3>
    <table>
      <tbody>
        <tr>
          <td>Código de error</td><td>{$error->getCode()}</td>
          <td>Mensaje de error</td><td>{$error->getMessage()}</td>
          <td>Archivo</td><td>$archivo</td>
          <td>Línea</td><td>{$error->getLine()}</td>
    ERROR;
    
    if( $error instanceof ErrorAplicacion ) {
      $pr = $error->getPuntoRecuperacion();
      echo <<<ERROR
          <td>Punto de recuperación</td>
          <td><a href="{$pr['url']}">{$pr['texto']}</a></td>
      ERROR;
    }
    $this->finHtml();
    ob_end_flush();
  }
}