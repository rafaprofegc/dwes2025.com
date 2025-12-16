<?php
namespace EN\BD\entidades;

class Cliente {
  public string $nif;
  public string $nombre;

  public function __construct(string $n, string $no) {
    $this->nif = $n;
    $this->nombre = $no;
  }
}