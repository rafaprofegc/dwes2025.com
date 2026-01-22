<?php
namespace ra6p\orm\bd;

use PDOException;
use ra5\util\Util;

// Conexión a la BD con patrón Singleton

class BDSingleton {
  private static BDSingleton $instancia;
  private \PDO $pdo;

  private function __construct() {
    try {
      $config = require($_SERVER['DOCUMENT_ROOT'] . "/ra6p/orm/bd/BDconfig.php");
      $this->pdo = new \PDO($config['dsn'], $config['usuario'], $config['clave'], $config['opciones']);
    }
    catch(PDOException $pdoe) {
      Util::MuestraExcepcion($pdoe);
    }
  }
  
  public static function getInstancia() {
    if( !isset(self::$instancia) ) {
      self::$instancia = new self(); // new BDSingleton();
    }
    return self::$instancia;
  }

  public function getPDO() {
    return $this->pdo;
  }  
}

?>