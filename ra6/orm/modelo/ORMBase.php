<?php
namespace ra6\orm\modelo;

use PDO;
use ra6\orm\entidad\Entidad;

abstract class ORMBase {
  protected PDO $pdo;
  protected string $tabla;
  protected array $clavePrimaria;

  public function __construct(PDO $pdo) {
    $this->pdo = $pdo;
  }

  public function getAll(): array {
    $sql = "SELECT *  FROM {$this->tabla}";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $filas = [];
    while( $fila = $stmt->fetch() ) {
      $clase = $this->getClaseEntidad();
      $filas[] = new $clase($fila);
    }
    return $filas;
  }

  public function get(array $id): ?Entidad {
    $sql = "SELECT * FROM {$this->tabla} " . $this->whereID();
    $stmt = $this->pdo->prepare($sql);

    array_map(fn($columna, $valor) => $stmt->bindValue(":pk_$columna", $valor),
      $this->clavePrimaria, $id);

    /* Lo mismo para asignar valores de los parámetros
    foreach($this->clavePrimaria as $indice => $columna) {
      $stmt->bindValue(":pk_$columna", $id[$indice]);
    }
    */
    $stmt->execute();
    $fila = $stmt->fetch();
    if( $fila ) {
      $clase = $this->getClaseEntidad();
      return new $clase($fila);
    }
    return null;
  }

  public function insert(Entidad $datos): bool {
    $propiedades = $datos->toArray();
    
    // $propiedades['referencia'] = 'ACIN0010';
    // $propiedades['pvp'] = 2.75;

    // INSERT INTO articulo
    // VALUES (referencia = :referencia, ..., pvp = :pvp)

    $columnas = array_map(fn($columna) => "$columna = :$columna", array_keys($propiedades));

    $sql = "INSERT INTO {$this->tabla} ";
    $sql.= "VALUES (" . implode(", ", $columnas) . ")";
   
    $stmt = $this->pdo->prepare($sql);
    array_walk($propiedades, fn($valor, $columna) => $stmt->bindValue(":$columna", $valor));

    return $stmt->execute();
  }

  public function update(array $id, Entidad $datos): bool {
    return true;
  }

  public function delete(array $id): bool {
    return true;
  }

  private function whereID(): string {
    // En artículo $this->clavePrimaria = ['referencia'];
    // En linea_pedido $this->clavePrimaria = ['npedido', 'nlinea'];
    // WHERE npedido = :pk_npedido AND nlinea = :pk_nlinea;
    $condiciones = array_map( fn($columna) => "$columna = :pk_$columna", $this->clavePrimaria);
    $clausulaWHERE = "WHERE " . implode(" AND ", $condiciones);
    return $clausulaWHERE;

  }
  public abstract function getClaseEntidad(): string;
    

}