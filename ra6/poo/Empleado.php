<?php
require_once("Direccion.php");

class Empleado {
  // Definición de propiedades
  // <nivel_acceso> [<tipo>] <$propiedad1> [ = <constante1>];

  public string $nif;
  public string $nombre;
  public string $apellidos;
  public ?float $salario;

  public array $cc;

  public ?Direccion $direccion;
  private ?string $telefono;

  // Definición de constantes de clase
  // const <CONSTANTE1> = <valor_cte1>;
  public const float IRPF = 0.2;
  const float SS = 0.05;
  const float SALARIO_BASE = 2000;

  // Definición de propiedades estáticas
  public static float $IRPF2 = 0.2;
  
  // Definición de métodos
  // Constructor de la clase

  public function __construct(string $nif, string $nombre, string $apellidos,
                              ?float $salario = null, array $cc = [], 
                              ?Direccion $direccion, string $telefono = "") {
    $this->nif = $nif;
    $this->nombre = $nombre;
    $this->apellidos = $apellidos;
    $this->salario = $salario;
    $this->cc = $cc;

    $this->direccion = $direccion;
    $this->telefono = $telefono;
  }                              
  
  /*
  public function __construct( public string $nif, public string $nombre, 
    public string $apellidos, public ?float $salario, public array $cc = [] ,
    protected string $direccion = "", private string $telefono = "") {}
  */

  public function getSalarioNeto(): ?float {
    if( $this->salario ) {
      $impSS = $this->salario * Empleado::SS;
      $impIRPF = $this->salario * Empleado::IRPF;
    
      return $this->salario - $impSS - $impIRPF;
    }
    return null;
  }

  // 10º Uso de objetos como argumentos
  public function esIgual(Empleado $otroEmpleado) : bool {
    return $this == $otroEmpleado;
  }

  // 11º Devolución de un objeto
  public function empleadoDuplicaSalario(): Empleado {
    $dir = new Direccion("c/", "Mayor", 5, 3, "A", 4,"B",28000, "Madrid");
    $emp = new Empleado($this->nif, $this->nombre, $this->apellidos,
    $this->salario * 2, [], $dir);
    return $emp;
  }

  public function __clone(): void {
    $this->direccion = clone $this->direccion;
  }

  // Miembros estáticos
  /*
  
  Un método estático pertenece a la clase, no a una instancia de objeto
  Se pueden ejecutar sin instanciar ningún objeto de la clase

  Problema: Para instanciarse necesito el nombre de la clase.
  Si uso la clase, en el método no puedo utilizar ninguna propiedad
  solo las variables locales del propio método o las constantes de clase
  o las propiedades estáticas de la clase.
  */

  public static function getPorcentajes(): string {
    $irpf = self::IRPF;
    $ss = self::SS;



    return "IRPF: " . ($irpf  * 100) . " - SS: " . ($ss * 100);
  }

}

?>