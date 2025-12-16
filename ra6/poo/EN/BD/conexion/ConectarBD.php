<?php
namespace EN\BD\conexion;

// Nombre real de la clase: EN\BD\conexion\ConectarBD
class ConectarBD {
  public string $usuario;
  public string $servidor;

  public function __construct(string $u, string $s) {
    $this->usuario = $u;
    $this->servidor = $s;
  }
}
?>

