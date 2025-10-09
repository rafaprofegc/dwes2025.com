<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arrays</title>
  </head>
  <body>
<h1>Arrays</h1>
<p>Un array es un conjunto de elementos que se referencian con el mismo nombre.
  A cada variable del array se le conoce como componente o elemento del array.
  Cada componente tiene asociado una clave que se emplea para acceder a ese 
  componente.
</p>
<p>En PHP los arrays son muy flexibles. Hay dos tipos:
  <ul>
    <li>Escalares -> Si la clave para acceder al componente es un número.</li>
    <li>Asociativos -> Si la clave para acceder al componente es una cadena
    de caracteres.</li>
  </ul>
  <p>Para acceder a un componente individual del array se utiliza el operador [].
    Sintaxis: $array[&lt;clave&gt;]
  </p>

  <h3>Array escalar</h3>
<?php
  // Un array se define de 2 formas:
  // 1ª: con la función Array();
  $notas = Array(4, 9, 7.5, 6, 2.5);
  // 2ª: con un literal de array
  $numeros = [8, 4, 2, 9, 5.5];

  // Si solo se indican los elementos del array, cada uno tiene su clave
  // según el orden en el que aparecen, comenzando por 0.
  // En el array escalar, la clave también se conoce como índice.
  echo "<p>La primera nota {$notas[0]}<br>";
  echo "La segunda nota {$notas[1]}<br>";
  echo "La cuarta nota {$notas[3]}</p>";

  // No es obligatorio que PHP asigne índices a los elmentos del array
  // Podemos asignárselo nosotros.

  $notas = Array(2 => 8.5, 4 => 4.75, 8 => 3.5);
  echo "<p>Componente 0 -> {$notas[0]}<br>";
  echo "<p>Componente 1 -> {$notas[1]}<br>";
  echo "<p>Componente 2 -> {$notas[2]}<br>";
  echo "<p>Componente 3 -> {$notas[3]}<br>";
  echo "<p>Componente 4 -> {$notas[4]}<br>";
  echo "<p>Componente 5 -> {$notas[5]}<br>";
  echo "<p>Componente 6 -> {$notas[6]}<br>";
  echo "<p>Componente 7 -> {$notas[7]}<br>";
  echo "<p>Componente 8 -> {$notas[8]}<br>";

  // Puedo asignar un lugar a un componente y 
  // que los siguientes sean asignados automáticamente.
  $notas = [3 => 5, 6.5, 8, 7 => 9, 5];
  echo "<p>Componente 0 -> {$notas[0]}<br>";
  echo "Componente 1 -> {$notas[1]}<br>";
  echo "Componente 2 -> {$notas[2]}<br>";
  echo "Componente 3 -> {$notas[3]}<br>";
  echo "Componente 4 -> {$notas[4]}<br>";
  echo "Componente 5 -> {$notas[5]}<br>";
  echo "Componente 6 -> {$notas[6]}<br>";
  echo "Componente 7 -> {$notas[7]}<br>";
  echo "Componente 8 -> {$notas[8]}</p>";

  // Borrado de elementos.
  // La función unset($variable)
  echo "<p>";
  unset($notas[4]);
  if( isset($notas[4]) ) {
    echo "El elemento 4 es {$notas[4]}<br>";
  }
  else {
    echo "El elemento 4 no está definido<br>";
  }

  // Modificar el valor de un componentes.
  $notas[5] = rand(0,10);
  echo "El elemento 5 es {$notas[5]}<br>";

  // Añadir un elemento al final del array
  $notas[] = 3.5;
  echo "El último elemento añadido es {$notas[9]}</p>";
?>
<h3>Arrays asociativos</h3>
<p>La clave del array es una cadena de caracteres</p>
<?php
$coches['1234ABC'] = "Seat León";
$coches['4321CCB'] = "Ford Focus";
echo "<p>Mi coche es {$coches['1234ABC']}<br>";
echo "El coche del vecino es " . $coches['4321CCB'] . "<br>";

