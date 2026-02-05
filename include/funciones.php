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

function sanearValidar(int $INPUT, array $filtros, array $obligatorios): array {
  $datos = filter_input_array($INPUT, $filtros);
  $datos = array_filter($datos);

  $noEstan = array_diff($obligatorios, array_keys($datos));
  if( $noEstan ) {
    return ['resultado' => false,
            'datos'     => $noEstan
    ];
  }
  else {
    return ['resultado' => true,
            'datos'     => $datos
    ];
  }
}

function mostrarError(Exception $e): void {
  echo <<<ERROR
  <h3>Error de la aplicación</h3>
  <table>
    <tbody>
      <tr><th>Código de error</th><td>{$e->getCode()}</td></tr>
      <tr><th>Mensaje de error</th><td>{$e->getMessage()}</td></tr>
      <tr><th>Archivo</th><td>{$e->getFile()}</td></tr>
      <tr><th>Línea</th><td>{$e->getLine()}</td></tr>
  ERROR;
}
?>