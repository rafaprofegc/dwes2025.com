<?php
namespace ra5p\rest\modelo;

use ra5p\mvc\error\ErrorAplicacion;
use ra6p\orm\bd\BDFactory;
use ra6p\orm\modelo\ORMArticulo;
use ra5p\seguridad\Auth;

class MNewReseña implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $ormArticulo = new ORMArticulo(BDFactory::create());
    $referencia = $parametros[0];

    $cliente = Auth::cliente();
    if( $cliente !== null && $ormArticulo->haComprado($cliente->nif, $referencia) ) {
      $articulo = $ormArticulo->get($referencia);
      return $articulo;
    }
    throw new ErrorAplicacion("No se pueden añadir reservas los clientes que no han comprado el artículo", 7, 
      ['url' => "/", 'texto' => 'Volver al inicio de la aplicación']);
  }
}