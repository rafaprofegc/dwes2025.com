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

inicioHtml("Actividad 8", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<h3>Usuario: {$payload['email']} - {$payload['nombre']}</h3>";
?>
<form method="POST" action="s3.php">
  <fieldset>
    <legend>Introducir comentario</legend>
      <label for="tema">Tema</label>
      <select name="tema" id="tema" size="1">
<?php
      foreach($temas as $tema => $titulo) {
        echo "<option value='$tema'>$titulo</option>";
      }
?>
      </select>

      <label for="comentario">Comentario</label>
      <textarea name="comentario" id="comentario" rows="4" cols="40"></textarea>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Añadir comentario">
</form>
<?php
finHtml();
?>