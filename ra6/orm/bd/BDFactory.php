<?php
namespace ra6\orm\bd;

use PDO;

class BDFactory {
  public static function create(): \PDO {
    $config = require($_SERVER['DOCUMENT_ROOT']. "/ra6/orm/bd/config.php");
    $pdo = new PDO($config['dsn'],
                   $config['usuario'],
                   $config['clave'],
                   $config['opciones']);

    return $pdo;
  }
}

?>