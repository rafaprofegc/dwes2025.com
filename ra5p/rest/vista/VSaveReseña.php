<?php
namespace ra5p\rest\vista;

use ra6p\orm\entidad\Entidad;

class VSaveReseña extends Vista {
  public function generaSalida(mixed $reseña): void {
    ob_start();
    $this->inicioHtml("Reseña insertada", ["/estilos/general.css", "/estilos/tabla.css"]);
    echo <<<TABLA
    <header>Reseña insertada</header>
    <table>
      <tbody>
        <tr><th>Referencia</th><td>{$reseña->referencia}</td></tr>
        <tr><th>Cliente</th><td>{$reseña->nif}</td></tr>
        <tr><th>Fecha</th><td>{$reseña->fecha->format(Entidad::FORMATO_FECHA_HORA)}</td><tr>
        <tr><th>Clasificación</th><td>{$reseña->clasificacion}</td></tr>
        <tr><th>Comentario</th><td>{$reseña->comentario}</td></tr>
      </tbody>
    </table>
    <p><a href="/">Volver al inicio de la aplicación</a></p>
    TABLA;

    $this->finHtml();
    ob_end_flush();

  }
}