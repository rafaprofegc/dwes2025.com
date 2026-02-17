<?php
namespace ra7p\restful\modelo;

use ra6p\orm\modelo\ORMArticulo;
use ra6p\orm\bd\BDFactory;
use ra6p\orm\entidad\Articulo;

class MSArticulos extends ModeloServicio {

  public function __construct() {
    $this->claseORM = new ORMArticulo(BDFactory::create() );
  }

  
  protected function ValidacionDatos(bool $completo): Articulo | array | null {
    try {
      $entrada = file_get_contents("php://input");
      $datos = json_decode($json=$entrada, $associative=true, $flags = JSON_THROW_ON_ERROR );

      $filtro = [
        'referencia'    => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'flags' => FILTER_NULL_ON_FAILURE],
        'descripcion'   => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'flags' => FILTER_NULL_ON_FAILURE],
        'pvp'           => ['filter' => FILTER_VALIDATE_FLOAT,
                            'options' => ['min_range' => 0],
                            'flags' => FILTER_NULL_ON_FAILURE | FILTER_FLAG_ALLOW_FRACTION],
        'dto_venta'     => ['filter' => FILTER_VALIDATE_FLOAT,
                            'options' => ['min_rangen' => 0, 'max_range' => 1],
                            'flags' => FILTER_NULL_ON_FAILURE | FILTER_FLAG_ALLOW_FRACTION],
        'und_vendidas'  => ['filter' => FILTER_VALIDATE_INT,
                            'options' => ['min_rangen' => 0],
                            'flags' => FILTER_NULL_ON_FAILURE],
        'und_disponibles'=> ['filter' => FILTER_VALIDATE_INT,
                            'options' => ['min_rangen' => 0],
                            'flags' => FILTER_NULL_ON_FAILURE],
        'fecha_disponible' => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'flags' => FILTER_NULL_ON_FAILURE],
        'categoria'     => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'flags' => FILTER_NULL_ON_FAILURE],
        'tipo_iva'      => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                            'flags' => FILTER_NULL_ON_FAILURE]
      ];

      $datosValidados = filter_var_array($datos, $filtro, true);
      $datosValidos = array_filter($datosValidados, fn($d) => $d !== null );

      $datosObligatorios = $completo ? ['referencia','descripcion','pvp', 'categoria'] : [];
      $faltan = array_diff($datosObligatorios, array_keys($datosValidos));
      if ( !empty($faltan) ) return null;
      
      $articulo = $completo ? new Articulo($datosValidados) : $datosValidos;
      return $articulo;
    }
    catch(\JsonException $je) {
      return null;
    }
  }
    
  
}
?>