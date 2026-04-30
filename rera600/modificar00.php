<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']. "/vendor/autoload.php");

use rera600\util\Html;

Html::inicioHtml("Examen RA6", ["/rera600/estilos/general.css"]);
try {

  if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);

    if ( preg_match()
  }
  


}
catch(Exception $e) {
  Html::mostrarError($e);
}

Html::finHtml();
?>
