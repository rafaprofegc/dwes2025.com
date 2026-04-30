<?php
namespace ra6\orm\entidad;

class Reseña extends Entidad {
  protected ?int $id_resena;
  protected string $nif;
  protected string $referencia;
  protected \DateTime $fecha;
  protected int $clasificacion;
  protected ?string $comentario;

  public static function getTipos(): array {
    return [
      'id_resena'           => 'int',
      'nif'                 => 'string',
      'referencia'          => 'string',
      'fecha'               => \DateTime::class,
      'clasificacion'       => 'int',
      'comentario'          => 'string'
    ];
  }
}