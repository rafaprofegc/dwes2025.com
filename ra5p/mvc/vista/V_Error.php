<?php
namespace ra5p\mvc\vista;

use ra5p\mvc\error\ErrorAplicacion;

class V_Error extends Vista {
  public function generaSalida(mixed $error): void {

    // Vaciar el buffer
    ob_clean();

    $this->inicioHtml("Error de la aplicación", ["/estilos/general.css", "/estilos/tabla.css"]);

    echo <<<TABLA
    <h2>Error de la aplicación</h2>
    <table>
      <tbody>
    TABLA;
    $archivo = explode("/", $error->getFile());
    $archivo = end($archivo);
    $pr = $error instanceof ErrorAplicacion ? 
      $error->getPuntoRecuperacion() : 
      ['url' => "", 'texto' => ""];

    echo <<<ERROR
    <tr><td>Código error</td><td>{$error->getCode()}</td></tr>
    <tr><td>Mensaje de error</td><td>{$error->getMessage()}</td></tr>
    <tr><td>Archivo</td><td>$archivo</td></tr>
    <tr><td>Línea</td><td>{$error->getLine()}</td></tr>
    <tr><td>Punto de recuperación</td><td><a href="{$pr['url']}">{$pr['texto']}</a></td></tr>
    </tbody>
    </table>
    ERROR;

    $this->finHtml();
    ob_end_flush();

  }
}