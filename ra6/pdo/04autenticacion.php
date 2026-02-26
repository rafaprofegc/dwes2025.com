<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\seguridad\JWT;

session_start();

Html::inicioHtml("Autenticación de usuario", ["/estilos/general.css", "/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] === "GET") { ?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Autenticación de usuario</legend>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="20" required>

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size="10" required>

  </fieldset>

  <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
</form>

<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  try {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'];

    if( !$email ) {
      throw new \Exception("El email recibido no es válido", 1000);
    }

    // Tengo email y clave
    // Leo el cliente de la BD
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
    $usuario = "usuario";
    $password = "usuario";
    $opciones = [
      PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES      => false
    ];

    $pdo = new PDO($dsn, $usuario, $password, $opciones);

    
    $sql = "SELECT * FROM cliente WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":email", $email);
    $stmt->execute();

    $cliente = $stmt->fetch();

    if( !$cliente ) {
      throw new Exception("El usuario $email no existe", 1001);
    }

    if( !password_verify($clave, $cliente['clave'] ) ) {
      throw new Exception("La clave no es correcta", 1002);
    }

    // La autenticación ha tenido éxito
    $payload = [
      'nif'       => $cliente['nif'],
      'nombre'    => $cliente['nombre'] . " " . $cliente['apellidos'],
      'email'     => $email
    ];

    $jwt = JWT::generarJWT($payload);
    setcookie("jwt", $jwt, 0, "/", "dwes.com");
    $_SESSION['carrito'] = [];
    $_SESSION['inicio'] = new DateTime();
    header("Location: 04inicio.php");
    exit;
  }
  catch( Exception $e) {
    Html::mostrarError($e);
    echo <<<ENLACE
      <p><a href="{$_SERVER['PHP_SELF']}">Volver a intentarlo</a></p>
    ENLACE;
  }
}

Html::finHtml();
?>