<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Carga directa de imagen", ["/estilos/general.css"]);
?>
<h3>Carga directa de una imagen desde una petición GET</h3>
<p>A continuación incluyo una imagen</p>
<img src="http://dwes.com/ra4/02enviar_imagen.php?imagen=test.jpg">
<br>
<img src="/ra4/02enviar_imagen.php">
<?php
finHtml();
?>