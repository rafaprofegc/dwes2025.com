<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5p\util\Html;
use ra5p\util\Util;
use ra6p\orm\bd\BDFactory;
USE ra6p\orm\bd\BDSingleton;

use ra6p\orm\modelo\ORMCliente;
use ra6p\orm\entidad\Cliente;

Html::inicioHtml("ORM", ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  $pdo = BDSingleton::getInstancia()->getPDO();
  $ormCliente = new ORMCliente( $pdo );

  // Obtener un artículo. Método get
  echo "<h3>Búsqueda de un cliente</h3>";
  $cliente = $ormCliente->get("30000001A");
  if( $cliente ) {
    echo "<p>Nif: {$cliente->nif}<br>";
    echo "Nombre: {$cliente->nombre} {$cliente->apellidos}<br>";
    echo "Iban: {$cliente->iban}<br>";
    echo "Tlf: {$cliente->telefono}<br>";
    echo "Email: {$cliente->email}<br>";
    echo "Ventas: {$cliente->ventas}</p>";
  }

  // Listado de todos los artículos. Método getAll()
  echo "<h3>Listado de clientes</h3>";
  $clientes = $ormCliente->getAll();
  echo <<<TABLA
  <table>
    <thead>
      <tr>
        <th>Nif</th>
        <th>Nombre</th>
        <th>Iban</th>
        <th>Telefono</th>
        <th>Email</th>
        <th>Ventas</th>
      </tr>
    </thead>
    <tbody>
  TABLA;
  foreach( $clientes as $cliente) {
    echo <<<CLIENTE
    <tr>
      <td>{$cliente->nif}</td>
      <td>{$cliente->nombre} {$cliente->apellidos}</td>
      <td>{$cliente->iban} €</td>
      <td>{$cliente->telefono}</td>
      <td>{$cliente->email}</td>
      <td>{$cliente->ventas}</td>
    CLIENTE;
  }
  echo <<<TABLA
    </tbody>
  </table>
  TABLA;
}
catch(Exception $e ) {
  Util::MuestraExcepcion($e);
  echo var_dump($e->getTrace());
}
catch(TypeError $te) {
  Util::MuestraExcepcion($te);
}

Html::finHtml();