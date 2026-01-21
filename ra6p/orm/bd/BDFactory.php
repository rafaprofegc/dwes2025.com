<?php
namespace ra6p\orm\bd;

class BDFactory {
  public static function create(): \PDO {
    $config = require("BDconfig.php");
    $pdo = new \PDO($config['dsn'], 
                   $config['usuario'], 
                   $config['clave'],
                   $config['opciones']);
    return $pdo;
  }
}
?>