<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Para probar el composer autoload
use ra5\util\Html;
use EN\BD\conexion\ConectarBD;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

Html::inicioHtml("Ejemplo de uso de biblioteca", ["/estilos/general.css"]);


// 1º Creamos un logger
$logger = new Logger("app");

// 2º Generamos el archivo log
$archivoLog = new StreamHandler(__DIR__ . "/app.log", Level::Debug);

// 3º Formato de línea
$formato = "[%datetime%] - %channel% %level_name%: %message% %context%\n";
$formatter = new LineFormatter($formato, 'd/m/Y H:i:s', true, true);

// 4º Asignar el formato de línea al archivo log
$archivoLog->setFormatter($formatter);

// 5º Asignar el archivo log al objeto log anterior
$logger->pushHandler($archivoLog);

// Se ha terminado de configurar el log con un archivo y podemos usarlo

// 6º Añadir líneas al log
$logger->debug("Estos es una línea de depuración de la aplicación");
$logger->info("Usuario autenticado con éxito: [usuario: pepe]");
$logger->warning("Advertencia: el espacio en disco ha llegado al 90%");
$logger->error("Error en la conexión con la BD. Error: 500 usuario no existe");
$logger->critical("Error crítico de la aplicación");

echo "<h2>Archivo log generado</h2>";

$log = fopen(__DIR__ . "/app.log", "r");
while( $linea = fgets($log)) {
  echo $linea . "<br>";
}
fclose($log);

// Probando el autoload del composer
$cbd = new ConectarBD("usuario", "usuario");
if( $cbd) echo "<h3>Ha instanciado bien ConectarBD</h3>";

Html::finHtml();
?>