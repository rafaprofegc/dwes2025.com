<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use rera600\util\Html;

Html::inicioHtml("Examen RA6", ["/rera600/estilos/general.css", "/rera600/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cerrar']) ) {
  $nombreSesion = session_name();
  $par = session_get_cookie_params();

  setcookie($nombreSesion, "", time() - 100, $par['path'], $par['domain'],
    $par['secure'], $par['httponly']);

  session_destroy();
  unset($_SESSION);

  session_start();
}

?>
<form method="POST" action="modificar00.php">
  <fieldset>
    <legend>Identificación</legend>
    <label for="dni">Dni</label>
    <input type="text" name="dni" id="dni" size="10" require>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>
<?php
Html::finHtml();
?>
