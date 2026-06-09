<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

$temas = [ 'pro' => "Programación",
           'coc' => "Cocina",
           'dep' => "Deporte"
        ];

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  if( !isset($_COOKIE['JWT'])) {
    header("Location: index.php");
  }

  $jwt = $_COOKIE['JWT'];
  $payload = verificarJWT($jwt);
  if( !$payload ) {
    header("Location: index.php");
  }

  $tema = filter_input(INPUT_POST, 'tema', FILTER_SANITIZE_SPECIAL_CHARS);
  $comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_SPECIAL_CHARS);

  if( !array_key_exists($tema, $temas) ) {
    echo "<h3>Error. El tema no es válido</h3>";
    echo "<p><a href='s2.php'>Volver a introducir un comentario</a></p>";
    finHtml();
    exit();
  }

  $_SESSION['comentarios'][] = ['tema' => $tema, 'fecha' => date("d/m/Y G:i:s"), 'comentario' => $comentario];

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


  echo "<p><a href='s4.php'>Ir a la pantalla final</a></p>";
  echo "<p><a href='s2.php'>Introducir otro comentario</a></p>";

  finHtml();
}


?>