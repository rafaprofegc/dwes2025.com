<?php
namespace ra5p\rest\modelo;

use ra6p\orm\bd\BDSingleton;
use ra6p\orm\modelo\ORMArticulo;

class MGetArticulos implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $ormArticulo = new ORMArticulo(BDSingleton::getInstancia()->getPDO());
    $articulos = $ormArticulo->getAll();
    return $articulos;
  }
}