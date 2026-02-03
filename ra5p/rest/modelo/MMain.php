<?php
namespace ra5p\rest\modelo;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;

class MMain implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $ormArticulo = new ORMArticulo( BDFactory::create() );
    $losMasVendidos = $ormArticulo->getLosMasVendidos(5);
    return $losMasVendidos;
  }
}
?>