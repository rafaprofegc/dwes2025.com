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

try {
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);
  
}
catch() {

}




?>