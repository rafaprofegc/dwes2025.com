<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operadores y expresiones</title>
  </head>
  <body>
<h1>Estructuras de control</h1>
<h2>Sentencias</h2>
<p>Las sentencias simples acaban en ;, pudiendo haber más de una 
  en una misma línea</p>

<?php
$numero = 3; echo "<p>El número es $numero<br>"; $numero += 3; print "Ahora es $numero</p>";
?>

<p>Un bloque de sentencias es un conjunto de sentencias simples encerradas entre llaves. No 
  suelen ir sueltas, sino formar parte de una estructura de control. Además, se pueden anidar.</p>

<?php
{
  $numero = 5;
  echo "<p>El número es $numero<br>";
  $numero -= 2;
  echo "<p>El resultado es $numero<br>";
  {
    $numero = 8;
    $numero *= 2;
    echo "El número es $numero</p>";
  }
}
?>

<h3>Estructura condicional simple</h3>
<?php
/* 
  Sintaxis:

  if( <condición>) <sentencia>;
*/
$numero = 4;
if( $numero >= 4 ) echo "<p>El número es mayor o igual a 4</p>";

if( $numero === 4 and $numero % 2 === 0 )
  echo "<p>El número es igual 4 y por tanto es número par</p>";

if( $numero === 4 or $numero < 8 ) {
  echo "<p>El número es igual a 4<br>";
  echo "Además, es inferior a 8</p>";
}

if( $numero === 5 - 1 ) {
  echo "<p>El número es igual a 5 - 1</p>";
}

?>

<h3>Estructura condicional compuesta</h3>
<?php
$n1 = 9;
$n2 = 5;
$n3 = 10;
echo "<p>";
if( $n1 == 9 or $n2 < $n1 and $n3 > 10 ) {
  echo "El resultado global ha sido True";
}
else {
  echo "El resultado global ha sido False";
}
echo "</p>";

// If con su única sentencia en una única línea y else con su sentencia en otra línea
echo "<p>";

if( $n1 > 20 or $n3 < 15 ) echo "La expresión ha sido True porque \$n3 es 10";
else echo "La expresión ha sido False";

echo "</p>";

$edad = 15;
echo "<p>";
if( $edad > 18 ) {
  echo "Puedes ver la peli para +18";
}
else {
  echo "No puedes ver la peli porque es para mayores de 18<br>";
  echo "Te faltan " . 18 - $edad . " años para poder verla";
}
echo "</p>";

$tipoCarnet = "C1";
echo "<p>";
if( $edad > 21 and $tipoCarnet === "C1" ) {
  echo "Obtención del carnet de camión<br>";
  echo "Tienes $edad años y al superar los 21 puedes obtener el carnet de $tipoCarnet";
}
else {
  echo "No cumples los requisitos para el carnet $tipoCarnet. ";
  echo "Todavía te faltan " . 21 - $edad . " años";
}
echo "</p>";

if( $edad >= 18 and $edad <= 65 ) { ?>
  <h3>Servicios del gimnasio disponibles</h3>
  <ul>
    <li>Spinning</li>
    <li>Boxeo</li>
    <li>Zumba</li>
  </ul>
<?php
}
else { ?>
  <h3>Servicios para menores o jubilados</h3>
  <ul>
    <li>Taichi</li>
    <li>Pilates</li>
    <li>Yoga</li>
  </ul>
<?php
}

$tipoCarnet = "C1";
if( $tipoCarnet === "C1" ) 
  echo <<<CARNETB1
  <h2>Documentación para solicitar la tarjeta de transporte</h2>
  <ul>
    <li>Fotocopia del carnet de conducir</li>
    <li>Certificado de penales</li>
    <li>Carnet B2</li>
  </ul>
  CARNETB1;
?>
<h2>Sentencia if anidada</h2>
<?php
$nota = 6.5;
echo "<p>Con la nota $nota tienes un ";
if( $nota >= 0 && $nota < 5 ) {
  echo "Suspenso";
}
else {
  if( $nota < 6) {
    echo "Suficiente";
  }
  else {
    if( $nota < 7 ) {
      echo "Bien";
    }
    else {
      if( $nota < 9 ) {
        echo "Notable";
      }
      else {
        if( $nota <= 10 ) {
          echo "Sobresaliente";
        }
        else {
          echo "Error. La nota no puede ser mayor que 10";
        }
      }
    }
  }
}
echo "</p>";

