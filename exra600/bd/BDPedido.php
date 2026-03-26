<?php
namespace exra600\bd;

use exra600\entidad\Pedido;

class BDPedido {
  public const string TABLA = "pedido";
  protected \PDO $pdo;

  public function __construct() {
    $par = require($_SERVER['DOCUMENT_ROOT'] . "/exra600/util/config.php");
    $this->pdo = new \PDO($par['dsn'], $par['usuario'], $par['clave'], $par['opciones']);
  }

  public function getPedidos(string $nif): array {
    $sql = "SELECT * FROM " . self::TABLA . " WHERE nif = :nif";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":nif", $nif);
    $stmt->execute();

    $pedidos = [];
    while( $fila = $stmt->fetch() ) {
      $pedidos[] = new Pedido($fila);
    }
    return $pedidos;
  }
}
?>