<?php
class Direccion {
  private ?string $tipoVia;
  private string $nombreVia;
  private int $numero;
  private int $portal;
  private string $escalera;
  private int $planta;
  private string $puerta;
  private int $cp;
  private string $localidad;

  private const PROPIEDADES = ['tipoVia', 'nombreVia', 'numero'];
  private const TIPOS_VIAS = ["c/", "Av", "Pz", "Crta", "Ronda"];

  public function __construct(string $tv, string $nv, int $n, int $p, 
    string $e, int $pl, string $pu, int $cp, string $l ){
    $this->tipoVia = $tv;
    $this->nombreVia = $nv;
    $this->numero = $n;
    $this->portal = $p;
    $this->escalera = $e;
    $this->planta = $pl;
    $this->puerta = $pu;
    $this->cp = $cp;
    $this->localidad = $l;
  }

  // Métodos getter y setter para tipoVia
  public function getTipoVia(): string {
    return $this->tipoVia;
  }

  public function setTipoVia(string $nuevoValor): void {
    
    if( in_array($nuevoValor, self::TIPOS_VIAS) ) $this->tipoVia = $nuevoValor;
  }

  // Sobrecarga de propiedades
  public function __get(string $propiedad): mixed {
    // if( property_exists(__CLASS__, $propiedad) ) {
    if( property_exists(self::class, $propiedad) ) {
      if( in_array($propiedad, self::PROPIEDADES)) {
        return $this->$propiedad;
      }
      echo "<p>Warning: No tiene acceso a $propiedad</p>";
      return null;
    }
    echo "<p>Warning: La propiedad $propiedad sin definir en " . __CLASS__ . "</p>";
    return null;
  }

  public function __set(string $propiedad, mixed $valor): void {
    if( property_exists(__CLASS__, $propiedad) ) {
      switch($propiedad) {
        case "tipoVia" : {
          if( in_array($valor, self::TIPOS_VIAS) ) {
            $this->$propiedad = $valor;
          }
          break;
        }
        case "numero": {
          if( $valor > 0 ) $this->$propiedad = $valor;
          break;
        }
        default: {
          $this->$propiedad = $valor;
        }
      }
    }
  }

  public function __isset(string $propiedad): bool {
    return property_exists(self::class, $propiedad) && 
      !empty($this->$propiedad);
  }

  public function __unset(string $propiedad): void {
    if( property_exists(self::class, $propiedad) ) {
      unset($this->$propiedad);
    }
    else {
      echo "<p>Warning: La propiedad $propiedad no existe en " . __CLASS__ . "</p>";
    }
  }
}
?>