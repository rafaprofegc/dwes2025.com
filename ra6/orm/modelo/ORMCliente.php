<?php
namespace ra6\orm\modelo;

use ra6\orm\modelo\ORMBase;
use ra6\orm\entidad\Cliente;

class ORMCliente extends ORMBase {
  protected string $tabla = "cliente";
  protected array $clavePrimaria = ['nif'];

  public function getClaseEntidad(): string {
    return Cliente::class;
  }
}