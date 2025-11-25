<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once("05sesion_include.php");
comprobarSesion();
inicioHtml("Datos de la sesión", ["/estilos/general.css", "/estilos/tabla.css"]);
verVariablesSesion();

define("PRECIO_KG", 10);
$costeTotal = 0;
foreach($_SESSION['productos'] as $producto ) {
  $costeTotal += $producto['cantidad'] * PRECIO_KG;
}
echo "<h3>Coste total de la cesta: $costeTotal €</h3>";

echo <<<CERRAR_SESION
  <p>
    <a href="01inicio_sesion.php?operacion=cerrar">Cerrar la sesión</a>
  </p>
CERRAR_SESION;

finHtml();
?>
