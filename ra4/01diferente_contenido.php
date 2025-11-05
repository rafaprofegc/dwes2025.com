<?php
/*
Encabecezados o Cabeceras HTTP
------------------------------
  Ejemplo de uso de los encabezados HTTP. 

  Queremos enviar al usuario contenido diferente a text/html. 

  Encabezado que vamos a usar Content-type: <tipo_mime>

  Listar en formato tabla el contenido de un directorio ra4/archivos. El usuario
  puede descargar el archivo que quiera.

  No hay descarga directa. El servidor recibe la petición con el archivo a descargar,
  lee el contenido del archivo y lo pone en la salida, previamente ha colocado
  el encabezado Content-type: <tipo_mime>. 

*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

// Si se envía una petición GET sin el nombre de archivo -> Se presenta la lista de archivos.
// Si se envía una petición POST o una petición GET con el nombre de archivo -> Se descarga el archivo

inicioHtml("Ejemplo de encabezados. Descarga de contenido de diferente tipo");

if( $_SERVER['REQUEST_METHOD'] === "GET" && !isset($_GET['archivo']) ) {
  // Presentamos la lista de archivos

}

if( $_SERVER['REQUEST_METHOD'] === "POST" || 
    $_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['archivo'])) {
  // Descarga del archivo

  // Establecer la cabecera con el tipo mime
      
  /*
  HTTP/1.1 200 Ok 
  ...
  Content-type: tipo_mime
  ...

  Cuerpo: Archivo que se descarga

}

?>