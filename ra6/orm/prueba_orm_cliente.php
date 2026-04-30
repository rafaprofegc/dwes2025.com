<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra6\orm\bd\BDFactory;
use ra6\orm\modelo\ORMCliente;
use ra6\orm\entidad\Cliente;

Html::inicioHtml("Prueba de las clases ORM", ["/estilos/general.css", "/estilos/tabla.css"]);
$ormCliente = new ORMCliente(BDFactory::create());

$clientes  = $ormCliente->getAll();

echo <<<TABLA
<h1>Listado de clientes</h1>
<table>
  <thead>
    <tr>
      <th>Nif</th>
      <th>Nombre</th>
      <th>Apellidos</th>
      <th>Clave</th>
      <th>Iban</th>
      <th>Teléfono</th>
      <th>Email</th>
      <th>Ventas</th>
    </tr>
  </thead>
  <tbody>
TABLA;

  foreach($clientes as $cliente) {
    echo <<<FILA
      <tr>
        <td>{$cliente->nif}</td>
        <td>{$cliente->nombre}</td>
        <td>{$cliente->apellidos}</td>
        <td>{$cliente->clave}</td>
        <td>{$cliente->iban}</td>
        <td>{$cliente->telefono}</td>
        <td>{$cliente->email}</td>
        <td>{$cliente->ventas} €</td>
      </tr>
    FILA;
  }

echo <<<TABLA
  </tbody>
</table>
TABLA;

$cliente = new Cliente(['nif' => "77000001A", 
                          'nombre' => "Javier", 
                          'apellidos' => "López",
                          'iban' => "ES84",
                          'clave' => "\$2y\$10\$jbX04d45oFsYy71796ZmZeMp2VBUDFypbULhWIU.oNgfQXD0568iG", 
                          'telefono' => "957000000", 
                          'email' => "javier@gemail.com",
                          'ventas' => 0]);
try {
  if( $ormCliente->insert($cliente) ) {
    echo "<h3>Cliente con nif {$cliente->nif} insertado</h3>";
  }
}
catch( Exception $e ) {
  Html::mostrarError($e);
  exit();
}


$clienteInsertado = $ormCliente->get([$cliente->nif]);

$clienteInsertado->apellidos = "García García";
$clienteInsertado->iban = "ES99";
$clienteInsertado->ventas = 1000;
$clienteInsertado->telefono = "957001122";

try {
  if( $ormCliente->update([$clienteInsertado->nif], $clienteInsertado) ) {
    echo "<h3>Cliente con nif {$clienteInsertado->nif} modificado</h3>";
  }
}
catch( Exception $e) {
  Html::mostrarError($e);
  exit();
}

$clienteModificado = $ormCliente->get([$clienteInsertado->nif]);
echo <<<CLIENTE
<h3>Cliente {$clienteModificado->nif}</h3>
<p>
Nombre: {$clienteModificado->nombre} {$clienteModificado->apellidos}<br>
Email: {$clienteModificado->email}€<br>
IBAN: {$clienteModificado->iban}<br>
Ventas: {$clienteModificado->ventas} €</p>
CLIENTE;

try {
  if( $ormCliente->delete([$clienteModificado->nif])) {
    echo "<h3>El cliente con nif {$clienteModificado->nif} se ha eliminado</h3>";
  }
}
catch( Exception $e) {
  Html::mostrarError($e);
}
?>