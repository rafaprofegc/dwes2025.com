<?php
namespace ra5p\rest\vista;

use ra6p\orm\entidad\Articulo;

class VGetArticulos extends Vista {
  public function generaSalida(mixed $datos): void {
    ob_start();
    $this->inicioHtml("Listado de artículos", ["/estilos/general.css", "/estilos/tabla.css"]);
    echo <<<ARTICULOS
    <header>Listado de artículos</header>
    <table>
      <thead>
        <tr>
          <th>Referencia</th>
          <th>Descripción</th>
          <th>PVP</th>
          <th>Dto Venta</th>
          <th>Und. Vendidas</th>
          <th>Und. Disponibles</th>
          <th>Fecha disponibilidad</th>
          <th>Categoría</th>
          <th>Tipo IVA</th>
        </tr>
      </thead>
      <tbody>
    ARTICULOS;

    array_walk($datos, function(Articulo $articulo) {
      $fechaDisponibilidad = $articulo->fecha_disponible ? 
        $articulo->fecha_disponible->format(Articulo::FORMATO_FECHA) : "";

      $dto = $articulo->dto_venta * 100;
      echo <<<ARTICULO
      <tr>
        <td>{$articulo->referencia}</td>
        <td>{$articulo->descripcion}</td>
        <td>{$articulo->pvp}€</td>
        <td>{$dto}%</td>
        <td>{$articulo->und_vendidas}</td>
        <td>{$articulo->und_disponibles}</td>
        <td>{$fechaDisponibilidad}</td>
        <td>{$articulo->categoria}</td>
        <td>{$articulo->tipo_iva}</td>
      </tr>
      ARTICULO;
    });
    echo <<<ARTICULOS
      </tbody>
    </table>
    ARTICULOS;

    $this->finHtml();
    ob_end_flush();
  }
}