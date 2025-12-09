<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once("Usuario.php");

if( $_SERVER['REQUEST_METHOD'] == "GET") {
  // Usuario se ha autentificado
  $login = "pepe";
  $perfil = Usuario::PERFIL_ADM;
  $nombre = "José Gómez";

  $usuario = new Usuario($login, $nombre, $perfil, "archivo_$login.txt");
  $_SESSION['usuario'] = $usuario;

  inicioHtml("Serialización de objetos", ["/estilos/general.css"]);
  echo "<h3>Usuario autenticado</h3>";
  echo "<p>Usuario: {$usuario}";
  $usuario->registraActividad("El usuario $login ha abierto sesión");
  $usuario->registraActividad("El usuario puede ir a su zona de perfil");
  echo "<p>Puede ir a la <a href='02serializacion.php'>zona del perfil $perfil</a></p>";
  finHtml();
}