<?php
/*
Crear un script PHP con un formulario con:
  - Nombre del profesor
  - Nota mínima para aprobar
  - Archivo CSV con las notas
  
  Tiene que visualizar: 
  - El profesor, la nota mínima para el aprobado
  - Una tabla con los alumnos aprobados.
  - Una tabla con los alumnos suspensos.
  - La nota máxima, la nota mínima, la nota media, cuántos alumnos han aprobado y cuántos han suspendido.
*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

function Error(int $codigo): void {
  $errores = [
    1 => 'El nombre del profesor es obligatorio',
    2 => 'No se ha subido un archivo',
    3 => 'El tipo del archivo tiene que ser CSV',
    4 => 'Error en la apertura del archivo'
  ];

  ob_clean();
  echo "<h3>Error de la aplicación</h3>";
  echo "<h4>$errores[$codigo]</h4>";
  echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver a intentarlo</a></p>";
  finHtml();
  ob_flush();
  exit($codigo);
}

function CabeceraTabla(string $titulo) {
  echo <<<TABLA
  <h3>$titulo</h3>
  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Email</th>
        <th>Nota</th>
      </tr>
    </thead>
    <tbody>
  TABLA;
}

function FinTabla() {
  echo <<<TABLA
    </tbody>
  </table>
  TABLA;
}

inicioHtml("Actividad 9", ["/estilos/general.css", "/estilos/formulario.css", "/estilos/tabla.css"]);
ob_start();

if( $_SERVER['REQUEST_METHOD'] === "GET") {
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
  <fieldset>
    <legend>Subida de las notas</legend>
    <label for="profesor">Profesor</label>
    <input type="text" name="profesor" id="profesor" size="40" required>

    <label for="nota">Nota para el aprobado</label>
    <input type="text" name="nota" id="nota" size="4" required>

    <label for="archivo">Archivo con las notas</label>
    <input type="file" name="archivo" id="archivo">
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Enviar">

</form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  // Sanear y validar los datos
  $profesor = filter_input(INPUT_POST, 'profesor', FILTER_SANITIZE_SPECIAL_CHARS);

  $notaMinima = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_FLOAT, 
    ['options' => ['min_range' => 0, 'max_range' => 10, 'default' => 5] ]);

  if( !$profesor ) {
    Error(1);
  }

  echo "<h3>Cálculo de las notas</h3>";
  echo "<h4>Profesor: $profesor. Nota mínima: $notaMinima</h4>";

  if( $_FILES['archivo']['error'] !== UPLOAD_ERR_OK ) {
    Error(2);
  }

  // Comprobamos el tipo del archivo
  $tipoMimeValido = "text/csv";
  $tipoMimeSubido = $_FILES['archivo']['type'];
  $tipoMimeArchivo = mime_content_type($_FILES['archivo']['tmp_name']);
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $tipoMimeInfo = finfo_file($finfo, $_FILES['archivo']['tmp_name']);

  if( $tipoMimeValido !== $tipoMimeSubido || $tipoMimeValido !== $tipoMimeArchivo 
      || $tipoMimeArchivo !== $tipoMimeInfo ) {
    Error(3);
  }

  // Procesamos el archivo
  $pf = fopen($_FILES['archivo']['tmp_name'], "r");
  if( !$pf ) {
    Error(4);
  }

  // Desechar la línea de cabecera
  fgetcsv($pf);

  $aprobados = [];
  $suspensos = [];
  while( $fila = fgetcsv($pf) ) {
    // La nota está en el elemento 7
    if( floatval($fila[7]) < $notaMinima ) {
      $suspensos[] = $fila;
    }
    else {
      $aprobados[] = $fila;
    }
  }

  $notaMin = $suspensos[0][7];
  $notaMax = $aprobados[0][7];
  $media = 0;
  CabeceraTabla("Aprobados");
  foreach( $aprobados as $aprobado ) {
    echo <<<FILA
      <tr>  
        <td>{$aprobado[1]} {$aprobado[0]}</td>
        <td>{$aprobado[2]}</td>
        <td>{$aprobado[7]}</td>
      </tr>
    FILA;
    $media += floatval($aprobado[7]);
    $notaMax = $notaMax > $aprobado[7] ? $notaMax : $aprobado[7];
  }
  FinTabla();

  CabeceraTabla("Suspensos");
  foreach( $suspensos as $suspenso) {
    echo <<<FILA
      <tr>  
        <td>{$suspenso[1]} {$suspenso[0]}</td>
        <td>{$suspenso[2]}</td>
        <td>{$suspenso[7]}</td>
      </tr>
    FILA;
    $media += floatval($suspenso[7]);
    $notaMin = $notaMin < $suspenso[7] ? $notaMin : $suspenso[7];
  }
  FinTabla();

  $media = $media / ( count($suspensos) + count($aprobados));
  // Presentar los datos
  echo "<p>Nota mínima: $notaMin<br>";
  echo "Nota máxima: $notaMax<br>";
  echo "Nota media: $media<br>";
  echo "Alumnos suspensos: " . count($suspensos) . "<br>";
  echo "Alumnos aprobados: " . count($aprobados) . "</p>";
}
finHtml();
ob_flush();

?>

