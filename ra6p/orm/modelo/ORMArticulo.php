<?php
namespace ra6p\orm\modelo;

use ra6p\orm\entidad\Articulo;

class ORMArticulo extends ORMBase {
  protected string $tabla = "articulo";
  protected string $clavePrimaria = "referencia";

  public function getClaseEntidad(): string {
    return Articulo::class;
  }
}