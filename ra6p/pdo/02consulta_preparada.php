<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;

$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";
$clave = "usuario";
$opciones = [
  PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES    => false
];

Html::inicioHtml("Consultas parametrizadas con PDO", 
  ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  // Crear la conexión con la BD
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);

  
}
catch (PDOException $pdoe) {

}
finally {
  $pdo = null;
}

