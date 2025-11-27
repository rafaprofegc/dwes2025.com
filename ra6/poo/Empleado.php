<?php
class Empleado {
  // Definición de propiedades
  // <nivel_acceso> [<tipo>] <$propiedad1> [ = <constante1>];
  public string $nif;
  public string $nombre;
  public string $apellidos;
  public ?float $salario;

  public array $cc;

  // Definición de constantes de clase
  // const <CONSTANTE1> = <valor_cte1>;
  const float IRPF = 0.2;
  const float SS = 0.05;
  const float SALARIO_BASE = 2000;
  
  // Definición de métodos
  // Constructor de la clase

  public function __construct(string $nif, string $nombre, string $apellidos,
                              ?float $salario = null, array $cc = []) {

    $this->nif = $nif;
    $this->nombre = $nombre;
    $this->apellidos = $apellidos;
    $this->salario = $salario;
    $this->cc = $cc;

  }
}

?>