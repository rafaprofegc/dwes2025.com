<?php
return $configBD = [
  'dsn'       => "mysql:host=cpd.iesgrancapitan.org;port=9990;dbname=tiendaol;charset=utf8mb4",
  'usuario'   => "usuario",
  'clave'     => "usuario",
  'opciones'  => [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false 
  ]
];

?>