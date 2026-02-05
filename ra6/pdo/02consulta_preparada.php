<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

$dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
$usuario = "usuario";
$clave = "usuario";
$opciones = [
  PDO::ATTR_ERRMODE                   => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE        => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES          => false
];

inicioHtml("Consultas preparadas", ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  $pdo = new PDO($dsn, $usuario, $clave, $opciones);

  // Sentencia con parámetros
  // 1ª forma de especificar los parámetros: utilizar ? en cada uno
  $sql = "SELECT referencia, descripcion, pvp, categoria, und_vendidas ";
  $sql.= "FROM articulo ";
  $sql.= "WHERE pvp > ? AND categoria = ? AND und_vendidas > ?";

  // Crear la sentencia preparada. Método prepare()
  $stmt = $pdo->prepare($sql);

  /* Asignar los valores a los parámetros.
  1ª forma: método bindParam(<parametro>,<variable>)
  - Asigna el valor de una variable al parámetro cuando se ejecute la sentencia.
  - Obligatoriamente, tiene que usarse una variable.
  - Se invoca tantas veces como parámetros haya, una por parámetro.
  - Si el parámetro biene en la forma ?, se indica el número de orden y la variable
  - NO hay que preocuparse por el tipo de datos, PDO se encarga de convertirlos.
  */

  $pvp = 7.5;
  $categoria = "TV";
  $und_vendidas = 2;
  // Si los parámetros on ?
  $stmt->bindParam(1, $pvp);
  $stmt->bindParam(2, $categoria);
  $stmt->bindParam(3, $und_vendidas);

  // A partir de aquí puedo cambiar el valor de $pvp, $categoria y $und_vendidas
  // Al ejecutar la sentencia, el valor que tengan en ese momento se usará
  // para los parámetros.
  $stmt->execute();
  echo <<<ARTICULOS
    <h3>Listado de artículos</h3>
    <p>Número de filas: {$stmt->rowCount()}</p>
    <table>
      <thead>
        <tr><th>Referencia</th><th>Descripción</th><th>PVP</th><th>Categoria</th><th>Und. Vend</th></tr>
      </thead>
      <tbody>
  ARTICULOS;

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
  finHtml();


}
catch (PDOException $pdoe) {
  mostrarError($pdoe);
}
finally {
  $pdo = null;
  $stmt = null;
}
?>