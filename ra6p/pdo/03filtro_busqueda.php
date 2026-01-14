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

  // Construyo la cláusula WHERE
}

// Presento el formulario sticky
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <label for="referencia">Referencia</label>
  <input type="text" name="referencia" id="referencia" size="10">
  
  <label for="descripcion">Descripcion</label>
  <input type="text" name="descripcion" id="descripcion" size="30">

  <label for="pvp">PVP</label>
  <input type="text" name="pvp" id="pvp" size="5">

  <label for="categoria">Categoria</label>
  
</form>
<?php
// Ejecutar la consulta parametrizada

// Presentar los resultados


?>
