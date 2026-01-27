<?php
namespace ra5p\mvc\modelo;

use ra6p\orm\bd\BDFactory;
use ra6p\orm\modelo\ORMArticulo;

class M_Main implements Modelo {
  public function procesaPeticion(): mixed {
    $ormArticulo = new ORMArticulo( BDFactory::create() );
    $losMasVendidos = $ormArticulo->getLosMasVendidos(5);
    return $losMasVendidos;
  }
}