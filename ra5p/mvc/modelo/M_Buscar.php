<?php
namespace ra5p\mvc\modelo;

use ra6p\orm\bd\BDFactory;
use ra6p\orm\modelo\ORMArticulo;

class M_Buscar implements Modelo {
  public function procesaPeticion(): mixed {
    $ormArticulo = new ORMArticulo( BDFactory::create() );
    
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);

    $parametros = ['descripcion' => $descripcion];
    $articulos = $ormArticulo->ejecutaSQL($parametros);
    return $articulos;
  }
}
?>