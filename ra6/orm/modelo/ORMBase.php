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
    // UPDATE tabla 
    // SET columna1 = :columna1, columna2 = :columna2, ...
    // WHERE pk1 = :pk1 AND pk2 = :pk2 ...

    // $propiedades['referencia'] = 'ACIN0010';
    // $propiedades['pvp'] = 2.75;
    $propiedades = $datos->toArray();
    $sql = "UPDATE {$this->tabla} ";

    // $columnas = ['referencia', 'descripcion', 'pvp', 'dto_venta', ...]
    $columnas = array_keys($propiedades);
    // $parametros = ["referencia = :referencia", "descripcion = :descripcion", "pvp = :pvp", .... ]
    $parametros = array_map(fn($columna) => "$columna = :$columna", $columnas);

    // SET referencia = :referencia, descripcion = :descripcion, pvp = :pvp, ....
    $sql .= "SET " . implode(", ",  $parametros);

    $sql.= $this->whereID();

    $stmt = $this->pdo->prepare($sql);

    // Vincular los parámetros de las columnas que se actualizan
    foreach( $propiedades as $columna => $valor ) {
      $stmt->bindValue(":$columna", $valor);
    }

    // Vincular los valores de la clave primaria
    array_map( fn($columna, $valor) => $stmt->bindValue(":pk_$columna", $valor), 
      $this->clavePrimaria, $id);

    return $stmt->execute();
  }

  public function delete(array $id): bool {
    $sql = "DELETE from {$this->tabla} "  . $this->whereID();
    $stmt = $this->pdo->prepare($sql);
    
    array_map( fn($columna, $valor) => $stmt->bindValue(":pk_$columna", $valor), 
      $this->clavePrimaria, $id);

    return $stmt->execute();

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