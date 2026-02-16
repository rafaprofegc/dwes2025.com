<?php
namespace ra7p\restful\modelo;

use ra6p\orm\modelo\ORMBase;
USE ra6p\orm\entidad\Entidad;
use ra7p\restful\error\ErrorServicio;

abstract class ModeloServicio {
  protected ORMBase $claseORM;

  public abstract function ValidacionDatos(): ?Entidad;

  public function getAll(): array {
    try {
      $filas = $this->claseORM->getAll();
      return $filas;
    }
    catch( \Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
  }

  public function get(mixed $id): Entidad {
    try {
      $fila = $this->claseORM->get($id);
    }
    catch(\Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
    if( $fila === null ) throw new ErrorServicio(404, ErrorServicio::ERROR_404, "Not Found");
    return $fila;
  }

  public function insert(): bool {
    try {
      $entidad = $this->ValidacionDatos();
      if( $entidad ) return $this->claseORM->insert($entidad);

      throw new ErrorServicio(422, ErrorServicio::ERROR_422, "Unproccessable Entity");
    }
    catch( \PDOException $pdoe ) {
      $error = $pdoe->getCode();
      $errorBD = $
      switch( $error ) {
        case '23000' : {
          // Error por violación de la integridad
          throw new ErrorServicio(409, $pdoe->getCode(), $pdoe->getMessage());
        }
      }
    }
    catch( \Exception $e) {

    }
    
  }

  public function update(mixed $id): bool {

  }

  public function delete(mixed $id): bool {

  }
}