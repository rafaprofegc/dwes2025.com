<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

$jwt = $_COOKIE['jwt'];
verificarJWT($jwt);

if( !isset($_SESSION['login'], $_SESSION['perfil'], $_SESSION['nombre']) ) {
  header("Location: 01autenticacion_form.php");
}

inicioHtml("Autenticación de usuario", ["/estilos/general.css"]);
?>
<header id="cabecera">
  Usuario: <?=$_SESSION['login'] . "-" . $_SESSION['perfil']?> 
  <a href="01autenticacion_form.php">Cerrar sesión</a>
</header>
<?php

echo "<header>Zona autenticada</header>";
echo "<p>Nombre de usuario {$_SESSION['nombre']}</p>";
if( $_SESSION['perfil'] == "Admin") {
  echo "<h3>Menú de opciones para el administrador</h3>";
}
else {
  echo "<h3>Opciones de la aplicación</h3>";
}

finHtml();
?>