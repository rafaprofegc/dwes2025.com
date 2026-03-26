<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use exra600\util\Html;

function CerrarSesion() {
  $sesion = session_name();
  $parametros = session_get_cookie_params();
  setcookie($sesion, "", time() - 100, $parametros['path'],
    $parametros['domain'], $parametros['secure'], $parametros['httponly']);

  session_destroy();
  session_start();
}

// Cierre de sesión
if( isset( $_GET['cerrar']) ) {
  CerrarSesion();
}


if( $_SERVER['REQUEST_METHOD'] === "GET") {
  Html::inicioHtml("Autenticación de cliente", 
    ["/exra600/estilos/general.css", "/exra600/estilos/formulario.css"]);
  if( isset($_SESSION['error']) ) {
    echo "<h3>{$_SESSION['error']}</h3>";
    unset($_SESSION['error']);
  }
?>
  <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
    <fieldset>
      <legend>Autenticación de cliente</legend>
      <label for="email">Email</label>
      <input type="email" name="email" id="email" size="10">

      <label for="clave">Clave</label>
      <input type="password" name="clave" id="clave" size="10">
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
  </form>
<?php
  Html::finHtml();
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  try {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'];

    if( !$email || !$clave) {
      throw new Exception("El email o la clave no son correctos"); 
    } 

    $par = require($_SERVER['DOCUMENT_ROOT'] . "/exra600/util/config.php");
    
    $pdo = new PDO($par['dsn'], $par['usuario'], $par['clave'], $par['opciones']);

    $sql = "SELECT nif, nombre, apellidos, email, clave FROM cliente WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->execute();

    $cliente = $stmt->fetch();
    if( !$cliente ) {
      $_SESSION['error'] = "El cliente no existe";
      header("Location: /exra600/index.php");
    }

    if( password_verify($clave, $cliente['clave']) ){
      // Éxito en la autentificación
      $_SESSION['nif'] = $cliente['nif'];
      $_SESSION['nombre'] = "{$cliente['nombre']} {$cliente['apellidos']}";
      $_SESSION['email'] = $cliente['email'];
      header("Location: /exra600/inicio.php");
    }
    else {
      $_SESSION['error'] = "La clave no es correcta";
      header("Location: /exra600/index.php");
    }

  }
  catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: /exra600/index.php");
  }
}



?>
