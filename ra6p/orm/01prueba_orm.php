<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra5\util\Util;
use ra6p\orm\bd\BDFactory;
USE ra6p\orm\bd\BDSingleton;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\entidad\Articulo;

Html::inicioHtml("ORM", ["/estilos/general.css", "/estilos/tabla.css"]);

try {
  $pdo = BDSingleton::getInstancia()->getPDO();
  $ormArticulo = new ORMArticulo( $pdo );

  // Obtener un artículo. Método get
  echo "<h3>Búsqueda de un artículo</h3>";
  $articulo = $ormArticulo->get("ACIN0003");
  if( $articulo ) {
    echo "<p>Referencia: {$articulo->referencia}<br>";
    echo "Descripción: {$articulo->descripcion}<br>";
    echo "PVP: {$articulo->pvp}<br>";
    echo "Dto Venta: {$articulo->dto_venta}<br>";
    echo "Und Vendidas: {$articulo->und_vendidas}<br>";
    echo "Und Disponibles: {$articulo->und_disponibles}<br>";
    echo "Fecha Disponible: " . ($articulo->fecha_disponible ? 
      $articulo->fecha_disponible->format(Articulo::FORMATO_FECHA) : "" ) . "<br>";
    echo "Categoría: {$articulo->categoria}<br>";
    echo "Tipo IVA: {$articulo->tipo_iva}</p>";
  }

  // Listado de todos los artículos. Método getAll()
  echo "<h3>Listado de artículos</h3>";
  $articulos = $ormArticulo->getAll();
  echo <<<TABLA
  <table>
    <thead>
      <tr>
        <th>Referencia</th>
        <th>Descripción</th>
        <th>PVP</th>
        <th>Dto Venta</th>
        <th>Und Vendidas</th>
        <th>Und Disponibles</th>
        <th>Fecha Disponible</th>
        <th>Categoría</th>
        <th>Tipo IVA</th>
      </tr>
    </thead>
    <tbody>
  TABLA;
  foreach( $articulos as $articulo) {
    $fechaDisponible = $articulo->fecha_disponible ? 
      $articulo->fecha_disponible->format(Articulo::FORMATO_FECHA) : "";
    $dtoVenta = ($articulo->dto_venta * 100) . "%";
    echo <<<ARTICULO
    <tr>
      <td>{$articulo->referencia}</td>
      <td>{$articulo->descripcion}</td>
      <td>{$articulo->pvp} €</td>
      <td>{$dtoVenta}</td>
      <td>{$articulo->und_vendidas}</td>
      <td>{$articulo->und_disponibles}</td>
      <td>{$fechaDisponible}</td>
      <td>{$articulo->categoria}</td>
      <td>{$articulo->tipo_iva}</td>
    </tr>
    ARTICULO;
  }
  echo <<<TABLA
    </tbody>
  </table>
  TABLA;

  // Crear un artículo. Método insert()
  $nuevoArticulo = new Articulo([
    'referencia'  => 'ACIN0016',
    'descripcion' => 'Hub USB C',
    'pvp'         => 40.5,
    'dto_venta'   => 0.15,
    'und_vendidas'=> 0,
    'und_disponibles' => 20,
    'fecha_disponible' => null,
    'categoria' => 'ACIN',
    'tipo_iva' => 'N'
  ]);

  echo "<h3>Insertar un artículo</h3>";
  if( $ormArticulo->insert($nuevoArticulo) ) {
    echo "<p>El artículo con referencia {$nuevoArticulo->referencia}";
    echo " se ha insertado correctamente</p>";
  }
  else {
    echo "<p>El artículo con referencia {$nuevoArticulo->referencia}";
    echo " NO se ha insertado</p>";
  }

  echo "<h3>Modificar un artículo insertado</h3>";
  $articulo = $ormArticulo->get($nuevoArticulo->referencia);
  $articulo->descripcion = "Hub USC C Modificado";
  $articulo->pvp = 50.5;
  $articulo->dto_venta = 0.2;
  $articulo->und_disponibles = null;
  $articulo->fecha_disponible = new DateTime("2025-05-01");

  if( $ormArticulo->update($articulo->referencia, $articulo) ) {
    echo "<p>El artículo con referencia {$articulo->referencia}";
    echo " se ha actualizado correctamente</p>";
  }
  else {
    echo "<p>El artículo con referencia {$articulo->referencia}";
    echo " NO se ha actualizado</p>";
  }
}
catch(Exception $e ) {
  Util::MuestraExcepcion($e);
  echo var_dump($e->getTrace());
}
catch(TypeError $te) {
  Util::MuestraExcepcion($te);
}

Html::finHtml();