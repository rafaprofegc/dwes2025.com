<?php
require_once("GestionSeguridad.php");

class Emp implements GestionSeguridad {
  private string $nif;
  private string $clave;
  private string $nombre;

  public function __construct(string $n, string $c, string $no) {
    $this->nif = $n;
    $this->clave = $c;
    $this->nombre = $no;
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;
  }
  public function __toString(): string {
    return "Emp: {$this->nif} {$this->nombre}";
  }

  public function autenticar(string $token): bool {
    return $this->clave == $token;
  }

  public function cambiarToken(string $tokenAnterior, string $tokenNuevo): bool {
    if( !$this->autenticar($tokenAnterior) ) {
      return false;
    }

    $digitos = "/[0-9]/";
    $letrasMinusculas = "/[a-z]/";
    $letrasMayusculas = "/[A-Z]/";

    $hayDigitos = preg_match($digitos, $tokenNuevo);
    $hayLetrasMi = preg_match($letrasMinusculas, $tokenNuevo);
    $hayLetrasMa = preg_match($letrasMayusculas, $tokenNuevo);

    if( $hayDigitos && $hayLetrasMi && $hayLetrasMa ) {
      $this->clave = $tokenNuevo;
    }
    return $hayDigitos && $hayLetrasMi && $hayLetrasMa;
  }

}
?>