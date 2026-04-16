<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra6\orm\bd\BDFactory;
use ra6\orm\modelo\ORMArticulo;
use ra6\orm\entidad\Articulo;
use ra6\orm\entidad\Entidad;

Html::inicioHtml("Prueba de las clases ORM", ["/estilos/general.css", "/estilos/tabla.css"]);
$ormArticulo = new ORMArticulo(BDFactory::create());

$articulos = $ormArticulo->getAll();

echo <<<TABLA
<h1>Listado de artículos</h1>
<table>
  <thead>
    <tr>
      <th>Referencia</th>
      <th>Descripción</th>
      <th>PVP</th>
      <th>Dto</th>
      <th>Und Vend</th>
      <th>Und Disp</th>
      <th>Fecha Disp</th>
      <th>Categoría</th>
      <th>Tipo IVA</th>
    </tr>
  </thead>
  <tbody>
TABLA;

  foreach($articulos as $articulo) {
    $fecha = $articulo->fecha_disponible ? $articulo->fecha_disponible->format(Articulo::FORMATO_FECHA_ES) : "";
    echo <<<FILA
      <tr>
        <td>{$articulo->referencia}</td>
        <td>{$articulo->descripcion}</td>
        <td>{$articulo->pvp}</td>
        <td>{$articulo->dto_venta}</td>
        <td>{$articulo->und_vendidas}</td>
        <td>{$articulo->und_disponibles}</td>
        <td>{$fecha}</td>
        <td>{$articulo->categoria}</td>
        <td>{$articulo->tipo_iva}</td>
      </tr>
    FILA;
  }

echo <<<TABLA
  </tbody>
</table>
TABLA;

$articulo = new Articulo(['referencia' => "ACIN0065", 
                          'descripcion' => "Cable HDMI", 
                          'pvp' => 3.5, 
                          'dto_venta' => 0.15, 
                          'und_vendidas' => 0, 
                          'und_disponibles' => 5, 
                          'fecha_disponible' => new DateTime('2025-05-10'), 
                          'categoria' => 'ACIN', 
                          'tipo_iva' => 'N']);
try {
  if( $ormArticulo->insert($articulo) ) {
    echo "<h3>Artículo con referencia {$articulo->referencia} insertado</h3>";
  }
}
catch( Exception $e ) {
  Html::mostrarError($e);
  exit();
}

$articuloInsertado = $ormArticulo->get([$articulo->referencia]);

$articuloInsertado->descripcion = "Cable HDMI v1.4";
$articuloInsertado->pvp = 6.75;
$articuloInsertado->dto_venta = 0.25;
$articuloInsertado->und_disponibles = 10;

try {
  if( $ormArticulo->update(['ACIN0065'], $articuloInsertado) ) {
    echo "<h3>Artículo con referencia {$articulo->referencia} modificado</h3>";
  }
}
catch( Exception $e) {
  Html::mostrarError($e);
  exit();
}

$articuloModificado = $ormArticulo->get([$articuloInsertado->referencia]);
echo <<<ARTICULO
<h3>Artículo {$articuloModificado->referencia}</h3>
<p>
Descripción: {$articuloModificado->descripcion}<br>
PVP: {$articuloModificado->pvp}€<br>
Dto Venta: {$articuloModificado->dto_venta}<br>
Und Vendidas: {$articuloModificado->und_vendidas}<br>
Und Disponibles: {$articuloModificado->und_disponibles}<br>
Fecha Disponible: {$articuloModificado->fecha_disponible->format(Articulo::FORMATO_FECHA_ES)}<br>
Categoría: {$articuloModificado->categoria}<br>
Tipo IVA: {$articuloModificado->tipo_iva}</p>
ARTICULO;

try {
  if( $ormArticulo->delete([$articuloModificado->referencia])) {
    echo "<h3>El artículo con referencia {$articuloModificado->referencia} se ha eliminado</h3>";
  }
}
catch( Exception $e) {
  Html::mostrarError($e);
}
?>