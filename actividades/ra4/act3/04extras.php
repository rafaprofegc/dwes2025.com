<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");
require_once("00include.php");

$usuario = comprobarJWT();

/* En caso de introducir los ingredientes de una sola vez 
if( $_SERVER['REQUEST_METHOD'] === "POST") {
  $ingredientes = filter_input(INPUT_POST, 'ingredientes', 
    FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
  $ingValidos = $_SESSION['tipo'] === "V" ? $vegetarianos : $noVegetarianos;

  $ingredientes = array_filter($ingredientes, 
    fn($i) => array_key_exists($i, $ingValidos));

  foreach($ingredientes as $ingrediente) {
    $_SESSION['ingredientes][$ingrediente] = $ingValidos[$ingrediente];
  }
}
*/

// Usuario identificado
inicioHtml("Extras", ["/estilos/general.css", "/estilos/formulario.css"]);
presentarUsuario($usuario);
?>
<h4>Extras para la pizza<h4>
<form method="POST" action="05final.php">
  <fieldset>
    <legend>Extras disponibles</legend>
    <label for="extras[]">Extra de queso</label>
    <input type="checkbox" name="extras[]" id="extra1" value="eq">

    <label for="extras[]">Borde relleno</label>
    <input type="checkbox" name="extras[]" id="extra2" value="br">

  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Siguiente">
</form>
<?php
finHtml();
?>