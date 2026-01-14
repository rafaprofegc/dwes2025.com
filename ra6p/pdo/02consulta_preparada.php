<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\util\Util;

$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";
$clave = "usuario";
$opciones = [
  PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES    => false
];

Html::inicioHtml("Consultas parametrizadas con PDO", 
  ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  // 1º Crear la conexión con la BD
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);

  // 2º Monto la sentencia con los parámetros
  // 1ª Forma: utilizamos ? para indicar los parámetros
  $sentenciaSQL = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
  $sentenciaSQL.= "FROM articulo ";
  //$sentenciaSQL.= "WHERE pvp > ? AND categoria = ? AND und_vendidas > ?";

  // 2ª Forma: utilizamos nombre en cada parámetro
  $sentenciaSQL.= "WHERE pvp > :pvp AND categoria = :categoria AND und_vendidas > :uv";

  // 3º Crear la sentencia preparada (objeto de la clase PDOStatement)
  $stmt = $pdo->prepare($sentenciaSQL);

  // 4º Asignar los valores de los parámetros
  $pvp = 7.5;
  $categoria = "TV";
  $undVendidas = 2;

  // 1ª forma: Método bindParam()
  // - Asigna el valor de una variable cuando se ejecute la sentencia
  // - Obligatoriamente, el valor del parámetro se indica con una variable
  // - Se invoca tantas veces como parámetros haya, una por cada parámetro
  // - Si el parámetro tiene en la forma ?, se indica el ńumero de orden y
  //   la variable.
  // $stmt->bindParam(1, $pvp);
  // $stmt->bindParam(2, $categoria);
  // $stmt->bindParam(3, $undVendidas);

  //$stmt->bindParam(':pvp', $pvp);         // Puedo omitir : en el nombre del parámetro
  //$stmt->bindParam(':categoria', $categoria);
  //$stmt->bindParam(':uv', $undVendidas);

  // 2ª forma: Método bindValue()
  // - Asigna el valor de una expresión cuando se ejecuta el método.
  // - Puede ser una expresión (del mismo tipo que la columna de la tabla en la BD)
  // - Se invoca tantas veces como parámetros haya, una por parámetro.
  // - Si el parámetro viene en la forma ?, se indica el número de orden y la expresión.
  //$stmt->bindValue(1, $pvp - 5.25);
  //$stmt->bindValue(2, "CONF");
  //$stmt->bindValue(3, $undVendidas * 2);
  $stmt->bindValue(':pvp', $pvp - 5.25);    // Puedo omitir : en el nombre del parámetro  
  $stmt->bindValue(':categoria', "CONF");
  $stmt->bindValue(':uv', $undVendidas * 2);

  // 3ª forma de asignar valores a los parámetros
  // Creo un array asociativo con los parámetros
  //  ':nombreParametro' => <expresión>
  $parametros = [ 
    'pvp'       => 2.25,
    'categoria' => "CONF",
    'uv'        => $undVendidas * 2
  ];

  //$pvp = 1000;
  //if( $stmt->execute() ) {
  if( $stmt->execute($parametros) ) {
    echo "<h3>Listado de artículos</h3>";
    echo "<p>Número de filas: " . $stmt->rowCount() . "</p>";
    echo <<<TABLA
    <table>
      <thead>
        <tr><th>Referencia</th><th>Descripción</th><th>PVP</th><th>Categoria</th><th>UndVend</th></tr>
      </thead>
      <tbody>
    TABLA;

    while ( $fila = $stmt->fetch() ) {
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
}
catch (PDOException $pdoe) {
  Util::MuestraExcepcion($pdoe);
}
finally {
  $pdo = null;
}

