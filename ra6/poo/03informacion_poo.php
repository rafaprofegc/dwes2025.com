<?php
/*
  Funciones útiles para obtener información de las clases/objetos

  - is_object($var) -> True si $var es un objeto
  - gettype($var)   -> Si $var es un objeto, devuelve object
  - get_class($var) -> Nombre de la clase de $var

  - El nombre de la clase se puede obtener con:
    - $object::class
    - self::class -> Dentro de la clase, generalmente en un método estático
    - __CLASS__ -> Constante mágica. En cualquier lugar de la clase
    - Clase::class
    - get_class($var);

  - Comprobar si una clase tiene una propiedad o método
    - property_exists( Clase | $objeto, string propiedad ) -> True si propiedad
                                                              es de la clase Clase
    - method_exists( Clase | $objeto, string método) -> True si método es de la 
                                                        clase Clase.

  - El operador instanceof
  $objeto instanceof Clase -> True si $objeto es una instancia de Clase

*/
require_once("Direccion.php");
$d = new Direccion("c/", "Mayor", 3, 4, "B", 5, "A", 28000, "Madrid");
if( $d instanceof Direccion ) {
  echo "<h3>\$d es una instancia de Dirección</h3>";
}

if( is_object($d) ) echo "<p>\$d es un objeto</p>";
?>