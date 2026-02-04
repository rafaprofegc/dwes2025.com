<?php
namespace ra5p\rest\vista;

class VNewReseña extends Vista {
  public function generaSalida(mixed $articulo): void {
    ob_start();

    $this->inicioHtml("Añadir una nueva reseña", ["/estilos/general.css", "/estilos/formulario.css"]);
    echo <<<FORM
    <h3>Añadir una nueva reseña</h3>
    <form method="POST" action="/reseñas">
      <input type="hidden" name="nif" id="nif" value="{$this->cliente->nif}">
      <input type="hidden" name="referencia" id="referencia" value="{$articulo->referencia}">

      <fieldset>
      <legend>Reseña de {$articulo->descripcion}</legend>
        <label for="clasificacion">Clasificación</label>
        <input type="range" min="0" max="5" step="1" name="clasificacion" id="clasificacion" value="0">

        <label for="comentario">Comentarios</label>
        <textarea name="comentario" id="comentario" cols="40" rows="5"></textarea>
      </fieldset>
      <input type="submit" name="operacion" id="operacion" value="Añadir reseña">
    </form>
    FORM;
    $this->finHtml();
    ob_end_flush();
  }
}