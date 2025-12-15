<?php
interface GestionSeguridad {

  public function autenticar(string $token): bool;
  public function cambiarToken(string $tokenActual, string $tokenNuevo): bool;
  
}

?>