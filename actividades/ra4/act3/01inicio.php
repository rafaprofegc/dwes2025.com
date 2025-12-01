<?php
/*
Pantalla de autenticación → Se presenta un formulario con email/clave 
para que el usuario se autentifique. Solo los usuarios autenticados 
pueden pedir pizzas.
*/
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
inicioHtml("Pizzas a domicilio", ["/estilos/general.css", "/estilos/formulario.css"]);

echo "<header>Bienvenido Pizza en moto</header>";
if( isset($_SESSION['errores']) ) {
  echo "<h3>Errores encontrados</h3>";
  echo "<ul>";
  array_walk($_SESSION['errores'], function($e) {
    echo "<li>$e</li>";
  });
  echo "</ul>";
  unset($_SESSION['errores']);
}
?>

<form method="POST" action="02autenticar.php">
  <fieldset>
    <legend>Autenticación de usuario</legend>
    <label for="email">Email</label>
    <input type="text" name="email" id="email" size="30">

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size= "10">
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>
<?php
finHtml();
?>