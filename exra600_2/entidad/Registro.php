<?php
namespace exra600_2\entidad;

class Registro {
  protected ?int $id;
  protected string $email;
  protected \DateTime $fecha_inscripcion;
  protected string $actividad;

  public const string FECHA_MYSQL = "Y-m-d";
  public const string FECHA_ESPANOL = "d/m/Y G:i:s";
  
  public function __construct( array $datos ) {
    foreach( $datos as $columna => $valor ) {
      $this->__set($columna, $valor);
    }
  }

  public function __set(string $propiedad, mixed $valor): void {
    if( property_exists($this, $propiedad) ) {
      switch( $propiedad ) {
        case 'id': {
          $this->$propiedad = intval($valor);
          break;
        }
        case 'fecha_inscripcion' : {
          if( $valor instanceof \DateTime ) {
            $this->$propiedad = $valor;
          }
          elseif( gettype($valor) === 'string') {
            $this->$propiedad = new \DateTime($valor);
          }
          else {
            throw new \Exception("El dato $valor no es una fecha");
          }
          break;
        }
        default: {
          $this->$propiedad = $valor;
        }
      }
    }
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;
  }

  public function toArray(): array {
    return get_object_vars($this);
  }
}
?>