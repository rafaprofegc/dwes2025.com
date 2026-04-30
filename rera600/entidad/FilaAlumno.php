<?php
namespace rera600\entidad;

class FilaAlumno {
  protected string $dni;
  protected ?string $curso;
  protected ?string $grupo;
  protected ?\DateTime $fecha_nacimiento;

  public const FORMATO_FECHA_ES = 'd/m/Y';
  public const FORMATO_FECHA_MYSQL = 'Y-m-d';

  public function __construct(array $filaBD) {
    foreach($filaBD as $columna => $valor) {
      $this->__set($columna, $valor);
    }
  }

  public function __set(string $propiedad, mixed $valor): void {
    if( property_exists($this, $propiedad) ) {
      switch($propiedad) {
        case 'dni':
        case 'curso':
        case 'grupo': {
          $this->$propiedad = $valor;
          break;
        }
        case 'fecha_nacimiento': {
          if( $valor instanceof \Datetime ) {
            $this->$propiedad = $valor;
          }
          elseif( gettype($valor) === 'string' ) {
            $this->$propiedad = new \DateTime($valor);
          }
          else {
            throw new \Exception("La propiedad $propiedad puede ser string o DateTime");
          }
        }
      }
    }
  }

  public function __get(string $propiedad): mixed {
    if( property_exists($this, $propiedad)) {
      return $this->$propiedad;
    }
    return null;
  }

  public function __toString(): string {
    $fecha = $this->fecha_nacimiento->format(self::FORMATO_FECHA_ES);
    return <<<OBJETO
      {$this->dni} {$this->curso}-{$this->grupo} {$fecha}
    OBJETO;
  }
}
?>

