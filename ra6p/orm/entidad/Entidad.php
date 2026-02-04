<?php
namespace ra6p\orm\entidad;

abstract class Entidad {

  public static abstract function getTipos(): array;

  // Constante de clase
  public const FORMATO_FECHA_MYSQL = "Y-m-d G:i:s";
  public const FORMATO_FECHA = "d/m/Y";
  public const FORMATO_FECHA_HORA = "d/m/Y G:i:s";
  
  // Constructor. No es necesario sobrescribirlo
  // en las clases derivadas
  public function __construct(array $datos) {
    foreach($datos as $columna => $valor) {
      $this->__set($columna, $valor);
    }
    
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad)) {
      return $this->$propiedad;
    }
    return null;
  }

  public function __set(string $propiedad, mixed $valor) {
    if( property_exists($this, $propiedad) ) {
      $this->$propiedad = $this->__cast($propiedad, $valor);
    }
  }
  
  private function __cast(string $propiedad, mixed $valor ): mixed {
    $tiposDatos = static::getTipos();
    $tipoPropiedad = $tiposDatos[$propiedad];
    
    if( $valor === null ) return null;

    switch( $tipoPropiedad ) {
      case 'int' :
        $v = (int)$valor; // $v = intval($valor);
        break;
      case 'float':
        $v = (float)$valor;
        break;
      case 'bool':
        $v = (bool)$valor;
        break;
      case 'string':
        $v = $valor;
        break;
      case \DateTime::class:
        $v = $valor instanceof \DateTime ? $valor : new \DateTime($valor);
        break;
      default:
        throw new \TypeError("El tipo de datos de $valor de la propiedad $propiedad no es correcto");
    }
    return $v;
  }
  
  public function toArray(): array {
    $propiedades = get_object_vars($this);
    $infoTipos = static::getTipos();
    /*
    $columnas = [];
    array_walk($propiedades, 
      function(mixed $valor, string $columna) use ($infoTipos, &$columnas) {
      switch($infoTipos[$columna]) {
        case 'int' | 'float' | 'bool' | 'string' :
          $v = $valor;
          break;
        case \DateTime::class: 
          $v = $valor->format(self::FORMATO_FECHA_MYSQL);
          break;
      }
      $columnas[$columna] = $valor;
    });
    */
    
    foreach($propiedades as $columna => $valor) {
      switch($infoTipos[$columna]) {
        case 'int':
        case 'float':
        case 'bool':
        case 'string' :
          $v = $valor;
          break;
        case \DateTime::class: 
          $v = $valor ? $valor->format(self::FORMATO_FECHA_MYSQL) : $valor;
          break;
      }
      $columnas[$columna] = $v;
    }
    return $columnas;   
  }

  public function __serialize(): array {
    return $this->toArray();
  }

  public function __unserialize(array $datos): void {
    array_walk($datos, function(mixed $valor, string $propiedad) {
      $this->__set($propiedad, $valor);
    });
  }
}
?>