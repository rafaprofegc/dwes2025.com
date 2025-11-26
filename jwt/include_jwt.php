<?php
function generarJWT(array $usuario): string {
  $cabecera = json_encode(['alg' => "HS256", 'typ' => "JWT"]);
  $payload = json_encode($usuario);

  // Convertir la cabecera y el payload en base64
  $cabecera64 = base64_encode($cabecera);
  $payload64 = base64_encode($payload);

  $cabecera64F = str_replace(["+","/","="], ["-", "_",""], $cabecera64);
  $payload64F = str_replace(["+", "/", "="], ["-","_", ""], $payload64);

  $clave = leerClave();


} 

function leerClave(): string {
  if( file_exists($_SERVER['DOCUMENT_ROOT']. "/jwt/clave.txt") ) {
    $pf = fopen($_SERVER['DOCUMENT_ROOT']. "/jwt/clave.txt", "r");
    $clave = fgets($pf);
  }
  else {
    $clave = "abc123";
  }
  return $clave;
}
?>