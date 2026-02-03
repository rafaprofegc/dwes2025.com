<?php
namespace ra5p\rest\modelo;

use ra6p\orm\bd\BDFactory;
use ra6p\orm\modelo\ORMArticulo;

class MGetArticulo implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $ormArticulo = new ORMArticulo(BDFactory::create());
    $articulo = $ormArticulo->get($parametros[0]);
    return $articulo;
  }
}