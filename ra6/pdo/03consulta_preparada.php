<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Búsqueda de artículos", ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
  $usuario = "usuario";
  $clave="usuario";
  $opciones = [
    PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ];

  $pdo = new PDO($dsn, $usuario, $clave, $opciones);

}
catch( PDOException ){
  mostrarError($e);
  finHtml();
  exit;
}

echo "<header>Búsqueda de artículos</header>";

?>
<form method="POST" class="enlinea" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Criterio de búsqueda</legend>
    <label for="referencia">Referencia</label>
    <input type="text" name="referencia" id="referencia" size="15">

    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" size="40">

    <label for="pvp">PVP</label>
    <input type="text" name="pvp" id="pvp" size="5">

    <label for="categoria">Categoría</label>
    <input type="text" name="categoria" id="categoria" size="10">

    <input type="submit" name="operacion" id="operacion" value="Buscar">
  </fieldset>
  
</form>
<?php
if( $_SERVER['REQUEST_METHOD'] === "POST") {
  // Validar los datos
  $filtro = [ 'referencia' => FILTER_SANITIZE_SPECIAL_CHARS, 
              'descripcion' => FILTER_SANITIZE_SPECIAL_CHARS,
              'pvp' => [
                'filter' => FILTER_VALIDATE_FLOAT,
                'flags' => FILTER_FLAG_ALLOW_FRACTION
                ],
              'categoria' => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datosValidados = filter_input_array(INPUT_POST, $filtro);
  $datosValidados = array_filter($datosValidados, fn($d) => $d != false);

  try {
    $sql = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
    $sql.= "FROM articulo ";
    $clausulaWHERE = "";

    if( count($datosValidados) > 1 ) {
      // $datosValidados = ['descripcion' => 'KG', 'pvp' => 3];
      $columas = array_keys($datosValidados);
      // $columnas = ['descripcion', 'pvp];

      $expresiones = array_map(fn($c) => "$c = :$c", $columnas);
      // $expresiones = ["descripcion = :descripcion","pvp = :pvp"]

      $clausulaWHERE = "WHERE " . implode(" AND ", $expresiones);
      // $clausula WHERe = WHERE descripcion = :descripcion AND pvp = :pvp
    }
    
    $sql.= $clausulaWHERE;
    $stmt = $pdo->prepare($sql);

    

  }
  catch(PDOException $pdoe) {
    mostrarError($e);
  }
}

finHtml();
?>