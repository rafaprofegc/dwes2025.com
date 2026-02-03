<?php
namespace ra5p\rest\vista;

use ra6p\orm\entidad\Articulo;

class VMain extends Vista {
  public function generaSalida(mixed $datos): void {
    ob_start();

    $this->inicioHtml("Tienda OnLine", ["/estilos/general.css"]);
    echo <<<BUSQUEDA
    <form action="/articulos" method="POST" style="display:inline-block;float:right;">
      <input type="hidden" name="idp" id="idp" value="buscar">
      <input type="text" name="descripcion" id="descripcion" size="50">
      <input type="submit" name="operacion" id="operacion" value="Buscar">
    </form>
    BUSQUEDA;

    echo "<h3>Nuestros productos más vendidos</h3>";

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