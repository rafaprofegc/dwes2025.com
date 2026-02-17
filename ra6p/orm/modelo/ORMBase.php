<?php
namespace ra6p\orm\modelo;

use ra6p\orm\entidad\Entidad;

abstract class ORMBase {
  protected string $tabla;
  protected string $clavePrimaria;
  protected \PDO $cbd;

  public abstract function getClaseEntidad(): string;

  public function __construct(\PDO $pdo) {
    $this->cbd = $pdo;
  }

  // Métodos CRUD (Create Read Update Delete)

  // Read
  public function getAll(): array {
    $sql = "SELECT * FROM {$this->tabla} ";

    // Verificar si hay filtro por descripción
    if( isset($_GET['descripcion']) ) {
      $sql.= "WHERE descripcion LIKE :descripcion";
      $valor = filter_input(INPUT_GET, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    $stmt = $this->cbd->prepare($sql);
    if( isset($valor) ) {
      $stmt->bindValue(':descripcion', "%$valor%");
    }

    $stmt->execute();
    $filas = [];
    while( $fila = $stmt->fetch() ) {
      $clase = $this->getClaseEntidad();
      $filas[] = new $clase($fila);
    }

  return $filas;
  }

  // Read
  public function get(mixed $id): ?Entidad {
    $sql = "SELECT * FROM {$this->tabla} ";
    $sql.= "WHERE {$this->clavePrimaria} = :id";

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(':id', $id);

    $stmt->execute();
    $fila = $stmt->fetch();
    $clase = $this->getClaseEntidad();
    return $fila ? new $clase($fila) : null;   
  }

  // Create
  public function insert(Entidad $fila): bool {
    // INSERT INTO <tabla> (<col1>, <col2>, <col3>, ..., <coln>)
    // VALUES(<:vcol1>, <:vcol2>, ..., <:vcoln>);
    $propiedades = $fila->toArray();
    $columnas = array_keys($propiedades);

    // Quitar las ñ 
    $columnasSinÑ = array_map(fn($c) => str_replace("ñ", "n", $c), $columnas);

    $sql = "INSERT INTO {$this->tabla} ";
    $sql.= "(" . implode(", ", $columnas) . ") ";
    $sql.= "VALUES (:" . implode(", :",$columnasSinÑ) . ")";
    

    $stmt = $this->cbd->prepare($sql);
    /*
    array_map(fn($columna, $valor) => $stmt->bindValue(":$columna", $valor), 
      $columnas, $propiedades);

    array_walk($propiedades, 
      fn($valor, $columna) => $stmt->bindValue(":$columna", $valor));
    */
    foreach( $propiedades as $columna => $valor ) {
      $columnaSinÑ = str_replace("ñ","n",$columna);
      $stmt->bindValue(":$columnaSinÑ", $valor);
    }
    return $stmt->execute();
  }

  // Update
  public function update(mixed $id, Entidad | array $fila): bool {
    // UPDATE <tabla>
    // SET <col1> = <:valor1>, <col2> = <:valor2>, ...
    // WHERE <pk> = <:pk>;

    $propiedades = $fila instanceof Entidad ? $fila->toArray() : $fila;
    $sql = "UPDATE {$this->tabla} ";
    $columnas = array_map( fn($columna): string => "$columna = :$columna", 
      array_keys($propiedades));
    /*
    foreach( $propiedades as $columna => $valor ) {
      $columnas = "$columna = :$columna";
    }
    */
    $sql.= "SET " . implode(", ", $columnas);

    /* Tengo que poner el parámetro de la PK con un nombre diferente
       a la misma columna que aparece en la cláusula SET
       UPDATE articulo
      SET referencia = :referencia, descripcion = :descripcion, ...
      WHERE referencia = :referencia; --> Parámetro repetido
    */
    $sql.= " WHERE {$this->clavePrimaria} = :pk_{$this->clavePrimaria}";

    $stmt = $this->cbd->prepare($sql);
    // Vincular los valores nuevos de la fila
    /*
    array_map( fn($columna, $valor) => $stmt->bindValue(":$columna", $valor),
      array_keys($propiedades), $propiedades);

    array_walk( $propiedades, 
      fn(mixed $valor, string $columna) => $stmt->bindValue(":$columna", $valor));
    */
    foreach($propiedades as $columna => $valor ) {
      $stmt->bindValue(":$columna", $valor);
    }
    // Vincular el valor de PK
    $stmt->bindValue("pk_{$this->clavePrimaria}", $id);

    return $stmt->execute();
  }

  // Delete
  public function delete(mixed $id): bool {
    $sql = "DELETE FROM {$this->tabla} ";
    $sql.= "WHERE {$this->clavePrimaria} = :id";

    $stmt = $this->cbd->prepare($sql);
    $stmt->bindValue(":id", $id);
    return $stmt->execute();
  }
}
?>