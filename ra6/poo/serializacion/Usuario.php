<?php
class Usuario {
  public string $login;
  public string $nombre;
  public string $perfil;

  public string $archivoLog;

  private $log;

  public const string PERFIL_ADM = "Adm";
  public const string PERFIL_ESTANDAR = "Est";
  public const string PERFIL_INVITADO = "Inv";

  public function __construct(string $l, string $n, string $p, string $a) {
    $this->login = $l;
    $this->nombre = $n;
    $this->perfil = $p;
    $this->archivoLog = $a;

    $this->log = fopen($this->archivoLog, 'a');

  }

  public function __toString(): string {
    return "{$this->login} - {$this->nombre} - {$this->perfil}<br>";
  }

  public function registraActividad(string $descripcion): void {
    if( $this->log ) {
      $formatoFecha = "d/m/Y G:i:s";
      $actividad = date($formatoFecha) . " -> " . $descripcion . "\n";
      fwrite($this->log, $actividad);
    }
  }

  /* Serialización de objetos:
  Función serialize() se emplea para serializar cualquier cosa
  El método mágico __sleep() o __serialize() se invocan automaticamente
  cuando se usa serialize() con un objeto.

  La función serialize() se invoca automáticamente cuando guardamos
  un objeto como variable de sesión

  Al deserializar, se invocan los métoso mágicos __wakeup() o __unserialize()
  cuando se usa la función unserialize() sobre un objeto.

  Cuando se recupera una sesión, se invoca automáticamente la función
  unserialize()

  Proceso:
    1.- Se guarda un objeto como variable de sesión
    2.- Se invoca serialize($_SESSION['objeto'])
    3.- Se invoca __sleep() o __serialize() si están definidos en la clase
    4.- Se recupera un objeto de una sesión
    5.- Se invoca unserialize($_SESSION['objeto'])
    6.- Se invoca __wakeup() o __unserialize() si están definidos en la clase.
  */

  // Método mágico __sleep()
  public function __sleep(): array {
    // Devuelve un array con los nombres de las propiedades a serializar
    // Además, hay que cerrar todos los recursos abiertos
    if( $this->log ) fclose($this->log);

    return Array('login', 'nombre', 'perfil', 'archivoLog');
  }

  // Método mágico __serialize()
  public function __serialize(): array {
    return get_object_vars($this);
  }

  public function __wakeup(): void {
    $this->log = fopen($this->archivoLog, "a");
  }

  public function __unserialize(array $datos): void {
    $this->login = $datos['login'];
    $this->nombre = $datos['nombre'];
    $this->perfil = $datos['perfil'];
    $this->archivoLog = $datos['archivoLog'];
    
    $this->log = fopen($this->archivoLog, "a");
  }
}