<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

if( isset($_COOKIE['jwt']) ) {
  $jwt = $_COOKIE['jwt'];
  $payload = verificarJWT($jwt);
  if( !$payload ) {
    // El payload es false, ha fallado la verificación del JWT
    header("Location: 01autenticacion_form.php");
  }
}
else {
  // El cliente no ha enviado la cookie porque ha caducado
  // Ha terminado la sesión, no hay identidad de usuario
  // El usuario tiene que volver a autenticarse
  header("Location: 01autenticacion_form.php");
}

/*
Datos de sesión que identifican al usuario. Si usamos JWT están en el payload
if( !isset($_SESSION['login'], $_SESSION['perfil'], $_SESSION['nombre']) ) {
  header("Location: 01autenticacion_form.php");
}

// Si estamos usando datos del usuario en $_SESSION ponemos esto debajo de 
// <header>
//  Usuario: <?=$_SESSION['login'] . "-" . $_SESSION['perfil']?> 

// Si estamos usando JWT, cogemos los datos del array $payload
*/


inicioHtml("Autenticación de usuario", ["/estilos/general.css"]);
?>
<header id="cabecera">
  Usuario: <?= $payload['email'] . " - " . $payload['perfil'] ?>
  <a href="01autenticacion_form.php">Cerrar sesión</a>
</header>
<?php

echo "<header>Zona autenticada</header>";
// echo "<p>Nombre de usuario {$_SESSION['nombre']}</p>";
echo "<p>Nombre de usuario: {$payload['nombre']}</p>";
//if( $_SESSION['perfil'] == "Admin") {
if( $payload['perfil'] == "Admin") {
  echo "<h3>Menú de opciones para el administrador</h3>";
}
else {
  echo "<h3>Opciones de la aplicación</h3>";
}

finHtml();
?>