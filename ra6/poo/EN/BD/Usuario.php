<?php
namespace EN\BD;

class Usuario {
  public string $login;
  public string $perfil;

  public \DateTime $ultimaSesion;

  public function __construct(string $l, string $p) {
    $this->login = $l;
    $this->perfil = $p;
  }
}