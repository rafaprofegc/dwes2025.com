<?php
namespace ra7p\rpc\controlador;

use Exception;

class JsonRpcControlador {
  public const string EN_MODELO = "\\ra7p\\rpc\\modelo\\";

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
    $id = isset($peticion['id']) ? $peticion['id'] : null;
    $parametros = isset($peticion['params']) ? $peticion['params'] : [];

    try {
      // Comprobar que la petición es válida
      if( !$this->EsPeticionValida($peticion) )
        throw new \Exception("El formato de petición no es válido");

      // En este punto, la petición es válida. 
      // Verificar si el método es correcto
      [$nombreModelo, $metodo] = $this->TraduceMetodo($peticion['method']);
    }
    catch( \Exception $e ) {
      $this->EnviarRespuesta($id, null, 
        ['code' => -32600, 'message' => "Invalid Request", 'data' => $e->getMessage()]);
    }
    
    // La petición es válida y el método también
    try {
      $claseModelo = self::EN_MODELO . $nombreModelo;
      if( !class_exists($claseModelo) ) 
        throw new \Exception("La clase modelo no está definida");

      if( !method_exists($claseModelo, $metodo) )
        throw new \Exception("El método de la clase $claseModelo no está definido");
    }
    catch(\Exception $e) {
      $this->EnviarRespuesta($id, null, 
        ['code' => -32600, 'message' => "Invalid Request", 'data' => $e->getMessage()]);
    }

    try {
      // Existe la clase y su método. Se invocan
      $modelo = new $claseModelo();
      $resultado = call_user_func_array([$modelo, $metodo], $parametros);
      $this->EnviarRespuesta($id, $resultado, null);
    }
    catch( \Exception $e ) {
      $this->EnviarRespuesta($id, null, 
        ['code' => -32603, 'message' => "Internal error", 'data' => $e->getMessage()]);
    } 
  }

  private function EsPeticionValida(array $peticion): bool {
    return isset($peticion['jsonrpc'], $peticion['method']) && 
      $peticion['jsonrpc'] === "2.0";
  }

  private function EnviarRespuesta(?string $id, mixed $resultado, ?array $error ): void {
    $respuesta = ['jsonrpc' => "2.0", 'id' => $id];

    if( $resultado !== null ) {
      $respuesta['result'] = $resultado;
    }

    if( $error !== null ) {
      $respuesta['error'] = $error;
    }
    
    header("Content-type: application/json");
    echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    exit;
  }

  private function TraduceMetodo(string $metodo): array {
    // Verificar que el método tiene la forma Clase.metodo
    $claseYMetodo = explode(".", $metodo);

    if( count($claseYMetodo) !== 2 ) {
      throw new \Exception('Formato de método inválido. Utilizar: Modelo.método');
    }
    
    return $claseYMetodo;
  }
}
