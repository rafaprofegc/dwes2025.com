<?php
namespace ra5p\rest\modelo;

use ra6p\orm\bd\BDFactory;
use ra6p\orm\modelo\ORMCliente;
use ra5p\rest\error\ErrorAplicacion;
use ra5p\seguridad\Auth;

class MLogin implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'];
    if( $email && $clave ) {
      $ormCliente = new ORMCliente(BDFactory::create());
      $cliente = $ormCliente->autenticar($email, $clave);
      if( $cliente ) {
        // Autenticación con éxito
        // Crear el JWT (cookie)
        // Iniciar variables de sesión (carrito, cliente)
        Auth::login($cliente);
        header("Location: /");
        exit;
      }
      else {
        throw new ErrorAplicacion("Las credenciales de usuario no son correctas", 5, 
        ['url' => "/login", 'texto' => "Ir al formulario de autenticación"]);
      }
    }
    else {
      throw new ErrorAplicacion("Las credenciales de usuario no pueden estar vacías", 5, 
        ['url' => "/login", 'texto' => "Ir al formulario de autenticación"]);
    }
  }
}