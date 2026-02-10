<?php
namespace exra500\vista;

use exra500\util\Html;
use exra500\entidad\Pedido00;

class VistaPedido00 {
  public function enviarSalida(?Pedido00 $pedido): void {
    ob_start();
    Html::inicio("Pedido", ["/exra500/estilos/general.css", "/exra500/estilos/tablas.css"]);
    if( !$pedido ) {
      echo "<h3>El pedido no existe</h3>";
    }
    else {
      echo <<<PEDIDO
      <header>Datos de un pedido nº {$pedido->npedido}</header>
      <table>
        <tbody>
          <tr><th>Nº pedido</th><td>{$pedido->npedido}</td></tr>
          <tr><th>Nif de cliente</th><td>{$pedido->nif}</td></tr>
          <tr><th>Fecha</th><td>{$pedido->fecha->format(Pedido00::FORMATO_FECHA)}</td></tr>
          <tr><th>Observaciones</th><td>{$pedido->observaciones}</td></tr>
          <tr><th>Importe del pedido</th><td>{$pedido->total_pedido}€</td></tr>
        </tbody>
      </table>
      PEDIDO;
    }
    echo "<p><a href=\"/exra500/inicio.php\">Volver a buscar otro pedido</a></p>";
    
    Html::fin();
    ob_end_flush();

  }
}
?>