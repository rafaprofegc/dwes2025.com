<?php
class Vivienda {
  protected string $ref;
  protected string $direccion;
  protected int $superficie;

  public function __construct(string $r, string $d, int $s) {
    $this->ref = $r;
    $this->direccion = $d;
    $this->superficie = $s;
  }
  
  
  public function __get(string $propiedad):mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;
  }

  /*
  public function __set(string $propiedad, mixed $valor): void {

  } 
  */

  public function __toString(): string {
    return "Vivienda: {$this->ref} {$this->direccion} {$this->superficie} m<sup>2</sup>";
  }

  public function getValorEstimado(float $precioMetro): float {
    return $this->superficie * $precioMetro;
  }
}