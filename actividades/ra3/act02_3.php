<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Búsqueda de libros", ["/estilos/general.css", "/estilos/tabla.css", "/estilos/formulario.css"]);

$libros = [
  "123-4-56789-012-3" => ["kf", "Los pilares de la tierra", "nh"],
  "987-6-54321-098-7" => ["kf", "La caída de los gigantes", "nh"],
  "345-1-91827-019-4" => ["mh", "La guerra de Churchill", "bi" ],
  "908-2-10928-374-5" => ["ia", "Fundación", "fa" ],
  "657-4-39856-543-3" => ["ia", "Yo, robot", "fa" ],
  "576-4-23442-998-5" => ["cs", "Cosmos", "dc"]
];

$autores = [
  "kf" => "Ken Follet", 
  "mh" => "Max Hastings", 
  "ia" => "Isaac Asimov", 
  "cs" => "Carl Sagan", 
  "sj" => "Steve Jacobson", 
  "gm" => "George R.R. Martin"
];

$generos = [
  "nh" => "Novela histórica",
  "dc" => "Divulgación científica",
  "bi" => "Biografía",
  "fa" => "Fantasía"
];

echo "<h2>Búsqueda de libros</h2>";

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  // Sanear y validar

  // ISBN: formato  ###-#-#####-###-#
  $expRegIsbn = "#^[0-9]{3}-[0-9]-[0-9]{5}-[0-9]{3}-[0-9]$#";
  $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_SPECIAL_CHARS);
  if( !preg_match($expRegIsbn, $isbn) ) {
    $isbn = false;
  }

  $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);

  $autoresRecibidos = isset($_POST['autores']) ? filter_input(INPUT_POST, 'autores', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) : [];
  $autoresRecibidos = array_intersect($autoresRecibidos, array_keys($autores));

  $generosRecibidos = isset($_POST['generos']) ? filter_input(INPUT_POST, 'generos', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) : [];
  $generosRecibidos = array_intersect($generosRecibidos, array_keys($generos));

  // Búsqueda
  $encontrados = [];

  foreach($libros as $is => $libro) {  
    $datoEncontrado[0] = $isbn ? $is === $isbn : true;
    $datoEncontrado[1] = $titulo ? $libro[1] === $titulo : true;
    $datoEncontrado[2] = empty($autoresRecibidos) ? true : in_array($libro[0], $autoresRecibidos);
    $datoEncontrado[3] = empty($generosRecibidos) ? true : in_array($libro[2], $generosRecibidos);

    if( count(array_filter( $datoEncontrado, fn($dato) => $dato)) === count($libro) + 1 ) {
      $encontrados[$is] = $libro;
    }
  }

  if( isset($encontrados) && !empty($encontrados) ) {
    echo <<<TABLA
    <h3>Libros encontrados</h3>
    <table width="100%">
      <thead>
        <tr>
          <th>Isbn</th>
          <th>Autor</th>
          <th>Título</th>
          <th>Género</th>
        </tr>
      </thead>
      <tbody>
    TABLA;
    foreach($encontrados as $is => $libro) {
      echo "<tr>";
      echo "<td>{$is}</td>";
      echo "<td>{$autores[$libro[0]]}</td>";
      echo "<td>{$libro[1]}</td>";
      echo "<td>{$generos[$libro[2]]}</td>";
      echo "</tr>";
    }
    echo <<<FIN_TABLA
      </tbody>
    </table>
    FIN_TABLA;
  }
  else {
    echo "<h3>No se ha encontrado ningún libro</h3>";
  }
}

?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Criterio de búsqueda</legend>
    <label for="isbn">ISBN</label>
    <input type="text" name="isbn" id="isbn" size="15"/>

    <label for="titulo">Título</label>
    <input type="text" name="titulo" id="titulo" size="40"/>

    <label for="autores">Autor</label>
    <select name="autores[]" id="autores" multiple size="6">
<?php
    foreach( $autores as $clave => $autor) {
      echo "<option value='$clave'>$autor</option>";
    }
?>
    </select>

    <label for="generos">Género</label>
    <select name="generos[]" id="generos" multiple size="4">
<?php
    foreach($generos as $clave => $genero) {
      echo "<option value='$clave'>$genero</option>";
    }
?>
    </select>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Buscar"/>
</form>
<?php

// Simulación pago fraccionado del ejercicio 4
// Supongamo 2 años y un precio de 30000€
$precioCoche=30000;
$cuotaInicial = $cuotaFinal = $precioCoche * 0.25; // 25% Cuota inicial y final
$resto = $precioCoche * 0.5;  // 50% se fracciona en mensualidades
$plazo = 24;
$mensualidad = $resto / $plazo;  // Lo que se paga cada mes

$hoy = getdate(); // Array con los datos de la fecha de hoy
$mes = $hoy['mon'] + 1; // Se empieza a pagar el 1 del mes siguiente
$ayo = $hoy['year'];  

for( $mesInicio = 1; $mesInicio <= $plazo; $mesInicio++) {
  echo "Pago del mes 01/$mes/$ayo -> {$mensualidad}€<br>";
  if( $mes === 12 ) {
    $mes = 1;
    $ayo++;
  }
  else {
    $mes++;
  }
}


finHtml();
?>