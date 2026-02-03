<?php
namespace ra5p\rest\vista;

use ra6p\orm\entidad\Entidad;

class VGetArticulo extends Vista {
  public function generaSalida(mixed $datos): void {
    // Inicio del buffer de salida
    ob_start();

    $this->inicioHtml("Datos de un artículo", ["/estilos/general.css", "/estilos/tabla.css"]);
    if( $datos === null ) {
      echo "<h3>El artículo solicitado no existe</h3>";
    }
    else {
      $fechaDisponible = $datos->fecha_disponible ? 
        $datos->fecha_disponible->format(Entidad::FORMATO_FECHA) : "";

      echo <<<ARTICULO
      <h3>Datos del artículo {$datos->referencia}</h3>
      <p>Descripción: {$datos->descripcion}<br>
      PVP: {$datos->pvp}<br>
      Dto Venta: {$datos->dto_venta}<br>
      Und. Vendidas: {$datos->und_vendidas}<br>
      Und. Disponibles: {$datos->und_disponibles}<br>
      Fecha Disponibilidad: {$fechaDisponible}<br>
      Categoría: {$datos->categoria}<br>
      Tipo IVA: {$datos->tipo_iva}</p>
      ARTICULO;
    }
    $this->finHtml();
    ob_end_flush();
  }
}