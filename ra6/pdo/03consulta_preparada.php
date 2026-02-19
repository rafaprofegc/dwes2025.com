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

  // Leo las categorías que voy a necesitar para la lista desplegable
  $sql = "SELECT id_categoria, descripcion FROM categoria ORDER BY descripcion";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $categorias = $stmt->fetchAll();

}
catch( PDOException ){
  mostrarError($e);
  finHtml();
  exit;
}

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
}

echo "<header>Búsqueda de artículos</header>";

?>
<form method="POST" class="enlinea" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Criterio de búsqueda</legend>
    <label for="referencia">Referencia</label>
    <input type="text" name="referencia" id="referencia" size="15"
    value=<?= isset($datosValidados['referencia']) ? $datosValidados['referencia'] : "" ?>>

    <label for="descripcion">Descripción</label>
    <input type="text" name="descripcion" id="descripcion" size="40"
    value=<?= isset($datosValidados['descripcion']) ? $datosValidados['descripcion'] : "" ?>>

    <label for="pvp">PVP</label>
    <input type="text" name="pvp" id="pvp" size="5"
    value=<?= isset($datosValidados['pvp']) ? $datosValidados['pvp'] : "" ?>>

    <label for="categoria">Categoría</label>
    <select name="categoria" size="1">
<?php
      foreach($categorias as $categoria) {
        $selecciona = $datosValidados['categoria'] === $categoria['id_categoria'] ? " selected" : "";
        echo <<<CATEGORIA
        <option value="{$categoria['id_categoria']}"$selecciona>{$categoria['descripcion']}</option>
        CATEGORIA;
      }
?>
    </select>
    <input type="submit" name="operacion" id="operacion" value="Buscar">
  </fieldset>
  
</form>
<?php
if( $_SERVER['REQUEST_METHOD'] === "POST") {
  try {
    $sql = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
    $sql.= "FROM articulo ";
    $clausulaWHERE = "";
    $columnasLIKE = ['descripcion', 'referencia'];

    // WHERE campo1 = :campo1 AND campo2 = :campo2 ...;

    if( count($datosValidados) > 0 ) {
      // $datosValidados = ['descripcion' => 'KG', 'pvp' => 3];
      $columnas = array_keys($datosValidados);
      // $columnas = ['descripcion', 'pvp];
     
      $expresiones = array_map(fn($columna) =>
        in_array($columna, $columnasLIKE) ? 
          "$columna LIKE :$columna" : "$columna = :$columna", $columnas);

      /*
      $expresiones = array_map( function($c) use($datosValidados) {
        $valor = $datosValidados[$c];
        if( is_numeric($valor) ) {
          return "$c = :$c";
        }
        else {
          return "$c LIKE :$c";
        }
      }, $columnas)
      */
      // $expresiones = ["descripcion = :descripcion","pvp = :pvp"]

      $clausulaWHERE = "WHERE " . implode(" AND ", $expresiones);
      // $clausulaWHERE = WHERE descripcion = :descripcion AND pvp = :pvp
    }
    
    $sql.= $clausulaWHERE;
    $stmt = $pdo->prepare($sql);

    // Asignar los valores de los parámetros
    foreach($datosValidados as $columna => $valor) {
      if( in_array($columna, $columnasLIKE) )
        $stmt->bindValue(":$columna", "%$valor%");
      else
        $stmt->bindValue(":$columna", $valor);
    }
    
    $stmt->execute();

    echo <<<TABLA
    <p>Filas de resultado: {$stmt->rowCount()}</p>
    <table>
      <thead>
        <tr><th>Referencia</th><th>Descripción</th><th>PVP</th><th>Categoría</th><th>Und.Vend.</th></tr>
      </thead>
      <tbody>
    TABLA;

    while( $fila = $stmt->fetch() ) {
      echo <<<FILA
      <tr>
        <td>{$fila['referencia']}</td>
        <td>{$fila['descripcion']}</td>
        <td>{$fila['pvp']}</td>
        <td>{$fila['categoria']}</td>
        <td>{$fila['und_vendidas']}</td>
      </tr>
      FILA;
    }

    echo <<<TABLA
      </tbody>
    </table>
    TABLA;
  }
  catch(PDOException $pdoe) {
    mostrarError($pdoe);
  }
}

finHtml();
?>