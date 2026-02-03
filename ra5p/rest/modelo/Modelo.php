<?php
namespace ra5p\rest\modelo;

interface Modelo {
  public function procesaPeticion(array $parametros): mixed; 
}