<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

$usuarios = [
  'pepe@gmail.com'  => ['nombre' => "José Gómez", 'clave' => password_hash("usuario", PASSWORD_DEFAULT)]
];

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  inicioHtml("Actividad 8", ["/estilos/general.css"]);

  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $clave = $_POST['clave'] ?? null;

  if( !$email || !$clave) {
    echo "<h3>Error. Las credenciales de usuario no son válidas</h3>";
    echo "<p><a href='index.php'>Volver a intentarlo</a></p>";
    finHtml();
    exit();
  }

  if( !array_key_exists($email, $usuarios) ) {
    echo "<h3>Error. El usuario no existe</h3>";
    echo "<p><a href='index.php'>Volver a intentarlo</a></p>";
    finHtml();
    exit();
  }

  if( !password_verify($clave, $usuarios[$email]['clave']) ) {
    echo "<h3>Error. La clave no es correcta</h3>";
    echo "<p><a href='index.php'>Volver a intentarlo</a></p>";
    finHtml();
    exit();
  }

  $payload = ['email' => $email, 'nombre' => $usuarios[$email]['nombre']];
  $jwt = generarJWT($payload);
  setcookie("JWT", $jwt, time() + 2 * 60 * 60, "/actividades/ra4/act8");

  $_SESSION['comentarios'] = [];
  echo "<h3>Bienvenido {$usuarios[$email]['nombre']}</h3>";
  echo "<p>Ya puede ir a <a href='s2.php'>la siguiente pantalla</a> para añadir comentarios sobre los temas disponibles</p>";
  finHtml();
  exit();
}
else {
  header("Location: index.php");
};
?>