<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Empleado.php");
inicioHtml("POO en PHP", ["/estilos/general.css"]);

// 1º Instanciar un objeto sin el método constructor
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

// 2º Instanciar un objeto con el constructor de la clase
$emp2 = new Empleado("30000001A", "Manuel", "García López", 2000, [], null);

// 3º Acceso a las propiedades de la clase con el operador -> y sin $
echo "<p>Empleado: {$emp2->nif} : {$emp2->nombre} {$emp2->apellidos} y gana {$emp2->salario}</p>";

// 4º Acceso a las constantes de la clase con el operador ::
echo "<p>El % de IRPF es " . Empleado::IRPF . " y de Seguridad Social es " . Empleado::SS . "</p>";
echo "<p>El % de IRPF es " . $emp2::IRPF . " y de Seguridad Social es " . $emp2::IRPF . "</p>";

// 5º Comparación de igualdad con objetos
/* Si se usa ==, 2 instancias de objeto son iguales si el valor de todas sus propiedades son iguales.
   Si se usa ===, 2 instancias de objeto son iguales si referencian a la misma instancia */
$emp3 = new Empleado("30000001A", "Manuel", "García López", 2000, [], null);
echo "<p>";
if( $emp2 == $emp3 ) echo "Emp2 y Emp3 son iguales en el valor de sus propiedades<br>";
else "Emp2 y Emp3 no tienen los mismos valores en sus propiedades<br>";

if( $emp2 === $emp3 ) echo "Emp2 y Emp3 son dos variables que apuntan (referencian) al mismo objeto<br>";
else echo "Emp2 y Emp3 son dos variables que apuntan cada una a un objeto<br>";
echo "</p>";

$emp4 = $emp3;
if( $emp4 === $emp3 ) echo "Emp4 y Emp3 son dos variables que apuntan (referencian) al mismo objeto<br>";
else echo "Emp4 y Emp3 son dos variables que apuntan cada una a un objeto<br>";

// 6º Iteración sobre las propiedades de los objetos
echo "<h3>Propiedades de los objetos</h3>";
foreach( $emp2 as $propiedad => $valorPropiedad ) {
   echo "<p>$propiedad -> $valorPropiedad</p>";
}

// Solo se puede iterar por las propiedades públicas
// desde fuera de la clase
$emp5 = new Empleado("40A", "María", "García", 2500, [], null, "600101010");

foreach($emp5 as $propiedad => $valorPropiedad) {
   echo "<p>$propiedad: $valorPropiedad</p>";
}

// 7º Métodos de instancia
echo "<h3>Métodos de objeto</h3>";
$salarioNeto = $emp2->getSalarioNeto();
echo "<p>El salario neto de {$emp2->nombre} es $salarioNeto</p>";

$emp6 = new Empleado("50A", "Javier", "Gómez", 3000, [], null);
$salarioNeto = $emp6->getSalarioNeto();
echo "<p>El salario neto de {$emp6->nombre} es $salarioNeto</p>";

// 8º Tipos de datos en propiedades de método y devolución de método

// 9º Promoción del constructor
$emp7 = new Empleado("60A", "Manuela", "Sánchez", 4000, [], $direccion = null);

// 10º Uso de objetos (instancias) como argumentos de métodos
echo "<h3>Uso de objetos como argumentos en métodos</h3>";
$emp8 = new Empleado("60A", "Manuela", "Sánchez", 4000, [], null);

if( $emp7->esIgual($emp8) ) {
   echo "<p>Emp7 y Emp8 son iguales</p>";
}
else {
   echo "<p>Emp7 y Emp8 son DIFERENTES</p>";
}

// 11º Devolución de objetos en un método
$emp9 = $emp8->empleadoDuplicaSalario();
echo "<h3>Devolución de objetos en un método</h3>";
echo "<p>El empleado 9 es $emp9->nombre $emp9->apellidos y gana $emp9->salario €</p>";

// 12º Métodos estáticos
$porcentajes = Empleado::getPorcentajes();
echo "<p>Los porcentajes son: $porcentajes</p>";

// Propiedades estáticas
echo "<p>El % de IRP es " . Empleado::$IRPF2 . "</p>";
Empleado::$IRPF2 = 0.3;
echo "<p>El % de IRP es " . Empleado::$IRPF2 . "</p>";

// Los miembros estáticos solo tienen una copia de su valor
$emp8::$IRPF2 = 0.4;
echo "<p>El % de IRPF es " . $emp9::$IRPF2 . "</p>";

finHtml();
?>
