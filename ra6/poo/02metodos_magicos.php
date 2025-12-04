<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

require_once("Direccion.php");

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

unset($dir1->tipoVia);
echo "<p>El tipo de vía en dir1 es {$dir1->provincia}</p>";
echo "<p>El tipo de vía en dir1 es {$dir1->tipoVia}</p>";

finHtml();
?>