<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Funciones</title>
  </head>
  <body>
<h1>Funciones</h1>
<p>
  Conjunto de sentencias con un nombre asociado que se ejecutan
  a discreción del desarrollador cuando se necesita.
</p>
<p>
  Invocación o llamada de la función -> La sentencia que solicita
  la ejecución de la función, momento en el cual el flujo
  del programa se desvía a la primera snetencia de la función 
  y comienza su ejecución.
</p>
<p>
  Las funciones habitualmente utilizan datos que se les pasa 
  en el momento de invocarse. Estos datos se conocen como 
  parámetros o argumentos. 
</p>
<p>
  Pueden devolver (no es obligatorio) un o varios valores al punto 
  de invocación, que posteriormente se emplea en cualquier expresión.
</p>

<p>Tipos de función:
  <ul>
    <li>Internas, integradas o predefinidas.- Las propias del lenguaje y 
      que forman la biblioteca estándar del lenguaje. Sin embargo, es posible 
      que el lenguaje tenga habilitados módulos, los cuales también 
      proporcionan funciones integradas.<li>
    <li>Métodos -> Funciones definidas dentro de una clase de objetos.</li>
    <li>De usuario -> Funciones que el desarrollador define en sus scripts.</li>
  </ul>
</p>
<h2>3.1 Definición de una función</h2>
<p>
  Antes de utilizar una función hay que de finirla. En su definición se indica:
  - Nombre de la función <br>
  - Conjunto de parámetro o argumentos<br>
  - Sentencias de la función, incluyendo el retorno de su valor<br>

  Sintaxis:
  <pre>
    function <nombreFuncion>([<par1>, <par2>, ... , <parN>]) {
      <sentencias>
      return <expresion>;
    }
  </pre>
</p>

<h2>3.2 Paso de parámetros</h2>
<p>Parámetros: Dato que la función necesita para ejecutarse. 
  Los parámetros permiten ejecutar una función muchas veces con 
  diferentes valores. <br>
  Tipos de parámetros:
  <ul>
    <li>Parámetro formal -> El que se define en la cabecera de la función. Este 
      parámetro es equivalente a una variable y, por ende, sigue las reglas de 
      nombrado de identificadores. Simplemente se les conoce como PARÁMETROS.</li>
    <li>Parámetro real -> El que se indica en la invocación de la función. Puede 
      ser una expresión de cualquier tipo (literal, variable, una expresión 
      que combine operandos y operadores, incluyendo el valor que devuelve 
      otra función). Se les conoce como ARGUMENTOS.
  </ul>
<?php
define("PI", 3.14);

function areaTriangulo($base, $altura) {
  $area = $base * $altura / 2;
  return $area;
}
?>
<h2>Invocación de las funciones de ejemplo</h2>
<?php
$area = areaTriangulo(5, 4);
echo "<p>El área del triángulo con base 5 y altura 4 es $area</p>";

$b = 10;
$a = 5;
$area = areaTriangulo($b, $a);
echo "<p>El área del triángulo con base $b y altura $a es $area</p>";

echo "<p>El área del triángulo con base $b y altura 8 es " . areaTriangulo($b, 8) . "</p>";
?>
<h2>Paso de parámetros por valor y por referencia</h2>
<p>
  Entre los argumentos y los parámetros de la función hay una asignación en orden, es decir,
  el primer argumento corresponde al primer parámetro, el segundo argumento al segundo 
  parámetros, ... así sucesivamente. 
  <ul>
    <li>Paso de parámetro por valor -> El argumento en la llamada de la función SE COPIA 
    en el parámetro de la función. SON DOS VARIABLES DIFERENTES, CADA UNA CON SU VALOR.
    Al terminar la función el parámetro desaparece y el argumento permanece.</li>
  </ul>
</p>
<p> Paso por valor: Cambios hechos a los parámetros no afectan a sus argumentos.<br>

<pre>
         nombreFuncion($arg1, $arg2, $arg3)
                         |      |      |
function nombreFuncion($par1, $par2, $par3) {
  // sentencias
  return;
}
</pre>
</p>
<?php
function areaPentagono($perimetro, $apotema) {
  echo "<p>Función areaPentágono: Valores de los argumentos: $perimetro - $apotema</p>";

  // Asigno nuevos valores a los parámetros
  $perimetro = 10;
  $apotema = 3;

  $area = $perimetro * $apotema / 2;

  echo "<p>Función areaPentágono: Valores cambiados: $perimetro - $apotema</p>";
  return $area;
}

$perimetro = 20;
$apotema = 4;
echo "<p>Script principal: Valores de los argumentos $perimetro - $apotema</p>";
$area = areaPentagono($perimetro, $apotema);
echo "<p>Script principal: Valores de los argumentos $perimetro - $apotema</p>";
echo "<p>El área del pentágono con perímetro $perimetro y apotema $apotema es $area</p>";
?>
<ul>
  <li>Paso por referencia -> El argumento que se pasa en la invocación de la función 
    es UNA REFERENCIA a una variable (dirección de memoria). Por tanto, solo hay 
    UNA ÚNICA VARIABLE entre el argumento y su correspondiente parámetro. De ahí que 
    si dentro de la función se modifica el parámetro, el nuevo valor es visible 
    en el script principal.</li>
</ul>
<?php

function areaPentagono2(&$perimetro, &$apotema) {
  echo "<p>Función areaPentagono2: Valores de los parámetros $perimetro - $apotema</p>";
  $perimetro = 50;
  $apotema = 8;
  echo "<p>Función areaPentagono2: Valores de los parámetros $perimetro - $apotema</p>";
  $area = $perimetro * $apotema / 2;
  return $area;
}

