<?php
namespace ra5p\rest\modelo;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;

use ra5p\seguridad\Auth;

class MMain implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $ormArticulo = new ORMArticulo( BDFactory::create() );
    $losMasVendidos = $ormArticulo->getLosMasVendidos(5);

    if( Auth::check() ) {
      $cliente = Auth::cliente();
      $ultimosArticulosComprados = $ormArticulo->ultimasCompras($cliente->nif);
    }

    return [
      'sinAuth' => $losMasVendidos, 
      'conAuth' => isset($ultimosArticulosComprados) ? $ultimosArticulosComprados : null
    ];
  }
}
?>