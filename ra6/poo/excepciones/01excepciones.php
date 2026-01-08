<?php
/*
EXCEPCIONES EN PHP
------------------

Excepción: Error en la ejecución de un programa que ocurre por una
situación anómala. Puede provocar la salida abrupta de la aplicación

Las excepciones se pueden gestionar. Una excepción se puede capturar,
y después, ejecutar código que permite paliar o corregir, dependerá
de la propia excepción, sus efectos. 

Cuando no se puede continuar, se puede hacer una salida ordenada
de la aplicación:
  - Cerrar los recursos abiertos: ficheros y conexiones de BD o red.
  - Informar sobre el error producido.

En PHP:
    - Una excepción al generarse se instancia un objeto de la clase
      Exception o de una clase derivada.

    - Cuando ocurre una excepción se crea un objeto excepción y se 
      lanza (se interrumpe el flujo del programa y se desvía a la
      excepción).
    
    - La excepción se lanza en cuanto ocurre y se informa al método
      o función donde ha ocurrido la excepción.

    - Si la función o método no tratan la excepción, se pasa a la función
      o método que invoco al que provocó la excepción. 

    - Propagación de la excepción:
      f1() --> f2() --> f3() --> f4() --> f5()
                                           | --> Se lanza una excepción $e
                                           No se gestiona $e
                                  $e <-- 
                                  No se gestiona $e
                          $e <--
                          No se gestionar $e
                  $e <-- 
                  No se gestiona $e
        $e <--
        No se gestiona
    El sistema de gestión de excepciones de PHP la captura y la trata.
    Tratamiento de PHP: Interrumpir el programa y emitir un aviso en la salida.

  - Gestión de excepciones en PHP
  try {

    // Código que se supone puede
    // provocar una excepción

  }
  catch( Exception $e) {
    // Código que gestiona la excepción

  }
  finally {
    // Código que se ejecuta, tanto si ha
    // ocurrido la excepción, como si no.

    // Acciones de limpieza: cerrar ficheros, conexiones BD
  }
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

function MuestraExcepcion($e) {
  echo "<h3>Error en la aplicación</h3>";
  echo "<p>Tipo de excepción: " . $e::class . "</p>";
  echo "<p>Mensaje de error: " . $e->getMessage() . "</p>";
  echo "<p>Código error: " . $e->getCode() . "</p>";
  echo "<p>Archivo: " . $e->getFile() . "</p>";
  echo "<p>Línea: " . $e->getLine() . "</p>";
}

inicioHtml("Excepciones en PHP", ["/estilos/general.css"]);

// Ejemplo 1: Lanzamos una excepción sin gestionar
/*
$numero = "a";
echo "<p>Vamos a calcular el cuadrado de nº</p>";
$cuadrado = $numero ** 2;   // TypeError
echo "<p>El cuadrado de número es {$cuadrado}</p>";
*/

// Ejemplo 2: Se captura la excepción
try {
  $numero = "a";
  $cuadrado = $numero ** 2;
  echo "<p>El cuadrado de $numero es $cuadrado</p>";
}
catch(TypeError $te) {
  // Ver información de la excepción
  echo "<h3>Error en la aplicación</h3>";
  echo "<p>Tipo de excepción: " . $te::class . "</p>";
  echo "<p>Mensaje de error: " . $te->getMessage() . "</p>";
  echo "<p>Código error: " . $te->getCode() . "</p>";
  echo "<p>Archivo: " . $te->getFile() . "</p>";
  echo "<p>Línea: " . $te->getLine() . "</p>";

  // Si no fuera posible continuar: exit();

  // Acción correctiva
  // $n = intval($numero);
  $numero = 5;
  $cuadrado = $numero ** 2;
}
finally {
  echo "<h4>Se ha terminado el bloque de gestión de la excepción</h4>";
  echo "<p>El cuadrado de $numero es $cuadrado</p>";
}

// Ejemplo 3: Se contemplan 2 excepciones
try {
  $cadena = "hola";
  $x = strpos($cadena, "h", 16);

  $numero = "a";
  $cuadrado = $numero ** 2;
  echo "<p>El cuadro es $cuadrado y la posición de h es $x</p>";
}
catch( ValueError $ve ) {
  MuestraExcepcion($ve);
  $cadena = "En realidad tendría que tener la h en una cadena más larga";
  $x = strpos($cadena, "h", 16);
}
catch( TypeError $te ) {
  MuestraExcepcion($te);
  if( gettype($numero) != "int") {
    $numero = 5;
  }
  $cuadrado = $numero ** 2;
}
finally {
  echo "<h3>Se termina la gestión de la excpeción</h3>";
  echo "El cuadrado es $cuadrado y la posición es $x</p>";
}

// Ejemplo 4: Gestionar juntas varias excepciones
try {
  $cadena = "hola";
  $x = strpos($cadena, "h", 16);

  $numero = "a";
  $cuadrado = $numero ** 2;
  echo "<p>El cuadro es $cuadrado y la posición de h es $x</p>";

}
catch( TypeError | ValueError $e ) {
  MuestraExcepcion($e);
}

// Ejemplo 5: Cláusula finally
echo "<h3>Cláusula finally</h3>";
try {
  $puntero = fopen("../serializacion/archivo_pepe.txt", "r");
  $numeroLineas = "#";
  while( $linea = fgets($puntero) ) {
    $numeroLineas+=1;
  }
}
catch( TypeError $te ) {
  MuestraExcepcion($te);
}
finally {
  // Acciones de limpieza
  echo "<p>Cerrando el fichero</p>";
  fclose($puntero);
}


// Ejemplo 6: El desarrollador lanza excepciones
echo "<h3>Lanzamiento de excepciones</h3>";

try {
  $nif = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);
  if( !$nif ) {
    throw new Exception("El nif no es válido", 1000);
  }
}
catch(Exception $e) {
  MuestraExcepcion($e);
}

// Ejemplo 7: Excepciones personalizadas.
echo "<h3>Excepciones personalizadas</h3>";
class AbrirFicheroExcepcion extends Exception {
  protected string $url;
  protected string $textoEnlace;

  public function __construct(string $m, int $c, string $u, string $te, 
              ?Exception $previo = null ) {
    parent::__construct($m, $c, $previo);

    $this->url = $u;
    $this->textoEnlace = $te;
  }

  public function getPuntoRecuperacion(): array {
    return [$this->url, $this->textoEnlace];
  }
}

try {
  if( !file_exists("ficheroNoExiste.txt") ) {
    throw new AbrirFicheroExcepcion("El fichero ficheroNoExiste.txt no existe",
      1001, "http://dwes.com/", "Ir al inicio de la aplicación");
  }
  $puntero = fopen("ficheroNoExiste.txt", "r");

}
catch( AbrirFicheroExcepcion $afe ) {
  MuestraExcepcion($afe);
  $pr = $afe->getPuntoRecuperacion();
  echo "<p>Punto de recuperación:";
  echo "<a href='{$pr[0]}'>{$pr[1]}</a></p>";
}

finHtml();

?>