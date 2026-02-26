<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\seguridad\JWT;

session_start();

if( !isset($_COOKIE['jwt']) ) {
  header("Location: 04autenticacion.php");
  exit;
}

$jwt = $_COOKIE['jwt'];
$payload = JWT::verificarJWT($jwt);

if( !$payload ) {
  header("Location: 04autenticacion.php");
  exit;
}

Html::inicioHtml("Zona de usuario autenticado", ["/estilos/general.css"]);
echo <<<CLIENTE
<header>Zona autenticada</header>
<h1>Bienvenido a la TiendaOL</h1>
<p>Cliente: {$payload['nombre']} - {$payload['email']}</p>
CLIENTE;

$fecha = $_SESSION['inicio'];
echo "<p>Inicio de la compra " . $fecha->format("d/m/Y G:i:s") . "</p>";

Html::finHtml();
?>