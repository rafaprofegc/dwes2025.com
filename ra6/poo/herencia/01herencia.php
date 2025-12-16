<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Vivienda.php");
require_once("Piso.php");
require_once("Casa.php");
require_once("Apartamento.php");

function verValorEstimado(Vivienda $v): void {
  $valor = $v->getValorEstimado(2000);
  echo "<p>El valor de la vivienda es: $valor</p>";
}

inicioHtml("Herencia", ["/estilos/general.css"]);
echo "<header>Herencia en PHP</header>";
echo "<h3>Bases de la herencia y nivel de acceso</h3>";
/*
$v1 = new Vivienda("a1", "c/ Mayor, 3", 80);
echo "<p>Vivienda: {$v1->ref} - {$v1->direccion} - Sup: {$v1->superficie}</p>";
*/

$p1 = new Piso("a2", "Av Libia, 4", 90, 5, "B");
echo "<p>Piso: {$p1->ref} - {$p1->direccion}, {$p1->planta} {$p1->puerta}";
echo "- Sup: {$p1->superficie}";

echo "<h3>Sobrescritura de métodos</h3>";
echo "<p>" . $p1->getDireccionCompleta() . "</p>";

echo "<p>$p1</p>";
echo "<p>El valor de mi piso es " . $p1->getValorEstimado(1000) . "</p>";

echo "<h3>Polimorfismo. Despacho de métodos dinámico</h3>";
$c1 = new Casa("30B", "c/ Arcos, 3", 150, 30, 20);

verValorEstimado($c1);
verValorEstimado($p1);

$a1 = new Apartamento("30C", "c/ Menor, 8", 45, 3, "C", 2);
echo "<p>Apartamento: $a1</p>";
echo "<p>La planta y la puerta del apartamento: " . $a1->getPlantaPuerta() . "</p>";
?>
