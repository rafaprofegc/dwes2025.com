<?php
namespace rera600\orm;

use \PDO;
use rera600\entidad\FilaAlumno;

class ORMAlumno {
  public static string $TABLA = "alumno";
  public static string $CLAVE = "dni";

  protected PDO $pdo;

  public function __construct() {
    $dsn = "mysql:host=cpd.informatica.org;port=9990;dbname=tiendaol;charset=utf8mb4";
    $opciones = [
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES    => false
    ];

    $this->pdo = new \PDO($dsn, "usuario", "usuario", $opciones );
  }

  public function actualizar( FilaAlumno $alumno): bool {
    $sql = "UPDATE " . self::$TABLA ;
    $sql.= " SET curso = :curso, grupo = :grupo, fecha_nacimiento = :fecha_nacimiento ";
    $sql.= " WHERE dni = :pk_dni";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":curso", $alumno->curso);
    $stmt->bindValue(":grupo", $alumno->grupo);
    $stmt->bindValue(":fecha_nacimiento", $alumno->fecha_nacimiento->format(FilaAlumno::FORMATO_FECHA_MYSQL));
    $stmt->bindValue(":pk_dni", $alumno->dni);

    return $stmt->execute();

  }

  public function listar(string $dni): ?FilaAlumno {
    $sql = "SELECT * FROM alumno WHERE dni = :$dni";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":dni", $dni);
    $stmt->execute();
    $fila = $stmt->fetch();

    if( $fila ) {
      return new FilaAlumno($fila);
    }

    return null;
  }

}
?>