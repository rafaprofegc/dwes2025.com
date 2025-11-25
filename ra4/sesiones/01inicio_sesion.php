<?php
/*
- Sesión: El conjunto de acciones del usuario cuando interactúa con
  una aplicación web. Típicamente una sesión comienza cuando el 
  usuario accede por primera vez a la aplicación y termina cuando
  se cierra el navegador.

  Durante la sesión el usuario realiza varias peticiones a la aplicación,
  cada una de las cuales genera y procesa datos.

  Problema: ES necesario que los datos que se generan en una petición, 
  puedan estar disponibles en sucesivas peticiones.

  PHP tiene soporte para gestionar las sesiones y proporcionar variables
  persistentes entre peticiones, conocidas como variables de sesión.

  Las variables de sesión son accesibles en todos los scripts que forman
  la aplicación mientras dure la sesión del usuario.

  El servidor web administra y gestiona varios sitios web, a cada uno 
  ellos se conectan varios usuarios y las variables de sesión que se generan
  para cada uno de ellos son exclusivas y privadas. Las variables de sesión
  de un usuario solo las ve y accede el propio usuario, no se comparten
  con las otras sesiones de usuario.

  PHP asigna un ID de sesión a cada sesión de usuario y cada sesión
  tiene un almacen de datos independiente de las otras sesiones para
  almacenar las variables de sesión.

  Funcionamiento básico:
    - Al iniciar la aplicación, en el primer script hay que ejecutar
      la función session_start().

    - Si no hay un sesión creada, se crea una y se le asigna una ID de
      sesión. Este ID se envía con una cookie llamada PHPSESSID. También
      se crea el array superglobal $_SESSION.

    - Si ya hay una sesión previa creada, se recupera el PHPSESSID
      (cookie) que contiene el ID de sesión y se carga el array
      superglobal $_SESSION.

    - En el array $_SESSION usamos la clave como nombre de variable.

  - Cierre de una sesión:
    - Al cerrar el navegador.
    - Se puede cerrar explícitamente por el usuario.
      - Se borra el PHPSESSID (cookie)
      - Se invoca la función session_destroy().
      
  - Recolector de basura. Si alguna variable de $_SESSION no ha sido
    referenciada durante algún tiempo predeterminado, la variable
    se borra.

  - Se puede configurar el recolector de basura en php.ini

  Ejemplo:
  Aplicación para que los trabajadores de una empresa registren
  que quieren en sus cestas de navidad.

*/

// Script 1 de la sesión: Recoger los datos personales
// Inicio de la sesión. Se asigna PHPSESSID y se crea $_SESSION
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
//require_once($_SERVER['DOCUMENT_ROOT'] . "/ra4/sesiones/05sesion_include.php");
require_once("05sesion_include.php");

$operacion = filter_input(INPUT_GET, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);

if( $operacion === "cerrar" ) {
  // Hay que cerrar la sesión
  
  // 1º Borrar la cookie con el id de sesión: PHPSESSID
  $idSesion = session_name(); // PHPSESSID
  $parametrosCookie = session_get_cookie_params();
  setCookie($idSesion, "", time() - 60, 
  $parametrosCookie['path'], $parametrosCookie['domain'],
  $parametrosCookie['secure'], $parametrosCookie['httponly'] );

  session_destroy();
  unset($_SESSION);

  session_start();
}

inicioHtml("Sesiones en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);
ob_start();
echo "<header>Mi cesta de Navidad</header>";
?>
<form action="02datos_sesion.php" method="POST">
  <fieldset>
    <legend>Datos personales</legend>
    <label for="nombre">Nombre completo</label>
    <input type="text" name="nombre" id="nombre" size="40" required>

    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="40" required>
  </fieldset>

  <input type="submit" name="operacion" id="operacion" value="Enviar datos personales">
</form>
<?php
finHtml();
ob_flush();
?>
