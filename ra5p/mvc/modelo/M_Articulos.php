<?php
namespace ra5p\mvc\modelo;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;

class M_Articulos implements Modelo {

  public function procesaPeticion(): mixed {
    $ormArticulo = new ORMArticulo(BDFactory::create());
    $articulos = $ormArticulo->getAll();
    return $articulos;
  }
}