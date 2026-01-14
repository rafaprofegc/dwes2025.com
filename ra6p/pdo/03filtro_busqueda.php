<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\util\Util;

$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";
$password = "usuario";
$opciones = [
  PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES    => false
];

try {
  $pdo = new PDO($dsn, $usuario, $password, $opciones);

  // Leemos las categorías de la BD
  // para rellenar la lista categorias
  // en el formulario de búsqueda
  $sentenciaSQL = "SELECT id_categoria, descripcion FROM categoria";
  $stmt = $pdo->prepare($sentenciaSQL);
  $categorias = [];
  if( $stmt->execute() ) {
    $categorias = $stmt->fetchAll();
  }
}
catch( PDOException $pdoe) {
  Util::MuestraExcepcion($pdoe);
}

if( $_SERVER['REQUEST_METHOD'] == "POST" ) {
  // Validar y sanear los datos
  $filtroValidacion = [
    'referencia'      => FILTER_SANITIZE_SPECIAL_CHARS,
    'descripcion'     => FILTER_SANITIZE_SPECIAL_CHARS,
    'pvp'             => ['filter' => FILTER_VALIDATE_FLOAT,
                          'options' => ['min_range' => 0],
                          'flags'   => FILTER_FLAG_ALLOW_FRACTION],
    'categoria'       => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datosValidados = filter_input_array(INPUT_POST, $filtroValidacion);
  $datosValidados = array_filter($datosValidados);

  // Construyo la cláusula WHERE
  $clausulaWhere = "";
  foreach( $datosValidados as $campo => $valor ) {

    // Si es cadena se usa el operador LIKE
    // Si es otro tipo, se usa el operador =

    if( is_float($valor) || is_int($valor) ) {
      $condiciones[] = "$campo = :$campo";
      $valores[$campo] = $valor;
    }
    else {
      $condiciones[] = "$campo LIKE :$campo";
      $valores[$campo] = "%$valor%";
    }   
  }
  if( isset($condiciones, $valores) ) {
    $clausulaWhere = "WHERE " . implode(" AND ", $condiciones);
  }
}

// Presento el formulario sticky
Html::inicioHtml("Filtrado de tabla", ["/estilos/general.css", "/estilos/tabla.css"]);
?>
<header>Listado de artículos</header>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <label for="referencia">Referencia</label>
  <input type="text" name="referencia" id="referencia" size="10"
  <?= isset($datosValidados['referencia']) ? "value=\"{$datosValidados['referencia']}\"" : "" ?>
  >
  
  <label for="descripcion">Descripcion</label>
  <input type="text" name="descripcion" id="descripcion" size="30"
  <?= isset($datosValidados['descripcion']) ? "value=\"{$datosValidados['descripcion']}\"" : "" ?>
  >

  <label for="pvp">PVP</label>
  <input type="text" name="pvp" id="pvp" size="5"
  <?= isset($datosValidados['pvp']) ? "value=\"{$datosValidados['pvp']}\"" : "" ?>
  >

  <label for="categoria">Categoria</label>
  <select name="categoria" id="categoria">
    <option value="">Elija una categoría</option>
<?php
  foreach($categorias as $categoria) {
    $seleccion = isset($datosValidados['categoria']) && 
                       $datosValidados['categoria'] == $categoria['id_categoria'] ? " selected": "";
    echo <<<OPTION
    <option value="{$categoria['id_categoria']}"$seleccion>{$categoria['descripcion']}</option>
    OPTION;
  }
?>
  </select>
  <input type="submit" id="operacion" name="operacion" value="Filtrar">
</form>
<?php

// Ejecutar la consulta parametrizada
try {
  $sentenciaSQL = "SELECT referencia, descripcion, pvp, categoria ";
  $sentenciaSQL.= "FROM articulo ";
  $sentenciaSQL.= $clausulaWhere ?? "";

  $stmt = $pdo->prepare($sentenciaSQL);

  // Ejemplo de array $valores
  // 'referencia'   => '%CONF0001',
  // 'pvp'          => '5.5',
  // 'descripcion'  => 'magadalenas'

  if( isset($clausulaWhere) ) {
    foreach( $valores as $parametro => $valor ) {
      $stmt->bindValue($parametro, $valor);
    }
  }

  // Asignamos los valores de los parámetros
  // que estarán en la clausula WHERE.

  if( $stmt->execute() ) {
    echo <<<TABLA
    <table>
      <thead>
        <tr><th>Referencia</th><th>Descripción</th><th>PVP</th><th>Categoría</th></tr>
      </thead>
      <tbody>
    TABLA;
    while( $fila = $stmt->fetch() ) {
      echo <<<FILA
      <tr>
        <td>{$fila['referencia']}</td>
        <td>{$fila['descripcion']}</td>
        <td>{$fila['pvp']}€</td>
        <td>{$fila['categoria']}</td>
      </tr>
      FILA;
    }
    echo <<<TABLA
      </tbody>
    </table>
    TABLA;
  }
}
catch(PDOException $pdoe) {
  Util::MuestraExcepcion($pdoe);
}
finally {
  $pdo = null;
}

// Presentar los resultados

Html::finHtml();
?>
