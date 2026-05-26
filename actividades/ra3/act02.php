<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Búsqueda de libros", ["/estilos/general.css", "/estilos/tabla.css", "/estilos/formulario.css"]);

$libros = [
  [ "123-4-56789-012-3", "Ken Follet", "Los pilares de la tierra", "Novela histórica"],
  [ "987-6-54321-098-7", "Ken Follet", "La caída de los gigantes", "Novela histórica"],
  [ "345-1-91827-019-4", "Max Hastings", "La guerra de Churchill", "Biografía" ],
  [ "908-2-10928-374-5", "Isaac Asimov", "Fundación", "Fantasía" ],
  [ "657-4-39856-543-3", "Isaac Asimov", "Yo, robot", "Fantasía" ],
  [ "576-4-23442-998-5", "Carl Sagan", "Cosmos", "Divulgación científica"]
];

$autores = [
  "Ken Follet", 
  "Max Hastings", 
  "Isaac Asimov", 
  "Carl Sagan", 
  "Steve Jacobson", 
  "George R.R. Martin"
];

$generos = [
  "Novela histórica",
  "Divulgación científica",
  "Biografía",
  "Fantasía"
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
  $autoresRecibidos = array_intersect($autoresRecibidos, $autores);

  $generosRecibidos = isset($_POST['generos']) ? filter_input(INPUT_POST, 'generos', FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) : [];
  $generosRecibidos = array_intersect($generosRecibidos, $generos);

  // Búsqueda
  $encontrados = [];

  foreach($libros as $libro) {  
    $datoEncontrado[0] = $isbn ? $libro[0] === $isbn : true;
    $datoEncontrado[1] = $titulo ? $libro[2] === $titulo : true;
    $datoEncontrado[2] = empty($autoresRecibidos) ? true : in_array($libro[1], $autoresRecibidos);
    $datoEncontrado[3] = empty($generosRecibidos) ? true : in_array($libro[3], $generosRecibidos);

    if( count(array_filter( $datoEncontrado, fn($dato) => $dato)) === count($libro) ) {
      $encontrados[] = $libro;
    }
  }

  if( isset($encontrados) && !empty($encontrados) ) {
    echo <<<TABLA
    <h3>Libros encontrados</h3>
    <table width="100%">
      <thead>
        <tr>
          <th>Isbn</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Género</th>
        </tr>
      </thead>
      <tbody>
    TABLA;
    foreach($encontrados as $libro) {
      echo "<tr>";
      echo "<td>{$libro[0]}</td>";
      echo "<td>{$libro[1]}</td>";
      echo "<td>{$libro[2]}</td>";
      echo "<td>{$libro[3]}</td>";
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
    foreach( $autores as $autor) {
      echo "<option value='$autor'>$autor</option>";
    }
?>
    </select>

    <label for="generos">Género</label>
    <select name="generos[]" id="generos" multiple size="4">
<?php
    foreach($generos as $genero) {
      echo "<option value='$genero'>$genero</option>";
    }
?>
    </select>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Buscar"/>
</form>
<?php
finHtml();
?>