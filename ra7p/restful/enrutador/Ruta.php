<?php
namespace ra7p\restful\enrutador;

class Ruta {
  protected string $metodoHTTP;
  protected string $expRegURI;
  protected string $claseModelo;
  protected string $metodo;
  protected bool $requiereAuth;
  protected array $perfiles;

  public function __construct(string $metodoHTTP, 
                              string $expRegURI,
                              string $claseModelo,
                              string $metodo,
                              bool $requiereAuth = false,
                              array $perfiles = ['user']) {

    $this->metodoHTTP = $metodoHTTP;
    $this->expRegURI = $expRegURI;
    $this->claseModelo = $claseModelo;
    $this->metodo = $metodo;
    $this->requiereAuth = $requiereAuth;
    $this->perfiles = $perfiles;
  }

}