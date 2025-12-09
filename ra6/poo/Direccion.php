<?php
class Direccion {
  /* Nivel de acceso:
  Cada miembro de la clase (propiedad, constante o método) tiene un nivel
  de acceso que indica donde es visible y accesible:

  - public -> Nivel de acceso público. El miembro es visible y accesible
    desde cualquier lugar: dentro o fuera de la clase.

  - private -> Nivel de acceso privado. El miembro SOLO ES VISIBLE Y
    ACCESIBLE dentro de su clase.

  - protected -> Nivel de acceso protegido. El miembro SOLO ES VISIBLE Y
    ACCESIBLE dentro de su clase y clases derivadas

  */
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

  private const MAPEO_METODOS = ['cambiarVia' => 'setTipoVia', 
                                 'cambiarNombreVia' => 'setNombreVia',
                                 'ponVia' => 'setTipoVia'];

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

  public function __toString(): string {
    $cadena = "{$this->tipoVia} {$this->nombreVia} {$this->numero}<br>";
    $cadena.= "{$this->portal} {$this->escalera} {$this->planta} {$this->puerta}<br>";
    $cadena.= "{$this->cp} {$this->localidad}";

    return $cadena;
  }

  private function convierteTipoVia(): string {
    $nombres= [ 'c/' => "Calle", 'Av' => "Avenida", 'Pz' => "Plaza"];
    return $nombres[$this->tipoVia];
  }

  public function __call(string $metodo, array $argumentos ): mixed {
    if( method_exists(self::class, $metodo) ) {
      return $this->$metodo(...$argumentos);
    }
    else {
      //echo "<h4>Error. El método $metodo no existe</h4>";
      //return null;
      if( array_key_exists($metodo, self::MAPEO_METODOS) ) {
        $metodoTraducido = self::MAPEO_METODOS[$metodo];
        return $this->$metodoTraducido(...$argumentos);
      }
      else {
        echo "<h4>Error. El método $metodo no existe</h4>";
        return null;
      }
    }
  }

  public function __debugInfo(): array {
    $salida = [];
    foreach($this as $propiedad => $valor) {
      $salida[$propiedad] = $valor;
    }
    return $salida;
  }
}
?>