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

  public function ejecutaSQL(array $parametros): array {
    $sql = "SELECT * FROM {$this->tabla} ";
    // ['descripcion' => $valor]
    $tipos = Articulo::getTipos();
    $columnas = array_map( function($columna) use ($tipos) {
      $operador = $tipos[$columna] === "string" ? "LIKE" : "=";
      return "$columna $operador :$columna";
    }, array_keys($parametros)
    );
    /*
    Igual con foreach

    $columnas = [];
    $tipos = Articulo::getTipos();
    foreach( array_keys($parametros) as $columna ) {
      $operador = $tipos[$columna] === "string" ? "LIKE" : "=";
      $columnas[] = "$columna $operador :$columna";
    }
    */

    $clausulaWhere = "WHERE " . implode(" AND ", $columnas);
    $sql.= $clausulaWhere;

    $stmt = $this->cbd->prepare($sql);
    array_walk($parametros, function(string $valor, string $columna) 
      use ($stmt, $tipos) {
      $v = $tipos[$columna] === "string" ? "%$valor%" : $valor;
      $stmt->bindValue($columna, $v);
    });

    $filas = [];
    $clase = $this->getClaseEntidad();
    if( $stmt->execute() ) {
      while( $fila = $stmt->fetch() ) {
        $filas[] = new $clase($fila);
      }
    } 
    return $filas;
  }
}