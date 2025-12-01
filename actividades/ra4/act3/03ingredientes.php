<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

/*
if( $_SERVER['HTTP_REFERER'] != "http://dwes.com/actividades/ra4/act3/02autenticar.php") {
  $_SESSION['errores'][] = "Tiene que autenticarse y elegir tipo de pizza";
  header("Location: 01inicio.php");
}
*/

session_start();

if( !isset($_COOKIE['jwt']) ) {
  $_SESSION['errores'][] = "La sesión ha expirado";
  header("Location: 01inicio.php");
}

$jwt = $_COOKIE['jwt'];
$usuario = verificarJWT($jwt);
if( !$usuario) {
  $_SESSION['errores'][] = "No ha podido confirmarse la identidad del cliente";
  header("Location: 01inicio.php");
}

$vegetarianos = [
  'pe' => ['nombre' => "Pepino", 'precio' => 1],
  'ca' => ['nombre' => "Calabacín", 'precio' => 1.5],
  'pv' => ['nombre' => "Pimiento verde", 'precio' => 1.25],
  'pr' => ['nombre' => "Pimiento Rojo", 'precio' => 1.75],
  'to' => ['nombre' => "Tomate", 'precio' => 1.5],
  'ac' => ['nombre' => "Aceitunas", 'precio' => 3],
  'ce' => ['nombre' => "Cebolla", 'precio' => 1]
];

$noVegetarianos = [
  'at' => ['nombre' => "Atún", 'precio' => 2],
  'cp' => ['nombre' => "Carne picada", 'precio' => 2.5],
  'pe' => ['nombre' => "Peperoni", 'precio' => 1.75],
  'mo' => ['nombre' => "Morcilla", 'precio' => 2.25],
  'an' => ['nombre' => "Anchoas", 'precio' => 1.5],
  'sa' => ['nombre' => "Salmón", 'precio' => 3],
  'ga' => ['nombre' => "Gambas", 'precio' => 4],
  'la' => ['nombre' => "Langostinos", 'precio' => 4]
];

$tipo =filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
$ingredientes = $tipo === "V" ? $vegetarianos : $noVegetarianos;

inicioHtml("Añadir ingredientes", ["/estilos/general.css", "/estilos/formulario.css"]);
?>
<h4>Datos del pedido</h4>
<p>Cliente: <?= $usuario['email']?><br>
Nombre: <?= $usuario['nombre'] ?><br>
Dirección: <?= $usuario['dirección'] ?><br>
Teléfono: <?= $usuario['telefono'] ?></p>
<hr>


<?php
finHtml();
?>