<?php
namespace ra6p\orm\modelo;

use ra6p\orm\entidad\Reseña;

class ORMReseña extends ORMBase {
  protected string $tabla = "reseña";
  protected string $clavePrimaria = "id_reseña";

  public function getClaseEntidad(): string {
    return Reseña::class;
  }
}