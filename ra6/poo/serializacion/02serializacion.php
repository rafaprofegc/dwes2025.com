<?php
require_once("Usuario.php");
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");


if( $_SERVER['REQUEST_METHOD'] == "GET") {
  inicioHtml("Serialización de objetos", ["/estilos/general.css"]);

  // Usuario se ha autentificado
  if( isset($_SESSION['usuario']) ) {
    $usuario = $_SESSION['usuario'];
    $usuario->registraActividad("Recuperada la sesión");
    echo "<h3>Zona de perfil: {$usuario->perfil}</h3>";
    echo "<p>Usuario: {$usuario}";
  }
  else {
    echo "<h3>El usuario no está autenticado</h3>";
  }  
  finHtml();
}