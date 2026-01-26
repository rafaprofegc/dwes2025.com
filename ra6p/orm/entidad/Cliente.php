<?php
namespace ra6p\orm\entidad;

class Cliente extends Entidad {
  protected string $nif;
  protected string $nombre;
  protected string $apellidos;
  protected string $clave;
  protected string $iban;
  protected ?string $telefono;
  protected string $email;
  protected ?float $ventas;

  public static function getTipos(): array {
    return [
      'nif'         => 'string',
      'nombre'      => 'string',
      'apellidos'   => 'string',
      'clave'       => 'string',
      'iban'        => 'string',
      'telefono'    => 'string',
      'email'       => 'string',
      'ventas'      => 'float',
    ];
  }
}