<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Vivienda.php");
require_once("Piso.php");

inicioHtml("Herencia", ["/estilos/general.css"]);
echo "<header>Herencia en PHP</header>";
echo "<h3>Bases de la herencia y nivel de acceso</h3>";
$v1 = new Vivienda("a1", "c/ Mayor, 3", 80);
echo "<p>Vivienda: {$v1->ref} - {$v1->direccion} - Sup: {$v1->superficie}</p>";

$p1 = new Piso("a2", "Av Libia, 4", 90, 5, "B");
echo "<p>Piso: {$p1->ref} - {$p1->direccion}, {$p1->planta} {$p1->puerta}";
echo "- Sup: {$p1->superficie}";

echo "<h3>Sobrescritura de métodos</h3>";
echo "<p>" . $p1->getDireccionCompleta() . "</p>";

echo "<p>$p1</p>";
echo "<p>El valor de mi piso es " . $p1->getValorEstimado(1000) . "</p>";

echo "<h3>Polimorfismo. Despacho de métodos dinámico</h3>";

?>
