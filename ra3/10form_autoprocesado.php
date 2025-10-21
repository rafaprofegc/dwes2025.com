<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Páginas autoprocesadas", ["/estilos/general.css", "/estilos/formulario.css"]);

/* Página autoprocesada:
 Consiste en incluir en el mismo script el formulario y el procesamiento del mismo.
 - Si la petición es GET, se presenta el formulario.
 - Si la petición es POST, se procesa el formulario.
 - En el proceso del formulario se puede poner un enlace al formulario de nuevo.
*/

if( $_SERVER['REQUEST_METHOD'] === "GET") {
  // Muestro el formulario ?>
  <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
      <legend>Datos del usuario</legend>
      <label for="nombre">Nombre completo</label>
      <input type="text" name="nombre" id="nombre" size="50" 
        placeholder="Escribe tu nombre completo">

      <label for="email">Email</label>
      <input type="email" name="email" id="email" size="30">

      <label for="clave">Clave</label>
      <input type="password" name="clave" id="clave" size="10">

      <label for="linkedin">Likedin</label>
      <input type="url" name="linkedin" id="linkedin" size="50"> 

    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar">
  </form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  // Proceso el formulario
  echo <<<FORM
  <p>Nombre: {$_POST['nombre']}<br>
  Email: {$_POST['email']}<br>
  Clave: {$_POST['clave']}<br>
  Perfil: {$_POST['linkedin']}</p>
  FORM;

  echo "<p>Puede rellenar otra respuesta <a href='{$_SERVER['PHP_SELF']}'>aquí</a></p>";
}
finHtml();