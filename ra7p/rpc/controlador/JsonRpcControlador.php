<?php
namespace ra7p\rpc\controlador;

class JsonRpcControlador {

  public function ManejarPeticion(): void {
    // Recoger los datos de la petición
    // que vienen el cuerpo
    // Ejemplo en JSON-RPC
    /*
        {
          "jsonrpc": "2.0",
          "method": "JsonRpcArticulos.getArticulos",
          "params": [ par1, par2, ...],
          "id": <nº>
        }
    */
    $cuerpo = file_get_contents('php://input');
    $peticion = json_decode($cuerpo, true);

    // Comprobar que la petición es válida
    if( !$this->EsPeticionValida($peticion) ) {
      
    }



  }

  private function EsPeticionValida(array $peticion): bool {
    return isset($peticion['jsonrpc'], $peticion['method']) && 
      $peticion['jsonrpc'] === "2.0";
  }

  private function EnviarRespuesta(string $id, ?mixed $resultado, ?array $error ): void {
    $respuesta = ['jsonrpc' => "2.0", 'id' => $id];

    if( $resultado !== null ) {
      $respuesta['result'] = $resultado;
    }

    if( $error !== null ) {
      $respuesta['error'] = $error;
    }
    
    header("Content-type: application/json");
    echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
  }

}
