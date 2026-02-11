<?php
namespace ra7p\rpc\modelo;

use ra6p\orm\entidad\Articulo;
use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;

class JsonRpcArticulos {
  protected ORMArticulo $ormArticulo;

  public function __construct() {
    $this->ormArticulo = new ORMArticulo( BDFactory::create() );
  }
  
  public function getArticulos(): array {
    $articulos = $this->ormArticulo->getAll();
    return $articulos;
  }

  public function getArticulo(string $referencia): Articulo {
    $articulo = $this->ormArticulo->get($referencia);
    return $articulo;
  }
}
?>