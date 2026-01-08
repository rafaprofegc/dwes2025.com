<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

// Ejemplo de uso de un servicio redis
// Supuestamente se ha instalado la extensión redis
// y se ha cargado en el motor PHP
inicioHtml("Extensiones PHP", ["/estilos/general.css"]);

$servicioRedis = new Redis();
$servicioRedis->connect("127.0.0.1", 6379);

if( $servicioRedis->ping() ) {
  echo "<h3>El servicio Redis está en funcionamiento</h3>";
}

// Guardar un valor simple
$servicioRedis->set("usuario", "pepe");

// Recuperar un valor del servidor Redis
$usuario = $servicioRedis->get("usuario");
echo "<p>El usuario recuperado es $usuario</p>";

// Almacenar un valor temporal
$servicioRedis->setex("puerto_redis", 60*10, 6379);

// Recuperar un valor temporal
$puertoRedis = $servicioRedis->getex("puerto_redis");
echo "<p>El puerto redis es $puertoRedis</p>";

// Utilizar listas
$servicioRedis->lpush("lista", "Autenticar usuario");
$servicioRedis->lpush("lista", "Verificar JWT");
$servicioRedis->lpush("lista", "Crear la sesión");

$arrayTareas = $servicioRedis->lrange("lista", 0, -1);
echo "<p>Array de tareas:<ul>";
array_walk($arrayTareas, function($e) {
  echo "<li>$e</li>";
});
echo "</ul>";

$tarea = $servicioRedis->lpop("lista");
echo "<p>La última tarea añadida es $tarea</p>";

// Uso de hashes
$servicioRedis->hset("usuario:admin", "nombre", "juan");
$usuario = $servicioRedis->hgetall("usuario:admin");
echo "<p>El hash del usuario es {$usuario['nombre']}</p>";


finHtml();