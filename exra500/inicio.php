<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/exra500/util/Html.php");

use exra500\util\Html;

Html::inicio("Solicitar un pedido", ["/estilos/general.css"]);
echo <<<FORM
<h3>Búsqueda de pedidos</h3>
<form method="POST" action="index.php">
  <input type="hidden" name="idp" id="idp" value="buscarPedido">
  <fieldset>
    <legend>Introduce un número de pedido</legend>
    <label for="npedido">Nº pedido</label>
    <input type="text" name="npedido" id="npedido" size="5" require>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Buscar">
</form>
FORM;
Html::fin();
?>