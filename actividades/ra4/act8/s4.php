<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

$temas = [ 'pro' => "Programación",
           'coc' => "Cocina",
           'dep' => "Deporte"
        ];

if( !isset($_COOKIE['JWT'])) {
  header("Location: index.php");
}

$jwt = $_COOKIE['JWT'];
$payload = verificarJWT($jwt);
if( !$payload ) {
  header("Location: index.php");
}

inicioHtml("Actividad 8", ["/estilos/general.css", "/estilos/tabla.css"]);
echo "<h3>Usuario: {$payload['email']} - {$payload['nombre']}</h3>";
echo "<h4>Comentarios del usuario</h4>";
echo <<<TABLA
  <table>
    <thead>
      <tr>
        <th>Tema</th>
        <th>Fecha</th>
        <th>Comentario</th>
      </tr>
    </thead>
    <tbody>
TABLA;
array_walk( $_SESSION['comentarios'], function($c) use($temas){
  echo <<<FILA
    <tr>
      <td>{$temas[$c['tema']]}</td>
      <td>{$c['fecha']}</td>
      <td>{$c['comentario']}</td>
    </tr>
  FILA;   
});

echo <<<TABLA
  </tbody>
</table>
TABLA;

// Cierre de sesión
$sesion = session_name();
$p = session_get_cookie_params();
setcookie($sesion, "", time() - 100, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
session_destroy();
unset($_SESSION);

setcookie("JWT", "", time() - 100);

echo "<p><a href='index.php'>Empezar de nuevo</a></p>";

finHtml();
?>