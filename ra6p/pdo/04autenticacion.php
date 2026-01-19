<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\seguridad\JWT;
use ra5\util\Html;
use ra5\util\Util;

Html::inicioHtml("Autenticación de usuarios en la BD",
  ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<header>Autenticación de usuario en la BD</header>";

if( $_SERVER['REQUEST_METHOD'] === "GET") {
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
  <fieldset>
    <legend>Indique sus credenciales</legend>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="15" require>

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size="15" require>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
</form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  // Sanear y validar
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $clave = $_POST['clave'];

  if( !$email ) {
    echo "<h3>Error. El email no tiene formato correcto</h3>";
    echo "<p><a href=\"{$_SERVER['PHP_SELF']}\">Volver a intentarlo</a></p>";
    Html::finHtml();
    exit(1);
  }

  // Tenemos las credenciales y las verificamos
  try {
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
    $usuario = "usuario";
    $claveBD="usuario";
    $opciones = [
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES    => false
    ];

    $pdo = new PDO($dsn, $usuario, $claveBD, $opciones);

    $sql = "SELECT nif, nombre, apellidos, email, clave ";
    $sql.= "FROM cliente ";
    $sql.= "WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);

    if( $stmt->execute() ) {
      if( $stmt->rowCount() == 1 ) {
        $usuario = $stmt->fetch();
        if( password_verify($clave, $usuario['clave']) ) {
          // Usuario autenticado con éxito
          $payload = [
            'nif'     => $usuario['nif'],
            'nombre'  => "{$usuario['nombre']} {$usuario['apellidos']}",
            'email'   => $usuario['email']
          ];
          $jwt = JWT::generarJWT($payload);
          setcookie("jwt", $jwt, 0, "/", "dwes.com", false, false );

          echo "<h3>Autenticación con éxito</h3>";
          echo "<p><a href=\"04inicio.php\">Ir a su zona autenticada</a></p>";
        }
        else {
          throw new Exception("La clave no es válida", 9000);
        }
      }
      else {
        throw new Exception("El usuario no existe", 9001);
      }
    } 
  }
  catch( Exception $e ) {
    Util::MuestraExcepcion($e);
  }
  finally {
    $pdo = null;
  }
}

Html::finHtml();
?>
