<?php
// Se invoca session_start() al principio de cada script que formen
// la sesión
session_start();

require_once($_SERVER['DOCUMENT_ROOT']. "/include/funciones.php");
require_once("05sesion_include.php");

$operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);

if( $_SERVER['REQUEST_METHOD'] === "POST" && $operacion == "Enviar datos personales") {
  // Recepcionar los datos personales
  $filtros = [
    'nombre'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'email' => FILTER_VALIDATE_EMAIL
  ];

  $obligatorios = ['nombre', 'email'];
  $resultado = sanearValidar(INPUT_POST, $filtros, $obligatorios);
  if( !$resultado['resultado'] ) {
    Error(1);
  }

  $datos = $resultado['datos'];

  // Los datos personales se añaden a variables de sesión
  $_SESSION['nombre'] = $datos['nombre'];
  $_SESSION['email'] = $datos['email'];
  $_SESSION['productos'] = [];
}

if( $_SERVER['REQUEST_METHOD'] === "POST" && $operacion === "Añadir a la cesta") {
  comprobarSesion();

  $filtros = [
    'dulce'   => FILTER_SANITIZE_SPECIAL_CHARS,
    'cantidad' => FILTER_VALIDATE_INT
  ];

  $obligatorios = ['dulce', 'cantidad'];
  $resultado = sanearValidar(INPUT_POST, $filtros, $obligatorios);
  if( !$resultado['resultado']) {
    Error(2);
  }

  $datos = $resultado['datos'];
  array_push($_SESSION['productos'], $datos);
  //$_SESSION['productos'][] = $datos;
}


// Presentar el formulario de productos de la cesta.
comprobarSesion();
inicioHtml("Sesiones en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);
verVariablesSesion();

?>
<header>Mi cesta de Navidad</header>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
  <fieldset>
    <legend>Añadir un nuevo producto a la cesta</legend>
    <label for="dulce">Dulce de Navidad</label>
    <input type="text" name="dulce" id="dulce" size="40" required>

    <label for="cantidad">Cantidad</label>
    <input type="number" name="cantidad" id="cantidad" size="5" required>

  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Añadir a la cesta"
</form>
<?php

?>