<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");
require_once("00include.php");

/*
if( $_SERVER['HTTP_REFERER'] != "http://dwes.com/actividades/ra4/act3/02autenticar.php") {
  $_SESSION['errores'][] = "Tiene que autenticarse y elegir tipo de pizza";
  header("Location: 01inicio.php");
}
*/

session_start();

$usuario = comprobarJWT();
$operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);
if( $operacion === "Añadir ingredientes") {
  $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
  if( $tipo == "V") $_SESSION['tipo'] = "V";
  else $_SESSION['tipo'] = "NV";
}

$tipo = $_SESSION['tipo'];
$ingredientes = $tipo === "V" ? $vegetarianos : $noVegetarianos;

if( $operacion === "Otro ingrediente") {
  $ingrediente = filter_input(INPUT_POST, 'ingrediente', FILTER_SANITIZE_SPECIAL_CHARS);
  if( array_key_exists($ingrediente, $ingredientes) ) {
    $_SESSION['ingredientes'][$ingrediente] = $ingredientes[$ingrediente];
  }
}

inicioHtml("Añadir ingredientes", ["/estilos/general.css", "/estilos/formulario.css"]);
presentarUsuario($usuario);
?>
<h4>Personalice su pizza</h4>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Indica los ingredientes</legend>
    <label for="ingrediente">Ingredientes</label>
    <select name="ingrediente" id="ingrediente" size="1">
<?php
    array_walk($ingredientes, function($i, $key) {
      echo "<option value='$key'>{$i['nombre']} {$i['precio']} €</option>";
    })
?>
    </select>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Otro ingrediente">
</form>
<p><a href="04extras.php">Siguiente</a></p>

<?php
finHtml();
?>