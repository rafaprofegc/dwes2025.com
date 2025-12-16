<?php
namespace EN\BD\entidades\tiendaol;

class Categoria {
  public string $cat;
  public string $desc;

  public function __function(string $c, string $d) {
    $this->cat = $c;
    $this->desc = $d;
  }
}