<?php
namespace ra7p\restful\enrutador;

use ra7p\restful\error\ErrorServicio;

/* La respuesta que se envía será:
  exito -> true o false
  datos -> Datos de la respuesta
  codigo -> Código de estado
  error -> Los datos del error
*/

class RespuestaFactory {

  private static function enviarJSON(array $resultado): void {
    http_response_code($resultado['codigo']);
    header("Content-type: application/json; charset=utf-8");
    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
  }

  public static function ok(mixed $datos): void {
    $resultado = [
      'exito' => true,
      'datos' => $datos,
      'codigo' => 200,
      'error' => null
    ];

    self::enviarJSON($resultado);
  }

  public static function created(mixed $datos): void {
    $resultado = [
      'exito' => true,
      'datos' => $datos,
      'codigo' => 201,
      'error' => null
    ];

    self::enviarJSON($resultado);
  }

  public static function noContent(): void {
    http_response_code(204);
    exit;
  }

  public static function error(ErrorServicio $es): void {
    $resultado = [
      'exito' => false,
      'datos' => null,
      'codigo' => $es->getEstado(),
      'codigoError' => $es->getCodigoError(),
      'error' => $es->getMensajeError()
    ];

    self::enviarJSON($resultado);
  }

}
?>