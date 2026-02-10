<?php
namespace exra500\vista;

use exra500\util\Html;

class VistaError00 {
  public static function muestraError(\Exception $e): void {
    ob_start();
    Html::inicio("Pedido", ["/exra500/estilos/general.css", "/exra500/estilos/tablas.css"]);
    $rutaCompleta = explode("/", $e->getFile());
    $archivo = end($rutaCompleta);
    echo <<<ERROR
      <header>Error de la aplicación</header>
      <table>
        <tbody> 
          <tr><td>Código de error</td><td>{$e->getCode()}</td></tr>
          <tr><td>Mensaje de error</td><td>{$e->getMessage()}</td></tr>
          <tr><td>Archivo</td><td>{$archivo}</td></tr>
          <tr><td>Línea</td><td>{$e->getLine()}</td></tr>
        </tbody>
      </table>
      <p><a href="/exra500/inicio.php">Volver a intentarlo</a></p>
    ERROR;
    Html::fin();
    ob_end_flush();
  }
}
?>