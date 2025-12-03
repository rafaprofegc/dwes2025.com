<?php
/*
Pantalla de autenticación → Se presenta un formulario con email/clave 
para que el usuario se autentifique. Solo los usuarios autenticados 
pueden pedir pizzas.
*/
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

// Cierre de sesión
<<<<<<< HEAD
if( $_SERVER['REQUEST_METHOD'] === "GET" ) {
  $operacion = filter_input(INPUT_GET, 'op', FILTER_SANITIZE_SPECIAL_CHARS);
  if( $operacion === "logout" ) {
    $idSesion = session_name();
    $parCookie = session_get_cookie_params();
    setCookie($idSesion, "", time() - 100, 
    $parCookie['path'], $parCookie['domain'], 
    $parCookie['secure'], $parCookie['httponly']);

    setCookie("jwt", "", time() - 100, "/");

    session_unset();
    session_destroy();
    session_start();
  }
}

=======
if( true ) {
  $idSesion = session_name();
  $parCookie = session_get_cookie_params();
  setCookie($idSesion, "", time() - 100, 
  $parCookie['path'], $parCookie['domain'], 
  $parCookie['secure'], $parCookie['httponly']);

  setCookie("jwt", "", time() - 100, "/");

  session_unset();

  session_destroy();

  session_start();

}
>>>>>>> origin/main
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