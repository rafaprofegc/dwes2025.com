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
function SanearValidar():array {

  // Sanear y validar el formulario
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  //$clave = filter_input(INPUT_POST, 'clave', FILTER_DEFAULT);
  $clave = $_POST['clave'];

  if( !$email ) $_SESSION['errores'][] = "El email no es válido";
  if( !$clave ) $_SESSION['errores'][] = "La clave no puede estar vacía";
  if (isset($_SESSION['errores'])) header("Location: 01inicio.php");

  return ['email' => $email, 'clave' => $clave];
}

function AutenticarUsuario(array $datosUsuario): void {
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
}

function ComienzoPedido() {

}

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

$datosAutenticacion = SanearValidar();

AutenticarUsuario($datosAutenticacion);

ComienzoPedido();

?>