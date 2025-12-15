<?php
require_once("Emp.php");
require_once("Cliente.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

function probarInterfaces(GestionSeguridad $gs, string $token) {
  if( $gs->autenticar($token) ) {
    echo "<h3>Autenticación con éxito {$gs->nombre}</h3>";
  }
  else {
    echo "<h3>Error en la autenticación</h3>";
  }  
}

$emp = new Emp("30A", "juan123", "Juan Gómez");
$cli = new Cliente("pepe@gmail.com", "1234", "Pepe Gómez");

if( $emp->cambiarToken("juan123", "123Juan") ) {
  echo "<h3>El empleado {$emp->nombre} ha cambiado su clave</h3>";
}
else {
  echo "<h3>Error en el cambio de clabe del empleado {$emp->nombre}</h3>";
}

inicioHtml("Interfaces", ["/estilos/general.css"]);
probarInterfaces($emp, "juan123");
probarInterfaces($cli, "1234");

finHtml();
?>