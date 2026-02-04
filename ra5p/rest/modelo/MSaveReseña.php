<?php
namespace ra5p\rest\modelo;

use ra5p\mvc\error\ErrorAplicacion;
use ra6p\orm\bd\BDFactory;
use ra6p\orm\entidad\Reseña;
use ra6p\orm\modelo\ORMReseña;

class MSaveReseña implements Modelo {
  public function procesaPeticion(array $parametros): mixed {
    $filtros = [
      'nif'           => FILTER_SANITIZE_SPECIAL_CHARS,
      'referencia'    => FILTER_SANITIZE_SPECIAL_CHARS,
      'clasificacion' => [
        'filter'  => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0, 'max_range' => 5]
      ],
      'comentario'    => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    $datosValidados = filter_input_array(INPUT_POST, $filtros);

    $datosValidados['nif'] = preg_match("/[0-9]{8}[A-Za-z]/", $datosValidados['nif']) ? : false;

    $obligatorios = ['nif', 'referencia', 'clasificacion'];
    array_filter($datosValidados);

    $faltan = array_diff($obligatorios, array_keys($datosValidados));
    if( count($faltan) > 0 ) {
      throw new ErrorAplicacion("Algún dato obligatorio no está presente", 9,
        ['url' => '/', 'texto' => "Ir al inicio de la aplicación"]);
    }

    //En este punto, los datos son válidos
    $reseña = new Reseña(
      [
        'id_reseña'     => null,
        'nif'           => $datosValidados['nif'],
        'referencia'    => $datosValidados['referencia'],
        'fecha'         => new \DateTime(),
        'clasificacion' => $datosValidados['clasificacion'],
        'comentario'    => $datosValidados['comentario'] 
      ]
    );

    $ormReseñas = new ORMReseña(BDFactory::create());
    $ormReseñas->insert($reseña);

    return $reseña;

  }

}