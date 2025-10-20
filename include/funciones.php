<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones2.php");

function inicioHtml(string $titulo = "Sin título", array $estilos = []) { ?>
  <!DOCTYPE html>
  <html lang="es">
    <head>
      <title><?=$titulo?></title>
      <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
      <meta charset="utf-8"/>
<?php
      foreach($estilos as $hoja) {
        echo "<link type='text/css' rel='stylesheet' href='$hoja'>";
      }
?>
    </head>
    <body>
<?php
}


function finHtml() {
  echo <<<FIN
  </body>
  </html>
  FIN;
}
?>