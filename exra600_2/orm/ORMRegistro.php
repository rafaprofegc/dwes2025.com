<?php
namespace exra600_2\orm;

use PDO;
use exra600_2\entidad\Registro;

class ORMRegistro {

  public const string TABLA = "registro_asistente";
  protected PDO $pdo;

  public function __construct() {
    $usuario = "usuario";
    $clave = "usuario";
    $dsn = "mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
    $opciones = [
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES    => false
    ];

    $this->pdo = new \PDO($dsn, $usuario, $clave, $opciones);
  }

  public function insertar(Registro $registro): bool {
    $sql = "INSERT INTO " . $this::TABLA . " VALUES ";
    $propiedades = $registro->toArray();
    $columnas = array_keys($propiedades);
    $sql.= "( :" . implode(", :", $columnas) . ")";

    $stmt = $this->pdo->prepare($sql);
    foreach( $propiedades as $columna => $valor) {
      if( $columna === "fecha_inscripcion") {
        $stmt->bindValue(":$columna", $valor->format($registro::FECHA_MYSQL));
      }
      else {
        $stmt->bindValue(":$columna", $valor);
      }      
    }

    return $stmt->execute();
  }

  public function listar(string $email): array {
    $sql = "SELECT * FROM " . $this::TABLA;
    $sql.= " WHERE email = :email";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":email", $email);

    $stmt->execute();

    $filas = [];
    while( $fila = $stmt->fetch() ) {
      $filas[] = new Registro($fila);
    }
    return $filas;
  }
}

?>