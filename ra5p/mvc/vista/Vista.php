<?php
namespace ra5p\mvc\vista;

abstract class Vista {

  public abstract function generaSalida(mixed $datos): void;

  protected function inicioHtml(string $titulo, array $estilos): void { ?>
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

  protected function finHtml(): void {
    echo <<<FIN
    <div style='text-aling:center;font-size:0.5em;'>
      <hr>
      &copy;2ºDAW-B
    </div>
    </body>
    </html>
    FIN;
  }
}
?>