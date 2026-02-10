<?php
namespace exra500\entidad;

class Pedido00 {

  public const string FORMATO_FECHA = "d/m/Y G:i:s";

  private int $npedido;
  private string $nif;
  private \DateTime $fecha;
  private ?string $observaciones;
  private ?float $total_pedido;

  public function __construct( int $np, string $n, string | \DateTime $f, ?string $o, ?float $tp) {
    $this->npedido = $np;
    $this->nif = $n;
    $this->observaciones = $o;
    $this->total_pedido = $tp;

    if( gettype($f) === "object" && $f instanceof \DateTime ) {
      $this->fecha = $f;
    }
    else {
      $this->fecha = new \DateTime($f);
    }
  }

  public function __set(string $propiedad, mixed $valor): void {
    if( property_exists($this, $propiedad) ) {
      $this->$propiedad = $valor;
    }
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad) ) {
      return $this->$propiedad;
    }
    return null;    
  }
}
?>