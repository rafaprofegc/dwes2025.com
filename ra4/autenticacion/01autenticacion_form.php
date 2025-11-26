<?php
session_start();

/*
  Sesión de usuario:
   - Solo para almacenar datos entre peticiones.
   - Identificador PHPSESSID (cookie)
   - Array global $_SESSION

  Autenticación de usuario:
   - Formulario con usuario/clave 
   - Compruebo las credenciales en BD de usuarios
   - Mantengo datos de la identidad del usuario para
     las siguientes peticiones. 2 formas:
      - Usar variables de $_SESSION
      - Usar un JWT
*/

$usuarios = [
  'juan@loquesea.com' => ['nombre' => "Juan Gómez", 
                          'perfil' => "Admin",
                          'clave' => password_hash("abc123", PASSWORD_DEFAULT)],
  'maria@loquesea.com' => ['nombre' => "María García", 
                          'perfil' => "Usuario",
                          'clave' => password_hash("123abc", PASSWORD_DEFAULT)]                          
];

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

function autenticaUsuario(string $email, string $clave): int | bool {
  global $usuarios;

  if( !array_key_exists($email, $usuarios) ) {
    return 1; // Usuario no existe
  }

  if( password_verify($clave, $usuarios[$email]['clave']) ) {
    // Autenticación con éxito
    return True;
  }
  else {
    return 2; // Clave no correcta
  }
}

function Error(int $codigoError): void {
  $errores = [
    1 => "El usuario no existe",
    2 => "La clave no es correcta",
    3 => "Los datos con las credenciales de usuario no son válidos"
  ];

  inicioHtml("Login de usuario", ["/estilos/general.css"]);
  echo "<h3>Error de la aplicación</h3>";
  echo "<p>{$errores[$codigoError]}</p>";
  echo "<p>Puede volver a intentarlo <a href='{$_SERVER['PHP_SELF']}'>aquí</a></p>";
  finHtml();
}

function cerrarSesion(): void {
  $idSession = session_name();
  $parCookie = session_get_cookie_params();
  setCookie($idSession, "", time() - 1000,
    $parCookie['path'], $parCookie['domain'],
    $parCookie['secure'], $parCookie['httponly']);

  session_unset();

  session_destroy();
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  // Sanear y validar
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $clave = trim($_POST['clave']);

  if( !$email || !$clave ) {
    Error(3);
  }

  // Comprobar las credenciales del usuario
  $resultado = autenticaUsuario($email, $clave);
  if( $resultado === True ) {
    // Si la autentificación tiene éxito: Redirección a 02bienvenida.php

    // Si estos datos están en el payload del JWT, no se necesitan
    // como datos de sesión 
    //$_SESSION['login'] = $email;
    //$_SESSION['nombre'] = $usuarios[$email]['nombre'];
    //$_SESSION['perfil'] = $usuarios[$email]['perfil'];

    // Generar el JWT (JSON Web Token)
    $payload = [ 
      'email' => $email,
      'nombre' => $usuarios[$email]['nombre'],
      'perfil' => $usuarios[$email]['perfil']
    ];

    $jwt = generarJWT($payload);
    // El JWT se envía al cliente
    // 1ª Forma: con un encabezado
    //header("Authorization: Bearer $jwt");

    // 2ª Forma: con una cookie
    setCookie("jwt", $jwt, 0, "/", "dwes.com");
    header("Location: 02bienvenida.php");
  }
  else {
    // Si la autenticación no tiene éxito: Enlace a si mismo para reintentar
    Error($resultado);
  }
}

if( $_SERVER['REQUEST_METHOD'] === "GET") {
  // Presentamos el formulario de autenticación.
  header("Cache-control: max-age=3600");

  // Cierre de sesión
  cerrarSesion();
  inicioHtml("Login de usuario", ["/estilos/general.css", "/estilos/formulario.css"]);
?>
  <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <img src="login.png" style="width:300px;">
    <fieldset>
      <legend>Login de usuario</legend>
      <label for="email">Email</label>
      <input type="email" name="email" id="email" size="40">

      <label for="clave">Clave</label>
      <input type="password" name="clave" id="clave" size="10">
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Login">
  </form>
<?php
  finHtml();
}
?>