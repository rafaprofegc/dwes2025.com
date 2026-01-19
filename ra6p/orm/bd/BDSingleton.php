<?php
namespace ra6p\orm\bd;

class BDSingleton {
  private static BDSingleton $instancia;
  private \PDO $pdo;

  private function __construct() {

    $config = require($_SERVER['DOCUMENT_ROOT'] . "/ra6p/orm/bd/BDconfig.php");
    $this->pdo = new \PDO($config['dsn'], $config['usuario'], $config['clave'], $config['opciones']);
  }
  
  
}
// Conexión a la BD con patrón Singleton
?>