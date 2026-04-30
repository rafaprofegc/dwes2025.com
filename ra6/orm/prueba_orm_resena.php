<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5\util\Html;
use ra6\orm\entidad\Reseña;
use ra6\orm\modelo\ORMReseña;
use ra6\orm\bd\BDFactory;

Html::inicioHtml("ORM de la tabla Reseña", ["/estilos/general.css", "/estilos/tabla.css"]);
try {
  $ormReseña = new ORMReseña(BDFactory::create());

  $reseña = new Reseña( ['id_resena' => null, 
                        'nif'       => '30000001A',
                        'referencia'=> 'CHUC0004',
                        'fecha'     => new \DateTime('2026-04-28 15:05:55'),
                        'clasificacion'  => 4,
                        'comentario'     => "Me gustan las gominolas"
                        ]);

  if( $ormReseña->insert($reseña) ) {
    echo "<h3>La reseña se ha insertado</h3>";
  }

  $ultimoId = $ormReseña->getLastInsertId();
  $reseña = $ormReseña->get([$ultimoId]);

  $fecha = $reseña->fecha->format(Reseña::FORMATO_FECHA_ES);

  echo <<<RESEÑA
    <p>
    Id Reseña: {$reseña->id_resena}<br>
    Nif: {$reseña->nif}<br>
    Rererencia: {$reseña->referencia}<br>
    Fecha: {$fecha}<br>
    Clasificación: {$reseña->clasificacion}<br>
    Comentario: {$reseña->comentario}</p>
  RESEÑA;
}
catch( \Exception $e) {
  Html::mostrarError($e);
}

Html::finHtml();
?>