$p = 30;
$a = 5;
echo "<p>Script principal: Valores de perímetro y apotema son $p - $a</p>";
$area = areaPentagono2($p, $a);
echo "<p>Script principal: Valores de perímetro y apotema son $p - $a</p>";
echo "<p>El área del pentágono con perímetro $p y apotema $a es $area</p>";
?>
<p>Los arrays se pasan por valor y los objetos se pasan por referencia y los tipos primitivos por valor</p>
<?php
function duplicaValoresArray(&$array) {
  for($i = 0; $i < count($array); $i++) {
    $array[$i] = $array[$i] * 2;
  }
}
$numeros = [5, 4, 3, 2, 1, 0];
echo "<P>Array antes de la función: ";
foreach($numeros as $numero) echo "$numero ";
echo "</p>";

duplicaValoresArray($numeros);
echo "<P>Array después de la función: ";
foreach($numeros as $numero) echo "$numero ";
echo "</p>";
?>
<h3>Parámetros pasados por defecto</h3>
<p>Puede definir un parámetro en la cabecera de la función con un valor por defecto. Si en la invocación 
  no indica un argumento para ese parámetro, utiliza el valor indicado por defecto. El valor por 
defecto solo puede ser un literal, no una expresión</P>
<?php
function volumenCilindro($radio, $altura = 10) {
  $volumen = $radio ** 2 * PI * $altura;
  return $volumen;
}

$volumen = volumenCilindro(5);
echo "<p>El volumen del cilindro es $volumen cm<sup>3</sup></p>";
$volumen = volumenCilindro(3, 11);
echo "<p>El volumen del cilindro es $volumen cm<sup>3</sup></p>";

/* Esto no se puede hacer salvo que se empleen argumentos con nombre
function volumenCilindro2($altura = 10, $radio) {

}
volumenCilindro2(5);
*/
?>
<h3>3.3.3 Tipos de datos en los parámetros y valor de devolución de la función</h3>
<p>Puedo indicar un tipo de datos a cada parámetro en la definición de la función. Al invocar 
  la función se intenta convertir el argumento al tipo indicado para el parámetro y si no puede 
  entonces se dispara la excepción TypeError. Obviamente, si puede hacer la conversión, continua</p>
  <p>Tipos de datos:
    <ul>
      <li>int</li>
      <li>float</li>
      <li>boolean</li>
      <li>string</li>
      <li>callable (una función)</li>
      <li>object</li>
      <li>&lt;Clase&gt;</li>
      <li>array</li> 
    </ul>
    Cada tipo puede ir precedido por ? indicando que se espera un argumento de ese tipo o null</p>
<?php


function areaRectangulo(float $base, float $altura): ?float {
  if( $base < 0 || $altura < 0 ) {
    $area = null;
  }
  else {
    $area = $base *$altura;
  }
  return $area;
}


echo "<p>Área del rectángulo con base 8 y altura 5: ";
$area = areaRectangulo(8, 5);
echo "$area</p>";

echo "<p>Área del recángulo con base \"9\" y altura 4:";
$area = areaRectangulo("9.3", 4);
echo "$area</p>";

/*
echo "<p>El segundo parámetro no se puede convertir: ";
$area = area_rectangulo(5, "ab45");
echo "$area</p>";
*/

echo "<p>Área del rectángulo con base negativa: ";
$area = areaRectangulo(-5, 8);
echo $area ? $area : " El valor ha sido nulo";
echo "</p>";
?>
<h3>Parámetros con nombre (en la llamada)</h3>
<p>Consiste en utilizar el nombre de un parámetro en la invocación de la función. Esto supone:
  <ul>
    <li>El valor del argumento se pasa mediante una expresión en la que se pone el nombre del 
      parámetro sin $ y su valor después del operador :, es decir, par : expr</li>
    <li>No es necesario respetar el orden de definición de los parámetros en la invocación</li>
  </ul>
</p>
<?php
$volumen = volumenCilindro( $radio = 8, $altura = 5);
echo "<p>El volumen del cilindro con radio 8 y altura 5 es $volumen</p>";

$volumen = volumenCilindro( altura : 5, radio : 9);
echo "<p>El volumen del cilindro con radio 9 y altura 5 es $volumen</p>";
?>
<h3>Valor de devolución</h3>
<p>Con la sentencia return, en cualquier lugar de la función, se devuelve un valor al punto 
  de invocación. Cualquier sentencia debajo de return, no se ejecuta.</p>

<p>Podemos devolver más de un valor. Para ello tenemos que primero devolver en la función 
  un array con todos los valores que queremos devolver. En el punto de invocación podemos
  recoger el array con una función list()</p>

<?php
function areaLongitud(float $radio): array {
  $resultado[] = $radio ** 2 * PI;
  $resultado[] = $radio * 2 * PI;

  return $resultado;
}

list($areaCirculo, $longitudCircunferencia) = areaLongitud(5);
echo "<p>El área del círculo con radio 5 es $areaCirculo y su circunferencia es $longitudCircunferencia</p>";

$areaYLongitud = areaLongitud(8);
echo "<p>El área del círculo con radio 8 es {$areaYLongitud[0]} y su circunferencia es {$areaYLongitud[1]}</p>";
?>
<h2>Ámbito y visibilidad</h2>
<p>
Ámbito es la parte del programa donde una variable existe. <br>
Visibilidad es la parte del programa donde una variable existe y es accesible<br>
<ul>
  <li>En una función  las variables definidas en el script no son accesibles, salvo si las 
    defino en la función como globales o accedo a ellas con el array superglobal $GLOBALS</li>
</ul>

</body>
</html>