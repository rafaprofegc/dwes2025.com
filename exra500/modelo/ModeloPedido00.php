<?php
namespace exra500\modelo;

use PDO;
use exra500\entidad\Pedido00;

class ModeloPedido00 {
  protected const string TABLA = "pedido";
  protected const string PK = "npedido";

  protected \PDO $pdo;

  public function __construct() {
    $dsn="mysql:host=cpd.informatica.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4";
    $usuario = "usuario";
    $clave = "usuario";
    $opciones = [
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES    => false
    ];

    $this->pdo = new PDO($dsn, $usuario, $clave, $opciones);

  }

  public function procesaPeticion(): ?Pedido00 {
    $npedido = filter_input(INPUT_POST, 'npedido', FILTER_VALIDATE_INT);

    if( !$npedido ) throw new \Exception("El número de pedido es erróneo", 1004);

    $sql = "SELECT * FROM pedido WHERE npedido = :npedido";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":npedido", $npedido);
    $stmt->execute();
    $pedido = $stmt->fetch();
    if( !$pedido ) return null;
    return new Pedido00(intval($pedido['npedido']), 
                        $pedido['nif'], 
                        new \DateTime($pedido['fecha']),
                        $pedido['observaciones'],
                        floatval($pedido['total_pedido']) );
  }
}

?>