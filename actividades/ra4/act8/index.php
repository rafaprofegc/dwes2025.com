<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
inicioHtml("Actividad 8", ["/estilos/general.css", "/estilos/formulario.css"]);
?>
<h1>Blog</h1>
<form method="POST" action="s1.php">
  <fieldset>
    <legend>Autenticación de usuario</legend>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="20" required>

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size="20" required>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
</form>
<?php
finHtml();
?>