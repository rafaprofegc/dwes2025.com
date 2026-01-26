<?php
namespace ra6p\orm\modelo;

use ra6p\orm\entidad\Articulo;

class ORMArticulo extends ORMBase {
  protected string $tabla = "articulo";
  protected string $clavePrimaria = "referencia";

  public function getClaseEntidad(): string {
    return Articulo::class;
  }

  public function getLosMasVendidos(int $n): array {
    $sql = <<<SQL
    SELECT * FROM {$this->tabla}
    ORDER BY und_vendidas DESC
    LIMIT :n
    SQL;

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":n", $n);
    $filas = [];
    $claseEntidad = $this->getClaseEntidad();
    if( $stmt->execute() ) {
      while( $fila = $stmt->fetch() ) {
        $filas[] = new $claseEntidad($fila);
      }
    }
    return $filas;
  }
}