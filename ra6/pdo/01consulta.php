<?php
/* 
  Parámetros de conexión a la BD
  ------------------------------
  Servidor: cpd.informatica.iesgrancapitan.org
  Puerto: 9990
  Usuario: usuario (cada uno el suyo)
  Clave: usuario
  BD: tiendaol
  Juego de caracteres: utf8mb4

  PDO
  ---

  Para acceder a una base de datos usando la API PDO:

  1º Crear una conexión con la BD. Crear una instancia de la clase PDO.
  2º Crear una consulta (con o sin parámetros). Crear una instancia de la clase
     PDOStatement
  3º Vincular los valores de los parámetros de la sentencia anterior, si los tiene.
  4º Ejecutar la consulta y recibir el resultado
  5º Acceder al conjunto de filas del resultado


*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

// Cadena DSN (Data Source Name). Esta cadena indica el SGBDR que vamos 
// a utilizar. Además, se incluye el usuario, la clave y el esquema con
// el que va a conectar, además de otros parámetros.
$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";   // Cada uno pone el suyo
$clave = "usuario";     // Todos tenemos la misma
$opciones = [
  PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES    => false
];

inicioHtml("Consultas a la BD", ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);
  
  // Creo una consulta con el método query()
  $stmt = $pdo->query("SELECT nif, nombre, apellidos, email FROM cliente");
  $stmt->execute();

  // En este punto, la sentencia se ha ejecutado
  echo<<<TABLA
  <h3>Resultado de la consulta</h3>
  <p>Número de filas: {$stmt->rowCount()}</p>
  <table>
    <thead>
      <tr><th>Nif</th><th>Nombre</th><th>Email</th></tr>
    </thead>
    <tbody>
  TABLA;
  // Acceso al resultado de una consulta SELECT 
  // 1ª forma. Se accede al resultado por filas individuales
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

  //2ª forma. Se accede a todas las filas del resultado de golpe
  $filas = $stmt->fetchAll();
  array_walk($filas, function(array $fila) {
    echo <<<FILA
    <tr>
      <td>{$fila['nif']}</td>
      <td>{$fila['nombre']} {$fila['apellidos']}</td>
      <td>{$fila['email']}</td>
    </tr>
    FILA;
  });

  echo <<<TABLA
    </tbody>
  </table>
  TABLA;

  finHtml();
}
catch(PDOException $pdoe) {
  mostrarError($pdoe);
}
finally {
  $pdo = null;
  $stmt = null;
}
?>