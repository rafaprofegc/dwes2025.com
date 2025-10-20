<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Proceso del formulario", ["/estilos/general.css"]);

echo "<h1>Proceso de la solicitud de empleo</h1>";

if( $_SERVER['REQUEST_METHOD'] == "GET") {
  foreach($_GET as $clave => $valor) {
    echo "<p>$clave = $valor</p>";
  }
  echo "Error. La petición no es correcta";
  exit(1);
}

if( $_SERVER['REQUEST_METHOD'] == "POST") {
  foreach($_POST as $clave => $valor ) {
    echo "<p>$clave = $valor</p>";
  }
}

finHtml();
?>