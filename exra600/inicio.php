<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use exra600\bd\BDPedido;
use exra600\util\Html;

if( !isset($_SESSION['nif']) ) {
  $_SESSION['error'] = "La sesión ha expirado. Vuelva a abrir sesión";
  header("Location: /exra600/index.php");
}

Html::inicioHtml("Pedidos del cliente", ["/exra600/estilos/general.css", "/exra600/estilos/tabla.css"]);
echo "<h1>Datos del cliente</h1>";
echo "<p>{$_SESSION['email']} {$_SESSION['nombre']}</p>";
try {
  $bdPedidos = new BDPedido();
  $pedidos = $bdPedidos->getPedidos($_SESSION['nif']);

  echo <<<TABLA
    <table>
      <thead>
        <tr>
          <th>Nº Pedido</th>
          <th>Nif</th>
          <th>Fecha</th>
          <th>Observaciones</th>
          <th>Total Pedido</th>
        </tr>
      </thead>
      <tbody>
  TABLA;
  foreach( $pedidos as $pedido ) {
    $fecha = $pedido->fecha->format(("d/m/Y"));
    echo <<<FILA
      <tr>
        <td>{$pedido->npedido}</td>
        <td>{$pedido->nif}</td>
        <td>{$fecha}</td>
        <td>{$pedido->observaciones}</td>
        <td>{$pedido->total_pedido}</td>
      </tr>
    FILA;
  }

  echo <<<TABLA
    </tbody>
  </table>
  TABLA;
}
catch(Exception $e) {
  Html::mostrarError($e);
}
echo "<p><a href='/exra600/index.php?cerrar=si'>Cerrar la sesión</a></p>";
Html::finHtml();

?>
