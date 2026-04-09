<?php
namespace exra600\entidad;

class Pedido {
  protected int $npedido;
  protected string $nif;
  protected \DateTime $fecha;
  protected ?string $observaciones;
  protected ?float $total_pedido;

  public function __construct(array $datos) {
    foreach($datos as $columna => $valor) {
      $this->__set($columna, $valor);
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
      if( $propiedad === "fecha" ) {
        $this->$propiedad = $valor instanceof \DateTime ? $valor : new \DateTime($valor);
      }
      else if( $propiedad === "npedido" ) {
        $this->$propiedad = intval($valor);
      }
      else if( $propiedad === "total_pedido") {
        $this->$propiedad = floatval($valor);
      }
      else {
        $this->$propiedad = $valor;
      }
    }
  }
}

?>