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

    if( $this->cliente ) {
      echo "<h3>Tus últimas compras</h3>";
      array_walk($datos['conAuth'], function(array $aV) {
        $dto = floatval($aV['dto']) * 100;
        $importe = intval($aV['unidades']) * ( floatval($aV['precio']) - floatval($aV['precio']) * floatval($aV['dto']) ); 
        $fechaCompra = new \Datetime($aV['fecha']);

        echo <<<ARTICULO_VENDIDO
        <div>
        Pedido: {$aV['npedido']} - Fecha: {$fechaCompra->format(Articulo::FORMATO_FECHA)}<br>
        Artículo: {$aV['referencia']} {$aV['descripcion']}
         - Unidades: {$aV['unidades']} Precio: {$aV['precio']}€ - 
         {$dto}% => {$importe}€
        <a href="/resenas/{$aV['referencia']}/new">Añadir una reseña</a>
        </div>
        <hr>
        ARTICULO_VENDIDO;
      });
    }

    echo "<h3>Nuestros productos más vendidos</h3>";

    array_walk($datos['sinAuth'], function(Articulo $articulo) {
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