<?php
namespace exra500\controlador;

use exra500\vista\VistaError00;

class Controlador00 {
  protected const string VISTA_ERROR = "VistaError00";
  protected array $peticiones;

  protected const string EN_MODELOS = "\\exra500\\modelo\\";
  protected const string EN_VISTAS = "\\exra500\\vista\\";

  public function __construct() {
    $this->peticiones['buscarPedido'] = [
      'modelo' => "ModeloPedido00", 'vista' => "VistaPedido00"
    ];
    $this->gestionarPeticion();
  }

  public function gestionarPeticion(): void {
    try {
      $peticion = filter_input(INPUT_POST, 'idp', FILTER_SANITIZE_SPECIAL_CHARS);
      if( !$peticion ) throw new \Exception("La petición no está contemplada", 1000);
      if( !array_key_exists($peticion, $this->peticiones) ) 
          throw new \Exception("La petición $peticion es errónea", 1001);
        
      $claseModelo = self::EN_MODELOS . $this->peticiones[$peticion]['modelo'];
      $claseVista = self::EN_VISTAS . $this->peticiones[$peticion]['vista'];

      if( !class_exists($claseModelo) ) throw new \Exception("La clase modelo no existe", 1002);
      if( !class_exists($claseVista) ) throw new \Exception("La clase vista no existe", 1003);

      $objetoModelo = new $claseModelo();
      $objetoVista = new $claseVista();

      $pedido = $objetoModelo->procesaPeticion();
      $objetoVista->enviarSalida($pedido);

    }
    catch(\Exception $e) {
      VistaError00::muestraError($e);
    }
  }
}
?>