?>
<h3>Array mixtos</h3>
<p>Las claves son índices numéricos y cadenas de caracteres indistintamente</p>
<?php
$alumno['nombre'] = "Juan Gómez";
$alumno[0] = 4;
$alumno[1] = 6;
$alumno[2] = 5;
$alumno['media'] = 5;

echo "<p>El alumno {$alumno['nombre']} tiene las siguientes notas: {$alumno[0]}, {$alumno[1]} y {$alumno[2]}";
echo ". Su nota media es {$alumno['media']}</p>";

$alumno = ['nombre' => "Manuel Martínez", 0 => 3, 8, 5, 4, 'media' => 5];

echo "<p>El alumno {$alumno['nombre']} tiene las siguientes notas: {$alumno[0]}, {$alumno[1]} y {$alumno[2]}";
echo ". Su nota media es {$alumno['media']}</p>";
?>
<h3>Arrays bidimensionales</h3>
<p>Arrays de dos dimensiones es cuando cada elemento de un array es otro array. En este caso 
  para acceder a elementos individuales hay que utilizar 2 claves.
</p>
<?php
$notas = Array(
  Array(3.5, 6, 8, 9.5, 3), 
  Array(2, 5.5, 6, 2, 10), 
  Array(4.5, 3, 2.5, 7, 8), 
  Array(7, 1, 0, 1.5, 3.5)
);

echo "<p>El elemento en la fila 2 columna 3 -> {$notas[1][2]}<br>";

// Añadir un elemento con valor 8 al final del array en el índice 2
$notas[2][] = 7.5;
echo "El último elemento de notas en la 3ª fila -> {$notas[2][5]}<br>";

// Se añade una fila más, con solo un elemento.
$notas[][] = 5.5;
echo "En la última fila, el primer elemento -> {$notas[4][0]}<br>";

// Array bidimensional asociativo.
$coches = [
  '1234ABC' => ['marca' => "Seat", 'modelo' => "Ibiza", 'motor' => "Diesel", 'pvp' => 18000],
  '4321CCB' => ['marca' => "Ford", 'modelo' => "Focus", 'motor' => "Gasolina", 'pvp' => 22000] 
];

echo "El primer coche es {$coches['1234ABC']['marca']}";
echo " {$coches['1234ABC']['modelo']}<br>";
?>
<h3>Arrays multidimensionales</h3>
<?php
$notas = [
  [ [3, 4, 5, 6], 
    [2, 8, 9, 3] 
  ],
  [ [1, 9, 8, 5], 
    [2, 8, 4, 5] 
  ],
];

echo "Accedo al elemento 1 - 1 - 2: {$notas[1][1][2]}<br>";

$notas = [
  'juan' => [
    't1'  => ['dwes' => 4, 'dwec' => 3.5, 'daw' => 5.5, 'diw' => 7],
    't2'  => ['dwes' => 4.5, 'dwec' => 4.5, 'daw' => 5, 'diw' => 7.5],
    't3'  => ['dwes' => 5, 'dwec' => 5, 'daw' => 5.75, 'diw' => 8.5],
  ],
  'maria' => [
    't1'  => ['dwes' => 4, 'dwec' => 3.5, 'daw' => 5.5, 'diw' => 7],
    't2'  => ['dwes' => 4.5, 'dwec' => 4.5, 'daw' => 5, 'diw' => 7.5],
    't3'  => ['dwes' => 5, 'dwec' => 5, 'daw' => 5.75, 'diw' => 8.5],
  ]
];

echo "La nota de maria en el segundo trimestre en dwec: {$notas['maria']['t2']['dwec']}<br>";
$alumno = "juan";
$modulo = "dwes";
$trimestre = "t3";

echo "La nota de $alumno en el módulo $modulo y el trimestre $trimestre: {$notas[$alumno][$trimestre][$modulo]}<br>";
?>
<h3>Recorrido de un array</h3>
<?php
// Con un bucle tradicional
// Solo con arrays escalares
$numeros = [6, 19, 12, 7, 11, 9, 3];
echo "<p>";

