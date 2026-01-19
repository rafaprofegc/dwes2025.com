<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\seguridad\JWT;

Html::inicioHtml("Zona autenticada de usuario", ["/estilos/general.css"]);
if( isset($_COOKIE['jwt']) ) {
  $jwt = $_COOKIE['jwt'];

  $usuario = JWT::verificarJWT($jwt);

  if( $usuario ) {
    echo "<h3>Zona autenticada</h3>";
    echo "<p>Usuario: {$usuario['email']}<br>";
    echo "Nombre: {$usuario['nombre']}</p>";
  }
  else {
    header("Location: 04autenticacion.php");    
  }
}
Html::finHtml();
?>