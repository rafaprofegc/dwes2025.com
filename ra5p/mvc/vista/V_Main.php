<?php
namespace ra5p\mvc\vista;

use ra6p\orm\entidad\Articulo;

class V_Main extends Vista {
  public function generaSalida(mixed $datos):void {
    ob_start();
    
    $this->inicioHtml("Tienda Online", ["/estilos/general.css"]);

    echo <<<PARTE_SUPERIOR
    <h1>Tienda Online</h1>
    <form action="/ra5p/index.php" method="POST" style="display:inline-block">
      <input type="hidden" name="idp" id="idp" value="login">

      <label for="email">Email</label>
      <input type="email" name="email" id="email" size="15" required>

      <label for="clave">Clave</label>
      <input type="password" name="clave" id="clave" size="15" required>

      <input type="submit" name="operacion" value="Login">
    </form>

    <form action="/ra5p/index.php" method="POST" style="display:inline-block;float:right;">
      <input type="hidden" name="idp" id="idp" value="buscar">
      <input type="text" name="descripcion" id="descripcion" size="50">
      <input type="submit" name="operacion" id="operacion" value="Buscar">
    </form>

    <h3>Nuestros productos más vendidos</h3>
    PARTE_SUPERIOR;

    array_walk($datos, function(Articulo $articulo) {
      $dto = $articulo->dto_venta * 100;
      echo <<<ARTICULO
      {$articulo->descripcion} PVP {$articulo->pvp} Dto especial {$dto} %
      <form method="POST" action="/ra5p/index.php">
        <input type="hidden" name="idp" id="idp" value="acarrito">
        <input type="submit" name="operacion" id="operacion" value="Añadir al carrito"> 
      </form>
      <hr>
      ARTICULO;
    });
    
    $this->finHtml();
    ob_end_flush();
  }
}
?>