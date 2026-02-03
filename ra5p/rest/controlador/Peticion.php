<?php
namespace ra5p\rest\controlador;

class Peticion {
  private string $metodoHttp;
  private string $expRegURI;
  private ?string $claseModelo;
  private ?string $claseVista;
  private bool $requiereAuth;

  public function __construct(string $metodoHttp, string $expRegURI, 
                              ?string $cM, ?string $cV, bool $requiereAuth = false) {
    $this->metodoHttp = $metodoHttp;
    $this->expRegURI = $expRegURI;
    $this->claseModelo = $cM;
    $this->claseVista = $cV;
    $this->requiereAuth = $requiereAuth;
  }

  public function esIgual(string $metodoHTTP, string $pathPeticion): bool {
    return  $this->metodoHttp == $metodoHTTP &&
      preg_match($this->expRegURI, $pathPeticion);
  }

  public function getClaseModelo():?string {
    return $this->claseModelo;
  }

  public function getClaseVista():?string {
    return $this->claseVista;
  }

  public function getExpRegURI(): string {
    return $this->expRegURI;
  }

  public function getRequiereAuth(): bool {
    return $this->requiereAuth;
  }
}
?>