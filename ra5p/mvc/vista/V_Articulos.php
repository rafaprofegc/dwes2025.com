<?php
namespace ra5p\mvc\vista;
use ra6p\orm\entidad\Entidad;

class V_Articulos extends Vista {
  public function generaSalida(mixed $datos): void {
    // Usamos buffer ya que si hay una excepción
    // en la vista de error lo vamos a limpiar
    ob_start();

    // Empezamos a enviar salida
    $this->inicioHtml("Listados de Artículos", ["/estilos/general.css", "/estilos/tabla.css"]);

    echo <<<TABLA
    <table>
      <thead>
        <tr>
          <th>Referencia</th>
          <th>Descripción</th>
          <th>PVP</th>
          <th>Fecha Disponible</th>
        </tr>
      </thead>
      <tbody>
    TABLA;

    // Listamos los artículos
    array_walk($datos, function($articulo) {
      $fechaDisponible = $articulo->fecha_disponible ? 
        $articulo->fecha_disponible->format(Entidad::FORMATO_FECHA) : "";
      echo <<<ARTICULO
      <tr>
        <td>{$articulo->referencia}</td>
        <td>{$articulo->descripcion}</td>
        <td>{$articulo->pvp}</td>
        <td>$fechaDisponible</td>
      </tr>
      ARTICULO;
    });
    /*
    foreach($datos as $articulo) {
      $fechaDisponible = $articulo->fecha_disponible ? 
        $articulo->fecha_disponible->format(Entidad::FORMATO_FECHA) : "";
      echo <<<ARTICULO
      <tr>
        <td>{$articulo->referencia}</td>
        <td>{$articulo->descripcion}</td>
        <td>{$articulo->pvp}</td>
        <td>$fechaDisponible</td>
      </tr>
      ARTICULO;
    }
    */

    echo <<<TABLA
      </tbody>
    </table>
    TABLA;

    $this->finHtml();
    ob_end_flush();
  }
}
?>