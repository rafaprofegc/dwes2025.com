<?php
require_once("GestionSeguridad.php");

class Cliente implements GestionSeguridad {
  private string $email;
  private string $pin;
  private string $nombre;

  public function __construct(string $e, string $p, string $n) {
    $this->email = $e;
    $this->pin = $p;
    $this->nombre = $n;
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;
  }
  
  public function __toString(): string {
    return "Cliente: {$this->email} {$this->nombre}";
  }

  public function autenticar(string $token): bool {
    return $this->pin == $token;
  }

  public function cambiarToken(string $tokenAnterior, string $tokenNuevo): bool {
    if( !$this->autenticar($tokenAnterior) ) {
      return false;
    }

    $valorEntero = intval($tokenNuevo);
    if( $valorEntero && $valorEntero >= 1000 && $valorEntero <= 9999 ) {
      $this->pin = $tokenNuevo;
      return true;
    }
    else {
      return false;
    }
  }
}
?>