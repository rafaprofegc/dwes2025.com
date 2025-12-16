<?php
require_once("Vivienda.php");

class Piso extends Vivienda {
  public int $planta;
  public string $puerta;

  public static float $INCREMENTO_PLANTA = 0.1;

  public function __construct(string $r, string $d, int $s, int $p, string $pu) {
    parent::__construct($r, $d, $s);
    $this->planta = $p;
    $this->puerta = $pu;
  }
  
  public function getDireccionCompleta(): string {
    return "{$this->direccion} {$this->planta} {$this->puerta}";
  }

  // Sobrescritura de métodos
  public function __toString(): string {
    $vivienda = parent::__toString();
    return $vivienda . " - {$this->planta} {$this->puerta}";  
  }

  /*
  public function getValorEstimado(float $precioMetro): float {
    $valorVivienda = parent::getValorEstimado($precioMetro);
    //$valorVivienda = $this->superficie * $precioMetro;
    $valorPiso = $valorVivienda + $valorVivienda * $this->planta * self::$INCREMENTO_PLANTA;
    return $valorPiso;
  }
  */
  public function getValorEstimado(float $precioMetro): float {
    $valorPiso = $this->superficie * $precioMetro;
    $valorPiso += $valorPiso * $this->planta * self::$INCREMENTO_PLANTA;
    return $valorPiso;
  }

  public final function getPlantaPuerta(): string {
    return "{$this->planta} - {$this->puerta}";
  }
  
  

}
?>