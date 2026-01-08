<?php
/*
  Fechas PHP
  ----------

  Para gestionar fechas en PHP
  - Clase DateTime        -> Fecha y hora
  - Clase DateInterval    -> Gestionar un intervalo de tiempo
  - Clase DatePeriod      -> Gestionar un periodo de tiempo.

*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Fechas en PHP", ["/estilos/general.css", "/estilos/formulario.css"]);

echo "<header>Fechas en PHP</header>";
echo "<h3>Clase DateTime</h3>";

// Fecha y hora del sistema en UTC | GMT | Zulú
$ahora = new DateTime();
// La cadena de formato para fecha y hora 
// según DateTimeInterface::format
// URL: https://www.php.net/manual/es/datetime.format.php

$formatoFechaHora = "d/m/Y G:i:s";
echo "<p>Fecha y hora actual UTC: " . $ahora->format($formatoFechaHora);

// date_default_timezone_set("Europe/Madrid");
// ini_set("date.timezone", "Europe/Madrid");
$zonaHorariaEuropea = new DateTimeZone("Europe/Madrid");
$ahora = new DateTime("now", $zonaHorariaEuropea);

echo "<p>Fecha y hora actual local: " . $ahora->format($formatoFechaHora);

// Indicar una fecha y hora de forma concreta en DateTime
$fechaCumple = new DateTime("1994-08-14");
echo "<p>Fecha y hora de mi cumpleaños: " . $fechaCumple->format($formatoFechaHora);

$fechaCumple = new DateTime("2005/12/24 21:15:25");
echo "<p>Fecha y hora de mi cumpleaños: " . $fechaCumple->format($formatoFechaHora);

$hoyEn2028 = new DateTime("2024");
echo "<p>Fecha y hora de hoy en 2028: " . $hoyEn2028->format($formatoFechaHora);

$sanValentin = new DateTime("2/24");
echo "<p>Fecha y hora de San Valentín: " . $sanValentin->format($formatoFechaHora);

$fechaCumple = new DateTime("2005/12/24 211525");
echo "<p>Fecha y hora de mi cumpleaños: " . $fechaCumple->format($formatoFechaHora);
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
  <fieldset>
    <label for="fecha">Fecha</label>
    <input type="date" name="fecha" id="fecha">

    <label for="fechaTexto">Fecha Texto (d/m/yyyy)</label>
    <input type="text" name="fechaTexto" id="fechaTexto" size="10" placeholder="d/m/yyyy">

    <label for="fechaHora">Fecha y Hora</label>
    <input type="datetime-local" name="fechaHora" id="fechaHora">
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>

<?php
if( $_SERVER['REQUEST_METHOD'] === "POST") {
  try {
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_SPECIAL_CHARS);
    echo "<p>Fecha recibida (fecha): $fecha</p>";
    $fechaDate = new DateTime($fecha, $zonaHorariaEuropea);
    echo "<p>Fecha convertida (fecha): " . $fechaDate->format($formatoFechaHora);

    $fecha = filter_input(INPUT_POST, 'fechaTexto', FILTER_SANITIZE_SPECIAL_CHARS);
    $fechaTexto = new DateTime($fecha, $zonaHorariaEuropea);
    echo "<p>Fecha convertida (texto): " . $fechaTexto->format($formatoFechaHora);

    $fechaHL = filter_input(INPUT_POST, 'fechaHora', FILTER_SANITIZE_SPECIAL_CHARS);
    $fechaHoraLocal = new DateTime($fechaHL, $zonaHorariaEuropea);
    echo "<p>Fecha Hora Local (texto): " . $fechaHoraLocal->format($formatoFechaHora);

  }
  catch(DateMalformedStringException $dmse) {
    echo "<h3>Excepción de fecha</h3>";
    echo "<p>Mensaje: " . $dmse->getMessage() . "</p>";
    echo "<p>Código: " . $dmse->getCode() . "</p>";
  }
}

/*
  Método DateTime::createFromFormat() 

*/
$formatoAceptable = "j/n/Y";
$fechaCadena = DateTime::createFromFormat($formatoAceptable, $fecha);
echo "<p>Fecha Hora Local (texto): " . $fechaCadena->format($formatoFechaHora);

finHtml();
?>
