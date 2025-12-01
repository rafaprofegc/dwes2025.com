<?php
/* Pantalla inicial → Se autentifica al usuario. Si tiene éxito se 
informa al usuario de que todas las pizzas 
tienen tomate frito y queso como ingredientes básicos, con un precio 
inicial de 5 €. Además, se presenta el nombre, dirección, teléfono y 
se elige entre pizza vegetariana o no vegetariana. 
Las no vegetarianas tienen un incremento de 2 €.

Si no tiene éxito la autenticación, se presenta una pantalla de error 
y un enlace al inicio.
*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/jwt/include_jwt.php");

function SanearYValidar():array {

  // Sanear y validar el formulario
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  //$clave = filter_input(INPUT_POST, 'clave', FILTER_DEFAULT);
  $clave = $_POST['clave'];

  if( !$email ) $_SESSION['errores'][] = "El email no es válido";
  if( !$clave ) $_SESSION['errores'][] = "La clave no puede estar vacía";
  if (isset($_SESSION['errores'])) header("Location: 01inicio.php");

  return ['email' => $email, 'clave' => $clave];
}

function AutenticarUsuario(array $datosUsuario): array {
  $usuarios = [ 'juan@loquesea.com' => [
    'nombre'    => "Juan García", 
    'clave'     => password_hash("juan123", PASSWORD_DEFAULT),
    'direccion' => "c/Mayor, 5-3º-B",
    'telefono'  => 600101010
  ],
  'maria@loquesea.com' => [
    'nombre'    => "María Gómez", 
    'clave'     => password_hash("maria123", PASSWORD_DEFAULT),
    'direccion' => "Av La Palmera, 55-2º-B",
    'telefono'  => 600202020
  ]
  ];

  if( !array_key_exists($datosUsuario['email'], $usuarios)) {
    $_SESSION['errores'][] = "El usuario {$datosUsuario['email']} no existe";
    header("Location: 01inicio.php");
  }

  $usuario = $datosUsuario['email'];
  if( !password_verify($datosUsuario['clave'], $usuarios[$usuario]['clave'] ) ){
    $_SESSION['errores'][] = "La clave no es correcta";
    header("Location: 01inicio.php");
  }

  // Autentificación con éxito
  // Generamos el JWT y lo enviamos al cliente.
  $payload = ['email' => $usuario, 
              'nombre' => $usuarios[$usuario]['nombre'], 
              'telefono' => $usuarios[$usuario]['telefono'],
              'direccion' => $usuarios[$usuario]['direccion']
  ];

  $jwt = generarJWT($payload);
  setCookie("jwt", $jwt, time() + 20*60, "/");

  // Iniciamos el pedido
  $_SESSION['ingredientes'] = [];
  $_SESSION['vegetariana'] = false;

  return $payload;
}

function ComienzoPedido(array $datosUsuario): void {
  inicioHtml("Pedido de Pizza", ["/estilos/general.css"]);
?>
<header>Pizzas por encargo</header>
<h4>Datos del pedido</h4>
<p>Cliente: <?=$datosUsuario['email']?><br>
Nombre: <?= $datosUsuario['nombre'] ?><br>
Dirección: <?= $datosUsuario['direccion']?><br>
Teléfono: <?= $datosUsuario['telefono']?></p>
<h4>¡¡¡Atención!!!</h4>
<p>Todas nuestras pizzas llevan tomate frito y queso en la base. El precio
  de inicio es de 5€</p>
<h4>Empieza indicando qué tipo de pizza quieres</h4>
<form method="POST" action="03ingredientes.php">
  <fieldset>
  Tipo de pizza <input type="radio" name="tipo" id="tipo1" value="V">Vegetariana
  <input type="radio" name="tipo" id="tipo2" value="NV">No vegetariana (tiene un incremento de 2 €)
  <input type="submit" name="operacion" id="operacion" value="Añadir ingredientes">
  </fieldset>
</form>
<?php
  finHtml();
}

session_start();

$datosAutenticacion = SanearYValidar();

$usuario = AutenticarUsuario($datosAutenticacion);

ComienzoPedido($usuario);

?>