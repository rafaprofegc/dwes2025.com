<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

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

?>