<?php
namespace ra5p\rest\controlador;

class Peticion {
  private string $metodoHttp;
  private string $expRegURI;
  private string $claseModelo;
  private string $claseVista;

  public function __construct(string $metodoHttp, string $expRegURI, 
                              string $cM, string $cV) {
    $this->metodoHttp = $metodoHttp;
    $this->expRegURI = $expRegURI;
    $this->claseModelo = $cM;
    $this->claseVista = $cV;
  }

  public function esIgual(string $metodoHTTP, string $pathPeticion): bool {
    return  $this->metodoHttp == $metodoHTTP &&
      preg_match($this->expRegURI, $pathPeticion);
  }

  public function getClaseModelo() {
    return $this->claseModelo;
  }

  public function getClaseVista() {
    return $this->claseVista;
  }

  public function getExpRegURI() {
    return $this->expRegURI;
  }
}
?>