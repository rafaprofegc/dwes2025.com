<?php
namespace ra6\orm\entidad;

abstract class Entidad {

  public const string FORMATO_FECHA_MYSQL = "Y/m/d G:i:s";
  public const string FORMATO_FECHA_ES = "d/m/Y";

  public function __construct(array $datos) {
    foreach($datos as $propiedad => $valor) {
      $this->__set($propiedad, $valor);
    }
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;
  }

  public function __set(string $propiedad, mixed $valor): void {
    if( property_exists($this, $propiedad) ) {
      $this->$propiedad = $this->__cast($propiedad, $valor);
    }
  }

  public function __cast(string $propiedad, ?string $valor): mixed {
    $tiposDatos = static::getTipos();
    if( in_array($tiposDatos[$propiedad], ['int', 'float']) ) {
      if( $valor !== null && !is_numeric($valor) ) {
        throw new \Exception("El valor $valor no se puede asignar a una propiedad numérica");
      }
    }
    
    switch( $tiposDatos[$propiedad] ) {
      case 'int' : {
        return intval($valor);
        break;
      }
      case 'float': {
        return floatval($valor);
        break;
      }
      case \DateTime::class: {
        return $valor ? new \DateTime($valor) : null;
        break;
      }
      default: {
        return $valor;
      }
    }
  }

  public function toArray(): array {
    $propiedades = get_object_vars($this);

    $tipos = static::getTipos();

    array_walk($propiedades, function(mixed $valor, string $columna) use ($tipos) {
      switch( $tipos[$columna] ) {
        case \Datetime::class: {
          $propiedades[$columna] = $valor ? $valor->format(self::FORMATO_FECHA_MYSQL) : null;
          break;
        }
      }  
    });

    return $propiedades;
  }

  public abstract static function getTipos(): array;
}
?>
