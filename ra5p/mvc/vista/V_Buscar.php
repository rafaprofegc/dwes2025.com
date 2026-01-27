<?php
namespace ra5p\mvc\vista;

use ra6p\orm\entidad\Articulo;

class V_Buscar extends Vista {
  public function generaSalida(mixed $datos):void {
    ob_start();

    $this->inicioHtml("Resultado de la búsqueda", ["/estilos/general.css"]);
    echo "<h1>Tienda Online</h1>";
    echo "<h3>Resultado de la búsqueda</h3>";
    array_walk($datos, function(Articulo $articulo) {
      echo <<<ARTICULO
      {$articulo->descripcion} PVP {$articulo->pvp} 
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