echo "<p>Con una nota de $nota tienes un: ";
if( $nota >= 0 && $nota < 5 ) {
  echo "Suspenso";
}
else if( $nota < 6 ) {
  echo "Aprobado";
}
else if( $nota < 7 ) {
  echo "Bien";
}
else if( $nota < 9 ) {
  echo "Notable";
}
else if( $nota <= 10 ) {
  echo "Sobresaliente";
}
else {
  echo "Error. La nota no puede ser mayor que 10";
}
echo "</p>";
?>
<h2>Estructura condicional múltiple</h2>
<?php
// Sentencia switch
/*
switch( <expresión> ) {
  case <valor1>:
    // Sentencias a ejecutar si expresión === valor1
    break;

  case <valor2>:
    // Sentencias a ejecutar si expresión === valor2

  ....
  case <valorN>:
    // Sentencias a ejecutar si expresión === valorN
  [default:
    // Sentencias si expresión !== de todos los valores
  ]
}
  // Siguiente sentencia a switch
*/
$nota = 7;
echo "<p>Con un $nota tienes un ";
switch($nota) {
  case 0:
  case 1:
  case 2:
  case 3:
  case 4:
    echo "Suspenso";
    break;
  case 5:
    echo "Aprobado";
    break;
  case 6:
    echo "Bien";
    break;
  case 7:
  case 8:
    echo "Notable";
    break;
  case 9:
  case 10:
    echo "Sobresaliente";
    break;
  default:
    echo "La nota tiene que estar enter 0 y 10";
}
echo "</p>";

$perfil = "admin";
echo "<p>Con un perfil de $perfil tienes acceos a: ";
switch( $perfil ) {
  case "user":
    echo "Lectura y escritura en la BD";
    break;
  case "admin":
    echo "Control total en la BD";
    break;
  case "invitado":
    echo "Lectura en la BD";
    break;
  default:
    echo "El perfil no es correcto";
}
echo "</p>";
?>
<h2>Expresión match</h2>
<?php
$nota_suspensa = 4.5;
$calificacion = match($nota) {
  0, 1, 2, 3, 4, $nota_suspensa     => "Suspenso",
  4 + 1, 6 - 1                 => "Aprobado",
  "5"               => "Aprobado",
  6                 => "Bien",
  7,8               => "Notable",
  9,10              => "Sobresaliente",
  default           => "Error. La nota tiene que estar entre 0 y 10"
};

echo "<p>Con una nota de $nota tienes un $calificacion</p>";
?>
<h2>Operador ternario</h2>
<?php
// Sintaxis: 
//  <condición> ? <expresión_true> : <expresión_false>;

$nota = 6;
$resultado = $nota >= 5 ? "Con un $nota, estás APROBADO" : "Con un $nota, estás SUSPENSO";
echo "<p>$resultado</p>";

$nombre = "Juan Gómez";
$conBeca = false;
?>
<form method="POST">
<input type="text" name="nombre" size="30" value="<?=isset($nombre) ? $nombre : ""?>">
<br>
<input type="checkbox" name="conBeca" <?=$conBeca ? "checked" : ""?> value="Si">
</form>

<h2>Operador de fusión de null</h2>
<?php

$metodo = "POST";
$segundoMetodo = "GET";
$por_defecto ="main";

$resultado = $metodo ?? $segundoMetodo ?? $por_defecto;
echo "<p>El resultado es: $resultado</p>";

$metodo = null;
$resultado = $metodo ?? $segundoMetodo ?? $por_defecto;
echo "<p>El resultado es: $resultado</p>";

$segundoMetodo = null;
$resultado = $metodo ?? $segundoMetodo ?? $por_defecto;
echo "<p>El resultado es: $resultado</p>";
?>

<h2>Bucles</h2>
<ul>
  <li>For con contador (estilo de Java y C++)</li>
  <li>For para colecciones de datos</li>
  <li>While</li>
  <li>Do .. while</li>
  <li>Sentencias break y continue</li>
</ul>

<h3>Bucle for con contador de bucle</h3>
<?php
// Tabla de multiplicar
$numero = 4;
echo "<p>La tabla de multiplicar del 4:<br>";
for( $i = 1; $i<= 10; $i++) {
  echo "$numero x $i = " . $numero * $i . "<br>";
}
echo "</p>";

echo "<p>Cuenta atrás</p>";
for( $i = 10; $i >= 0; $i--) {
  echo "Quedan $i segundos<br>";
}
echo "¡Ignición!</p>";

// Varias expresiones en el inicio del contador
// y en la parte del incremento
echo "<p>";
for( $i = 10, $j = 0; $i >= 5 && $j < 8; $i--, $j++) {
  echo "Valor de i es $i y valor de j es $j<br>";
}
echo "</p>";

echo "<p>";
// Visualizar los números pares entre 2 y 20
for( $i = 2; $i <= 20; $i+=2 ) {
  echo "El número par es $i<br>";
}
echo "</p>";
?>

</body>
</html>