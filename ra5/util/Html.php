<?php
namespace ra5\util;

class Html {
  public static function inicioHtml(string $titulo, array $estilos) { ?>
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

  public static function finHtml() {
    echo "</body>";
    echo "</html>";
  }
}