<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Direccion.php");
require_once("Empleado.php");

inicioHtml("Métodos mágicos", ["/estilos/general.css"]);

$dir1 = new Direccion("c/", "Mayor", 3, 2, "A", 4, "B", 28000, "Madrid");
echo "<h3>Acceso a propiedades</h3>";
// echo "<p>La dirección es $dir1->tipoVia </p>"; // Error, es privada

/* Métodos getter and setter
  Un método, público, para acceder (getter) o asignar (setter) una propiedad
  no pública (private o protected) de la clase

  Generalmente, el método getter o setter tiene un formato de nombre:
    getPropiedad()  -> Getter. Prefijo get y el nombre de la propiedad
                               en CamelCase
    setPropiedad()  -> Setter. Prefijo set y el nombre de la propiedad 
                               en CamelCase
*/

// Utilizo los métodos getter y setter para acceder a tipoVia
echo "<p>El tipo de vía es " . $dir1->getTipoVia() . "</p>";
$dir1->setTipoVia("Av");
echo "<p>El tipo de vía es " . $dir1->getTipoVia() . "</p>";

$dir1->setTipoVia("Gl");
echo "<p>El tipo de vía es " . $dir1->getTipoVia() . "</p>";

// Utilizar los métodos mágicos __get() y __set()
echo "<p>Propiedad no declarada: $dir1->provincia</p>";

echo "<p>Dirección: {$dir1->tipoVia} {$dir1->nombreVia}, {$dir1->numero}</p>";
$dir1->nombreVia = "Arcos de la Frontera";
echo "<p>Dirección: {$dir1->tipoVia} {$dir1->nombreVia}, {$dir1->numero}</p>";
$dir1->tipoVia = "Glorieta";
echo "<p>Dirección: {$dir1->tipoVia} {$dir1->nombreVia}, {$dir1->numero}</p>";

if( isset($dir1->provincia) ) {
  echo "<p>La propiedad provincia existe y es != de null</p>";
}
else {
  echo "<p>La propiedad provincia NO EXISTE o es null</p>";
}

echo "<h3>Borrado de propiedades</h3>";
/*
unset($dir1->tipoVia);
echo "<p>El tipo de vía en dir1 es {$dir1->provincia}</p>";
echo "<p>El tipo de vía en dir1 es {$dir1->tipoVia}</p>";
*/
echo "<h3>Convertir objeto en cadena</h3>";
echo "<p>Dirección 1" . $dir1 . "</p>";

echo "<h3>Clonación de objetos</h3>";
$num1 = 8;
$num2 = $num1;
$num1 = 10;
echo "<p>Los tipos primitivos tienen su propio espacio: $num1 y $num2</p>";

$dir2 = $dir1;
echo "<p>" . ($dir1 === $dir2 ? "dir1 y dir2 apuntan al mismo objeto en memoria" : "Hay 2 objetos") . "</p>";

$dir2 = clone $dir1;
echo "<p>" . ($dir1 === $dir2 ? "dir1 y dir2 apuntan al mismo objeto en memoria" : "Hay 2 objetos") . "</p>";

/* Proceso en la clonación de un objeto 
  - Se crea un nuevo objeto
  - Se copian el valor de todas las propieddades del objeto clonado
    en el nuevo objeto.
  - Ejemplo
    $dir2 = clone $dir1

    $dir2 = new Direccion();
    $dir2->tipoVia = $dir1->tipoVia
    $dir2->nombreVia = $dir1->nombreVia
    ...
    $dir2->localidad = $dir1->localidad
  */

$emp1 = new Empleado("30A", "Manuel", "García", 2000, [], $dir1, "957");
$emp2 = clone $emp1;

/*
  $emp2 = new Empleado()
  $emp2->nombre = $emp1->nombre
  $emp2->apellidos = $emp1->apellidos
  ...
  $emp2->direccion = $emp1->direccion;

  // Si tiene el método __clone()
  $this->direccion = clone $this->direccion;
*/
if( $emp1->direccion === $emp2->direccion ) {
  echo "<p>Los dos empleados apuntan a la misma dirección</p>";
}

echo "<h3>Sobrecarga de métodos</h3>";
$dir1->setTipoVia("Av");
echo "$dir1";

$dir1->metodoNoExiste();

// Mapeo de métodos
$dir1->cambiarVia("Pz");
echo "$dir1<br>";

$dir1->ponVia("Crta");
echo "$dir1<br>";

// Depuración de objetos
echo "<h3>Depuración de objetos</h3>";
var_dump($dir1);

finHtml();
?>