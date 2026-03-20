<?php
namespace ra6\orm\bd;

use PDO;

// Para obtener una conexión PDO desde fuera de esta clase
// $pdo = BDSingleton::getInstancia()->getPDO();

class BDSingleton {
  private static ?BDSingleton $instancia = null;
  private PDO $pdo;

  private function __construct() {
    $config = require($_SERVER['DOCUMENT_ROOT'] . "/ra6/orm/bd/config.php");
    $this->$pdo = new PDO($config['dsn'],
                          $config['usuario'],
                          $config['clave'],
                          $config['opciones']);
  }

  public static function getInstancia(): self {
    if( !isset(self::$instancia) ) {
      self::$instancia = new self();
    }
    return self::$instancia;
  }

  public function getPDO(): PDO {
    return $this->pdo;
  }
}
?>

