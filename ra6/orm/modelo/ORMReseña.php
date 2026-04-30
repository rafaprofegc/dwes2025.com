<?php
namespace ra6\orm\modelo;

use ra6\orm\entidad\Reseña;

class ORMReseña extends ORMBase {
  protected string $tabla = "reseña";
  protected array $clavePrimaria = ['id_resena'];

  public function getClaseEntidad(): string {
    return Reseña::class;
  }
}