<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
function Error($codigoError) {
  $mensajesError = [
    1 => 'Los datos personales no son válidos',
    2 => 'Los datos para la cesta no son válidos'
  ];

  ob_clean();
  echo "<h3>Error de la aplicación</h3>";
  echo "<p>Código de error: $codigoError<br>";
  echo "Mensaje de error: {$mensajesError[$codigoError]}</p>";

  finHtml();

}

function comprobarSesion(): void {
  if( !isset($_SESSION['email'], $_SESSION['nombre']) ) {
    header("Location: 01inicio_sesion.php");
  }
}

function verVariablesSesion(): void {

  echo "<h3>Datos de la cesta</h3>";
  echo "<p>Nombre: {$_SESSION['nombre']}<br>";
  echo "Email: {$_SESSION['email']}<br>";
  $productos = $_SESSION['productos'];
  if( $productos ) {
    echo <<<TABLA
      <table>
        <thead>
          <tr>
            <th>Dulce de Navidad</th>
            <th>Cantidad</th>
          </tr>
        </thead>
        <tbody>
    TABLA;

    foreach($productos as $producto) {
      echo "<tr><td>{$producto['dulce']}</td><td>{$producto['cantidad']}</td></tr>";
    }

    /*
    array_walk($productos, function($producto) {
      echo "<tr><td>{$producto['dulce']}</td><td>{$producto['cantidad']}</td></tr>";
    });
    */
    echo <<<TABLA
      </tbody>
    </table>
    <hr>
    TABLA;
  }
}
?>