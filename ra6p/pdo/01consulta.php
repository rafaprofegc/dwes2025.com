<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

/*
PDO

  Para acceder a una BD usando PDO tenemos que hacer lo siguiente:
  1º Crear una conexión a la BD: instanciar un objeto de la clase PDO.
  2º Crear una consulta SQL (con o sin parámetros): objeto consulta
  3º Si la consulta tiene parámetros, asignarle valores: vincular valores
     (variables o literales) a los parámetros de la consulta.
  4º Ejecutar la consulta: obtenemos un objeto resultado.
  5º Acceder a las filas del resultado: usando un método del objeto anterior
*/

use ra5\util\Html;

Html::inicioHtml("Biblioteca PDO", ["/estilos/general.css", "/estilos/tabla.css"]);

$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";
$clave = "usuario";
$opciones = [
  PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES    => false,
  PDO::ATTR_CASE                => PDO::CASE_LOWER
];
try {
  // 1º Abrir conexión con la BD
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);

  // 2º Crear una consulta
  $sentenciaSQL = "SELECT niff, nombre, apellidos, email ";
  $sentenciaSQL.= "FROM cliente";

  // 3º Crear una consulta a la BD
  $stmt = $pdo->query($sentenciaSQL);   // Devuelve objeto PDOStatement

  // 4º Ejecutar la consulta
  if( $stmt->execute() ) {
    echo "<h3>Resultados de la consulta</h3>";
    echo "<p>Número de filas " . $stmt->rowCount() . "</p>";

    echo <<<TABLA
    <table>
      <thead>
        <tr><th>Nif</th><th>Nombre</th><th>Email</th></tr>
      </thead>
      <tbody>
    TABLA;

    // 5º Acceder a los resultados de la consulta
    // 1ª forma: fila a fila
    /*
    while( $fila = $stmt->fetch() ) {
      echo <<<FILA
      <tr>
        <td>{$fila['nif']}</td>
        <td>{$fila['nombre']} {$fila['apellidos']}</td>
        <td>{$fila['email']}</td>
      </tr>
      FILA;
    }
    */
    // 2ª forma: obtengo todas las filas de golpe
    $filas = $stmt->fetchAll();
    foreach($filas as $cliente) {
      echo <<<FILA
      <tr>
        <td>{$cliente['nif']}</td>
        <td>{$cliente['nombre']} {$cliente['apellidos']}</td>
        <td>{$cliente['email']}</td>
      </tr>
      FILA;
    }
    
    echo <<<TABLA
    </tbody>
    </table>
    TABLA;
  }

}
catch( PDOException $pdoe ) {
  echo "<h3>Error de la aplicación</h3>";
  echo "<p>Código de error: {$pdoe->getCode()}<br>";
  echo "Mensaje de error: {$pdoe->getMessage()}<br>";
  echo "Script: {$pdoe->getFile()}<br>";
  echo "Línea: {$pdoe->getLine()}</p>";
}
finally {
  $pdo = null;
}
Html::finHtml();

?>