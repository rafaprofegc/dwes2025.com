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
define("DIRECTORIO", $_SERVER['DOCUMENT_ROOT'] . "/archivos_ra4");

function calcularTamaño(int $tamaño): string {
  $unidades = ["bytes", "KB", "MB", "GB", "TB"];
  $indice = 0;
  while( $tamaño > 1024 ) {
    $tamaño = intval($tamaño / 1024);
    $indice++;
  }

  return "$tamaño {$unidades[$indice]}";

}

if( $_SERVER['REQUEST_METHOD'] === "GET" && !isset($_GET['archivo']) ) {
  // Presentamos la lista de archivos
  inicioHtml("Ejemplo de encabezados. Descarga de contenido de diferente tipo",
  ["/estilos/general.css", "/estilos/tabla.css"]);

  $archivos = scandir(DIRECTORIO);
  if( $archivos ) {
    echo <<<TABLA
    <table>
      <thead>
        <tr><th>Archivo</th><th>Tipo</th><th>Tamaño</th><th></th><th></th></tr>
      </thead>
      <tbody>
    TABLA;

    /*
    foreach($archivos as $archivo) {

    }
    */
    array_walk( $archivos , function($archivo) {
      if( is_file(DIRECTORIO . "/$archivo" ) ) {
        echo "<tr>";

        //       -  $_SERVER['PHP_SELF']      - Query String
        // href="/ra4/01diferente_contenido.php?archivo=architecture.png
        echo <<<ENLACE
          <td>
            <a href="{$_SERVER['PHP_SELF']}?archivo=$archivo">$archivo</a>
          </td>
        ENLACE;

        $tipoMime = mime_content_type(DIRECTORIO . "/$archivo");
        echo "<td>$tipoMime</td>";

        $tamaño = filesize(DIRECTORIO . "/$archivo");
        $tamañoVisto = calcularTamaño($tamaño);
        echo "<td>$tamañoVisto</td>";

        // Botones para descargar y ver
        echo <<<BOTON1
          <td>
            <form method="POST" action="{$_SERVER['PHP_SELF']}">
              <input type="hidden" name="archivo" id="archivo" value="$archivo">
              <input type="submit" value="Descarga">
            </form>
          </td>
        BOTON1;
        
        echo <<<BOTON2
          <td>
            <form method="GET" action="{$_SERVER['PHP_SELF']}">
              <input type="hidden" name="archivo" id="archivo" value="$archivo">
              <input type="submit" value="Ver">
            </form>
          </td>
        BOTON2;
      }
    });

    echo "</tbody></table>";

  }
  finHtml();
}

if( $_SERVER['REQUEST_METHOD'] === "POST" || 
    $_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['archivo'])) {
  
  // Descarga del archivo

  $tipoEntrada = isset($_POST['archivo']) ? INPUT_POST : INPUT_GET;
  $archivo = filter_input($tipoEntrada, 'archivo', FILTER_SANITIZE_SPECIAL_CHARS);

  if( file_exists(DIRECTORIO . "/$archivo") ) {
    $tipoMime = mime_content_type(DIRECTORIO. "/$archivo");
    $tamaño = filesize(DIRECTORIO . "/$archivo");

    header("Content-type: $tipoMime");
    header("Content-length: $tamaño");
    if( isset($_POST['archivo']) ) {
      header("Content-disposition: attachment; filename=$archivo");
    }
    
    readfile(DIRECTORIO . "/$archivo");
    exit(0);
  }

  // Establecer la cabecera con el tipo mime



  /*
  HTTP/1.1 200 Ok 
  ...
  Content-type: tipo_mime
  ...

  Cuerpo: Archivo que se descarga
  */
}

?>