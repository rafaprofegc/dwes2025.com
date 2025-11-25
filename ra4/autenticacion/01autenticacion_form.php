<?php
session_start();

$usuarios = [
  'juan@loquesea.com' => ['nombre' => "Juan Gómez", 
                          'perfil' => "Admin",
                          'clave' => password_hash("abc123", PASSWORD_DEFAULT)],
  'maria@loquesea.com' => ['nombre' => "María García", 
                          'perfil' => "Usuario",
                          'clave' => password_hash("123abc", PASSWORD_DEFAULT)]                          
];


?>