for($i=0; $i < count($numeros); $i++) {
  echo "Elemento $i es {$numeros[$i]}<br>";
}
echo "</p>";
/*
$i = 0;
while( $i < count($numeros) ) {
  echo "Elemento {$numeros[$i]}<br>";
  $i++;
}
*/

// Para cualquier tipo de array (escalar o asociativo)
// Bucle foreach
/* Sintaxis:
    foreach( $array as [$clave =>] $valor ) {
      sentencias
    }
*/
echo "<p>Array números con foreach:<br>";
foreach( $numeros as $numero ) {
  echo "Elemento es $numero<br>";
}
echo "</p>";

// Con Arrays mixto
$alumno = [];   // $alumno = Array();
$alumno['nombre'] = "Juan";
$alumno['apellidos'] = 'Gómez González';
$alumno[0] = 4;
$alumno[1] = 6;
$alumno[2] = 7;
$alumno['media'] = 6;
echo "<p>";
foreach($alumno as $clave => $valor) {
  echo "Elemento con clave $clave y valor $valor<br>";
}
echo "</p>";

// Array asociativo
$componentes['cpu'] = "i7 Ultra 13th";
$componentes['mt'] = "Asus H81M2";
$componentes['ram'] = "Kingstone DDR4 3200Mhz 16GB";
$componentes['sdd'] = "Samsung EVO 950 1 TB Nvme m.2";
$componentes['caja'] = "Caja con fuente 700w";
$componentes['monitor'] = "Monitor UHD 4K 23\"";
$componentes['teclado'] = "Teclado 105 inalámbrico retroiluminado";
$componentes['raton'] = "Ratón 3 botones inalámbrico";

echo "<p>Los componentes de mi PC:<br>";
foreach($componentes as $componente) {
  echo "Un componente: $componente<br>";
}
echo "</p>";

echo "<p>Los componentes y sus tipos de mi PC:<br>";
foreach($componentes as $tipo => $componente) {
  echo "Componente: $tipo -> $componente<br>";
}
echo "</p>";

// Arrays multidimensionales

$misNotas = Array(
  Array(3.5, 6, 8, 9.5, 3), 
  Array(2, 5.5, 6, 2, 10), 
  Array(4.5, 3, 2.5, 7, 8), 
  Array(7, 1, 0, 1.5, 3.5)
);

// Con for tradicional anidado
for( $i = 0; $i < count($misNotas); $i++ ) {
  echo "Fila: $i: ";
  for($j = 0; $j < count($misNotas[$i]); $j++) {
    echo " - {$misNotas[$i][$j]}";
  }
  echo "<br>";
}
echo "</p>";

// Con arrays mixtos o asociativos, foreach anidados.
echo "<p>";
foreach($coches as $matricula => $coche ) {
  echo "Vehículo: $matricula<br>";
  foreach( $coche as $caracteristica => $valor ) {
    echo "$caracteristica: $valor<br>";
  }
}
echo "</p>";
echo "<p>";

foreach( $notas as $alumno => $notas_trimestres ) {
  echo "Alumno: $alumno<br>";
  foreach( $notas_trimestres as $trimestre => $notas_modulos) {
    echo "Trimestre: $trimestre<br>";
    foreach( $notas_modulos as $modulo => $nota) {
      echo "$modulo: $nota - ";
    }
    echo "<br>";
  }
  echo "------------------<br>";
}
?>
<h3>Funciones de arrays</h3>
<?php
echo "<p>";
if( array_key_exists("1234ABC", $coches) ) {
  echo "Existe el coche con mátricula 1234ABC y es:";
  $coche = $coches['1234ABC'];
  foreach( $coche as $caracteristica => $valor ) {
    echo "$caracteristica: $valor";
  }
}
else {
  echo "Error. El coche con mátricula 1234ABC no existe";
}

echo "</p>";
?>
</body>
</html>