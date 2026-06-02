<?php
/*
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Actividad 9", ["/estilos/general.css", "/estilos/formulario.css", "/estilos/tabla.css"]);

$especialidades = [
  'inf' => "Informática",
  'fol' => "FOL",
  'ing' => "Inglés"
];

function Error(int $codigo): void {
  $errores = [
    1 => 'Datos no válidos',
  ];
}

if( $_SERVER['REQUEST_METHOD'] === "GET") {
?>
<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
  <fieldset>
    <legend>Datos del profesor</legend>
    <label for="nif">Nif</label>
    <input type="text" name="nif" id="nif" size="10" required>

    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" size="40">

    <label for="especialidad">Especialidad</label>
    <select name="especialidad" id="especialidad" size="1">
<?php
    foreach($especialidades as $clave => $especialidad) {
      echo "<option value='$clave'>$especialidad</option>\n";
    }
?>
    </select>

    <label for="archivo">Archivo</label>
    <input type="file" name="archivo" id="archivo" accept="text/plain">

  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Enviar">

</form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  $nif = filter_input(INPUT_POST, 'nif', FILTER_SANITIZE_SPECIAL_CHARS);
  $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
  $especialidad = filter_input(INPUT_POST, 'especialidad', FILTER_SANITIZE_SPECIAL_CHARS);

  $nif = preg_match("/[0-9]{7,8}[A-Z]/", strtoupper($nif)) ? $nif : false;
  $especialidad = array_key_exists($especialidad, $especialidades) ? $especialidad : false;

  if( !$nif || !$especialidad ) {
    Error(1);
  }

  // Procesar el archivo
  if( $_FILES['archivo']['error'] === UPLOAD_ERR_FORM_SIZE ) {
    Error(2);
  }

  if( $_FILES['archivo']['error'] === UPLOAD_ERR_NO_FILE ) {
    Error(3);
  }

  if( $_FILES['archivo']['error'] !== UPLOAD_ERR_OK ) {
    Error(4);
  }

  $tipoMimeValido = "text/plain";
  $tipoMimeSubido = $_FILES['archivo']['type'];
  $tipoMimeArchivo = mime_content_type($_FILES['archivo']['tmp_name']);
  if( $tipoMimeValido !== $tipoMimeSubido || $tipoMimeValido !== $tipoMimeArchivo ) {
    Error(5);
  }

  $pf = fopen($_FILES['archivo']['tmp_name'], "r");
  $primeraLinea = fgets($pf);
  if( $primeraLinea !== $nif ) {
    Error(6);
  }

  $lineas = [];
  while($linea = fgets($pf) ) {
    $lineas[] = $linea;
  }

  $indiceUltimo = count($lineas) - 1;
  $ultimaLinea = $lineas[$indiceUltimo];

  if( $ultimaLinea !== $especialidad ) {
    Error(6);
  }

  echo "<h3>Archivo válido</h3>";
  foreach($lineas as $linea) {
    echo $linea;
  }
}

finHtml();

?>