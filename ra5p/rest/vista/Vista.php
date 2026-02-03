<?php
namespace ra5p\rest\vista;

use ra6p\orm\entidad\Cliente;
use ra5p\seguridad\Auth;

abstract class Vista {
  protected ?Cliente $cliente;

  public abstract function generaSalida(mixed $datos): void;

  public function __construct() {
    $this->cliente = Auth::cliente();
  }
  
  protected function inicioHtml(string $titulo, array $estilos): void { ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width;initial-scale=1">
        <title><?=$titulo?></title>
  <?php
        array_walk($estilos, function($hoja) {
          echo <<<ESTILOS
          <link type="text/css" rel="stylesheet" href="$hoja">
          ESTILOS;
        });
  ?>
      </head>
      <body>
      <div id="cabecera">
        <img src="imagen/logo.png">
        <h1>Tienda ONline</h1>
<?php        
        if( $this->cliente ) {
          echo <<<CLIENTE
          <span>Hola, {$this->cliente->nombre}</span>
          <a href="/logout">Cerrar sesión</a>
          CLIENTE;
        }
        else {
          echo <<<FORM
          <form method="POST" action="/login">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" size="15" require>

            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" size="15" require>

            <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
          </form>
          FORM;
        }
?>        
      </div>
  <?php
  }

  protected function finHtml(): void {
    echo <<<FIN
    </body>
    </html>
    FIN;
  }
}