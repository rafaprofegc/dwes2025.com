<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

// 1º Creamos un logger
$logger = new Logger("app");

// 2º Genero un archivo para el log
$archivoLog = new StreamHandler(__DIR__ . "/app.log", Level::Debug);

// 3º Configurar el formato de la línea para el archivo log
$formato = "[%datetime%] - %channel% %level_name%: %message% %context%\n";
$formatter = new LineFormatter($formato);

// 4º Asignar el formato archivo
$archivoLog->setFormatter($formatter);

// 5º Asignar el archivo log al logger
$logger->pushHandler($archivoLog);

// 6º Añadir líneas al log
$logger->debug("Esto es un mensaje de depuración");
$logger->info("El usuario se ha autenticado", ["usuario" => "pepe"]);
$logger->warning("Advertencia: el espacio ocupado en el disco ha llegado al 90%");
$logger->error("Error en la conexión a la base de datos", 
  ["error" => 500, "mensaje" => "El usuario no existe en la BD"]);
$logger->critical("¡Fallo crítico del sistema!");

$logger->close();

inicioHtml("Ejemplo de uso de biblioteca PHP", ["/estilos/general.css"]);
// Abrimos el archivo log y vemos su contenido
$log = fopen(__DIR__ . "/app.log", "r");
while( $linea = fgets($log)){
  echo $linea . "<br>";
}
fclose($log);
finHtml();
?>