<?php
namespace ra5p\rest\modelo;

use ra5p\seguridad\Auth;

class MLogout implements Modelo {

  public function procesaPeticion(array $parametros): mixed {
    Auth::logout();
    header("Location: /");
    return null;
  }
}
?>