<?php
class Casa extends Vivienda {
  public int $supPatio;
  public int $supJardin;

  public static int $METRO_PATIO = 30;
  public static int $METRO_JARDIN = 25;

  public function __construct(string $r, string $d, int $s, int $sp, int $sj) {
    parent::__construct($r, $d, $s);
    $this->supPatio = $sp;
    $this->supJardin = $sj;
  }

  /*
  public function getValorEstimado(float $precioMetro): float {
    $valorVivienda = parent::getValorEstimado($precioMetro);
    $valorVivienda += $this->supPatio * self::$METRO_PATIO 
      + $this->supJardin * self::$METRO_JARDIN;

    return $valorVivienda;
  }
  */

  public function __toString(): string  {
    $vivienda = parent::__toString();
    $casa = $vivienda . " Patio: {$this->supPatio} - Jardín: {$this->supJardin}";
    return $casa;
  }

  public function getValorEstimado(float $precioMetro): float {
    $valorCasa = $this->superficie * $precioMetro;
    $valorCasa += $this->supJardin * self::$METRO_JARDIN + $this->supPatio * self::$METRO_PATIO;
    return $valorCasa;
  }
}