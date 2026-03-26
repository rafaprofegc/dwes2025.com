<?php
session_start();

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
  
}
catch(Exception $e) {

}
echo "<p><a href='/exra600/index.php?cerrar=si'>Cerrar la sesión</a></p>";
Html::finHtml();

?>
