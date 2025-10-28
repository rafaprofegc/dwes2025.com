<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

function Error(int $codigoError): void {
  $mensajesError = [ 
    0 => "No hay error",
    1 => "Petición no válida",
    2 => "No se ha subido ningún archivo",
    3 => "El formato del archivo no es el esperado",
    4 => "No se ha podido abrir el archivo",
    7 => "" 
  ];  

  ob_clean();
  echo "<h2>Error de la aplicación</h2>";
  echo "<p>Código de error: $codigoError<br>";
  echo "Mensaje de error: {$mensajesError[$codigoError]}</p>";
  finHtml();

  exit($codigoError);
}

function comprobarTipo(string $archivo, string $tipoMIME): bool {
  $tipoMIMESubido = $_FILES[$archivo]['type'];

  $tipoMIMEFuncion = mime_content_type($_FILES[$archivo]['tmp_name']);

  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $tipoMIMEInfo = finfo_file($finfo, $_FILES[$archivo]['tmp_name']);

  return $tipoMIME === $tipoMIMESubido && $tipoMIME === $tipoMIMEFuncion 
    && $tipoMIME === $tipoMIMEInfo;

}

function mostrarDatos(): void {
  if( $_FILES['archivo_csv']['error'] != UPLOAD_ERR_OK ) {
    Error(2);
  }

  if( !comprobarTipo("archivo_csv", "text/csv") ) {
    Error(3);
  }

  // Podemos abrir el archivo y procesarlo
  $puntero = fopen($_FILES['archivo_csv']['tmp_name'], "r");
  if( !$puntero ) {
    Error(4);
  }

  $filaCabecera = isset($_POST['fila_cabecera']);

  echo <<<TABLA
    <table border="1">
      <caption>Datos encontrados en el archivo</caption>
      <thead>
  TABLA;

  if( $filaCabecera ) {
    $linea = fgetcsv($puntero);
    echo "<tr>";
    foreach( $linea as $campo ) {
      echo "<th>{$campo}</th>";
    }
    // array_walk($linea, function($x) { echo "<th>$x</th>"; });

    echo "</tr>";   
  }
  else {
    echo "<tr>";
    for( $i = 1; $i <= 6; $i++ ) {
      echo "<th>Campo {$i}</th>";
    }

    // array_walk([1, 2, 3, 4, 5, 6], function($x) { echo "<th>Campo {$x}</th>";});
    echo "</tr>";
  }

  echo "</thead>";
  echo "<tbody>";
  
  while( true ) {
    $linea = fgetcsv($puntero);
    if( feof($puntero) ) break;

    echo "<tr>";
    foreach($linea as $dato) {
      echo "<td>$dato</td>";
    }
    echo "</tr>";
    
  }
  echo "</tbody>";
  echo "</table>";
  fclose($puntero);
}

inicioHtml("Subida de archivo y proceso", ["/estilos/general.css", "/estilos/formulario.css"]);

ob_start();

echo "<header>Importación de datos</header>";

if( $_SERVER['REQUEST_METHOD'] == "POST") {
  // Procesamos el formulario
  if( $_POST['operacion'] == "Mostrar") {
    mostrarDatos();
  }
  else if( $_POST['operacion'] == "Guardar") {
    //guardarArchivo();
  }
  else {
    Error(1);
  }
  echo "<p>Si quiere procesar un nuevo archivo <a href='{$_SERVER['PHP_SELF']}'>pinche aquí</a></p>";
}

if( $_SERVER['REQUEST_METHOD'] == "GET" ) { ?>
  <form method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <fieldset>
      <legend>Introduzca el archivo con los datos a importar</legend>

      <label>Primera fila de cabecera</label>
      <input type="checkbox" name="fila_cabecera" id="fila_cabecera" value="Si" checked>

      <label>Archivo (CSV)</label>
      <input type="file" name="archivo_csv" id="archivo_csv" accept="text/csv">
    </fieldset>
    <div>
      <button type="submit" name="operacion" id="op1" value="Mostrar">Mostrar los datos importados</button>
      <button type="submit" name="operacion" id="op2" value="Guardar">Guardar el archivo</button>
    </div>
  </form>
<?php
}

finHtml();
ob_flush();
?>