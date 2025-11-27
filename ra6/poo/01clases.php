<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Empleado.php");
inicioHtml("POO en PHP", ["/estilos/general.css"]);

// Instanciar un objeto sin el método constructor
/*
$emp1 = new Empleado;

$emp1->nif = "30000001A";
$emp1->nombre = "Manuel";
$emp1->apellidos = "García López";
$emp1->salario = 2000;


echo "<p>Empleado: {$emp1->nif} : {$emp1->nombre} {$emp1->apellidos} y gana {$emp1->salario}</p>";

// Cuidado al usar el $ para acceder a una propiedad
echo "<p>Uso el $ para acceder al nif: {$emp1->$nif}</p>";

// Cuando usamos $emp1->$nif el $nif es una variable independiente que contiene
// el nombre de la propiedad a la que queremos acceder
$propiedadNif = "nif";
echo "<p>Acceso a nif con una variable {$emp1->$propiedadNif}</p>";
*/

// Instanciar un objeto con el constructor de la clase
$emp2 = new Empleado("30000001A", "Manuel", "García López", 2000);

// Acceso a las propiedades de la clase con el operador -> y sin $
echo "<p>Empleado: {$emp2->nif} : {$emp2->nombre} {$emp2->apellidos} y gana {$emp2->salario}</p>";

// Acceso a las constantes de la clase con el operador ::
echo "<p>El % de IRPF es " . Empleado::IRPF . " y de Seguridad Social es " . Empleado::SS . "</p>";
echo "<p>El % de IRPF es " . $emp2::IRPF . " y de Seguridad Social es " . $emp2::IRPF . "</p>";

// Comparación de igualdad con objetos
/* Si se usa ==, 2 instancias de objeto son iguales si el valor de todas sus propiedades son iguales.
   Si se usa ===, 2 instancias de objeto son iguales si referencian a la misma instancia */
$emp3 = new Empleado("30000001A", "Manuel", "García López", 2000);
echo "<p>";
if( $emp2 == $emp3 ) echo "Emp2 y Emp3 son iguales en el valor de sus propiedades<br>";
else "Emp2 y Emp3 no tienen los mismos valores en sus propiedades<br>";

if( $emp2 === $emp3 ) echo "Emp2 y Emp3 son dos variables que apuntan (referencian) al mismo objeto<br>";
else echo "Emp2 y Emp3 son dos variables que apuntan cada una a un objeto<br>";
echo "</p>";

$emp4 = $emp3;
if( $emp4 === $emp3 ) echo "Emp4 y Emp3 son dos variables que apuntan (referencian) al mismo objeto<br>";
else echo "Emp4 y Emp3 son dos variables que apuntan cada una a un objeto<br>";

finHtml();
?>