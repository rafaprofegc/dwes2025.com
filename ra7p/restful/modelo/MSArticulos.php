<?php
namespace ra7p\restful\modelo;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;

class MSArticulos extends ModeloServicio {

  public function __construct() {
    $this->claseORM = new ORMArticulo(BDFactory::create() );
  }
  
  public function ValidacionDatos(): ?Articulo {

  }
  
}
?>