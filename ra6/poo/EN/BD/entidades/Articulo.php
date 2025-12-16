<?php
namespace EN\BD\entidades;

class Articulo {
  public string $ref;
  public float $pvp;
  public Cliente $c;
  public tiendaol\Categoria $cat;

  public function __construct(string $r, float $p) {
    $this->ref = $r;
    $this->pvp = $p;
  }
}

