<?php
session_start();
define("PRECIO_BASE", 5);
define("INC_NO_VEG", 2);
define("INC_EXTRA", 1);

$extrasPizza = ['eq' => "Extra de queso", 'br' => "Bordes rellenos"];

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");
require_once("00include.php");

$usuario = comprobarJWT();

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  $extras = filter_input(INPUT_POST, 'extras', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

  $extrasValidos = ['eq','br'];
  $extras = array_filter($extras, fn($e) => in_array($e, $extrasValidos));

  inicioHtml("Pizza a servir", ["/estilos/general.css", "/estilos/tabla.css"]);
  presentarUsuario($usuario);

  // Presentar los datos de la pizza
  echo "<h4>Datos de la pizza pedida</h4>";
  echo "<p>Tipo: " . ($_SESSION['tipo'] === "V" ? "Vegetariana": "No Vegetariana") . "</p>";
  echo "<p>Ingredientes:";
  echo <<<INGREDIENTES
    <table>
      <thead>
        <tr><th>Ingrediente</th><th>Precio<th></tr>
      </thead>
      <tbody>
  INGREDIENTES;

  $precioBase = PRECIO_BASE;
  $precioNoVe = $_SESSION['tipo'] === "V" ? 0 : INC_NO_VEG;
  $precioIng = 0;
  foreach($_SESSION['ingredientes'] as $clave => $ingrediente ) {
    echo "<tr>";
    echo "<td>{$ingrediente['nombre']}</td>";
    echo "<td>{$ingrediente['precio']}</td>";
    echo "</tr>";
    $precioIng += $ingrediente['precio'];
  }
  echo <<<INGREDIENTES
    </tbody>
  </table>
  INGREDIENTES;

  $precioExtras = count($extras) * INC_EXTRA;
  echo "<p>Extras: ";
  foreach( $extras as $extra) {
    echo $extrasPizza[$extra] . " " . INC_EXTRA . "€<br>";
  }

  echo "Importe total: " . ($precioBase + $precioNoVe + $precioIng + $precioExtras) . "€</p>";

  echo "<p><a href='01inicio.php?op=logout'>Hacer otro pedido</a></a>";
}
finHtml();
?>