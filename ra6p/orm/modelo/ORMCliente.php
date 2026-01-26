<?php
namespace ra6p\orm\modelo;

use ra6p\orm\entidad\Cliente;

class ORMCliente extends ORMBase {
  protected string $tabla = "cliente";
  protected string $clavePrimaria = "nif";

  public function getClaseEntidad(): string {
    return Cliente::class;
  }
}

?>