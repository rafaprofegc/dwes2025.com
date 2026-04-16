<?php
namespace ra6\orm\modelo;

use ra6\orm\entidad\Articulo;

class ORMArticulo extends ORMBase {
  protected string $tabla = "articulo";
  protected array $clavePrimaria = ['referencia'];

  public function getClaseEntidad(): string {
    return Articulo::class;
  }
}