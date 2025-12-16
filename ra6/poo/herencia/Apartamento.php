<?php
class Apartamento extends Piso {
  protected int $nHabitaciones;

  public function __construct(string $r, string $d, int $s, 
          int $p, string $pu, int $nh ) {
    parent::__construct($r, $d, $s, $p, $pu);
    $this->nHabitaciones = $nh;
  }

  public function __toString(): string {
    $piso = parent::__toString();
    $piso .= ". Hab: {$this->nHabitaciones}";
    return $piso;
  }

  /*
    public function getPlantaPuerta(): string {
      return "Planta: $this->planta - Puerta: $this->puerta";
    }
  */
}