<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

define("TAMAÑO_MAXIMO", 200 * 1024);

function ErrorValidacion(array $datosSinValidar ): void {
  $mensajesValidacion = [
    'nombre'  => "El nombre no puede estar en blanco",
    'fecha'   => "La fecha no es válida, tiene que ser del futuro",
    'email'   => "El formato del email no es correcto",
    'destino' => "El destino elegido no existe",
    'compania' => "La compañía aérea no existe",
    'hotel'   => "El hotel no es válido",
    'personas' => "El número de personas está entre 5 y 10",
    'dias'    => "Los días tienen que ser 5, 10 o 15"
  ];


  ob_clean();
  echo "<h3>Errores de validación de datos</h3>";
  echo "<h4>Los siguientes datos faltan o son erróneos</h4>";
  echo "<ul>";
  foreach($datosSinValidar as $dato ) {
    echo "<li>{$mensajesValidacion[$dato]}</li>";
  }
  echo "</ul>";
  finHtml();
  ob_flush();
  exit();
}

function ErrorArchivo(string $mensaje): void {
  ob_clean();
  echo "<h3>Error en la subida del archivo</h3>";
  echo "<p>$mensaje</p>";
  finHtml();
  ob_flush();
  exit();
}

inicioHtml("Actividad 05", ["/estilos/general.css", "/estilos/formulario.css"]);
ob_start();

$destinos = [
  'pa'    => ['nombre' => "París", 'precio' => 100],
  'lo'    => ['nombre' => "Londres", 'precio' => 120],
  'es'    => ['nombre' => "Estocolmo", 'precio' => 200],
  'ed'    => ['nombre' => "Edinburgo", 'precio' => 175],
  'pr'    => ['nombre' => "Praga", 'precio' => 125],
  'vi'    => ['nombre' => "Viena", 'precio' => 150]
];

$compañias = [
  'ma'    => ['nombre' => "MyAir", 'precio' => 0],
  'af'    => ['nombre' => "AirFly", 'precio' => 50],
  'vc'    => ['nombre' => "Vuela conmigo", 'precio' => 75],
  'aa'    => ['nombre' => "Apedales Air", 'precio' => 150]
];

$hoteles = [
  '3e'    => ['clasif' => "3*", 'precio' => 0],
  '4e'    => ['clasif' => "4*", 'precio' => 40],
  '5e'    => ['clasif' => "5*", 'precio' => 100]
];

$extras = [
  'vg'  => ['titulo' => "Visita guiada", 'precio' => 200],
  'bt'  => ['titulo' => "Bus turístico", 'precio' => 30],
  '2m'  => ['titulo' => "2ª maleta facturada", 'precio' => 20],
  'sv'  => ['titulo' => "Seguro de viaje", 'precio' => 30]
];

echo "<header>Reserva de viaje</header>";
if( $_SERVER['REQUEST_METHOD'] === "GET" ) {
?>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?=200*1024?>">
  <fieldset>
    <legend>Datos del viaje</legend>
    <label for="nombre">Nombre completo</label>
    <input type="text" name="nombre" id="nombre" size="40">

    <label for="fecha">Fecha</label>
    <input type="date" name="fecha" id="fecha">

    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="20">

    <label for="destino">Destino</label>
    <select name="destino" id="destino" size="1">
<?php
    foreach($destinos as $clave => $destino) {
      echo "<option value='$clave'>{$destino['nombre']} - {$destino['precio']}€ /p/d</option>";
    }
?>
    </select>

    <label for="compania">Compañía aérea</label>
    <select name="compania" id="compania" size="1">
<?php
    array_walk($compañias, function($co, $clave) {
      echo "<option value='$clave'>{$co['nombre']} - {$co['precio']}€/p</option>";
    });
?>
    </select>

    <label for="hotel">Hotel</label>
    <select name="hotel" id="hotel" size="1">
<?php
    foreach($hoteles as $clave => $hotel) {
      echo "<option value='$clave'>{$hotel['clasif']}";
      echo $hotel['precio'] ? " ({$hotel['precio']}€/p/d)" : " Incluido";
      echo "</option>";
    }
?>
    </select>

    <label for="desayuno">Desayuno incluido</label>
    <span><input type="checkbox" name="desayuno" id="desayuno"> (20€/p/d)</span>

    <label for="personas">Nº de personas</label>
    <input type="text" size="5" name="personas" id="personas">

    <label for="dias">Dias</label>
    <div>
      <input type="radio" name="dias" id="dias1" value="5">5&nbsp;
      <input type="radio" name="dias" id="dias2" value="10">10&nbsp;
      <input type="radio" name="dias" id="dias3" value="15">15
    </div>

    <label for="extras">Extras</label>
    <div>
<?php
      $indice=1;
      foreach( $extras as $clave => $extra) {
        echo "<input type='checkbox' name='extras[]' id='extras{$indice}'";
        echo " value='$clave'>{$extra['titulo']} ({$extra['precio']}€)<br>";
        $indice++;
      }
?> 

    </div>

    <label for="dni">Copia del DNI</label>
    <input type="file" name="dni" id="dni" accept="image/*">

  </fieldset>

  <input type="submit" name="operacion" id="operacion" value="Enviar">

</form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {

  // 1º Sanear y validar los datos
  $filtros = [
    'nombre'    => FILTER_SANITIZE_SPECIAL_CHARS,
    'fecha'     => FILTER_SANITIZE_SPECIAL_CHARS,
    'email'     => FILTER_VALIDATE_EMAIL,
    'destino'   => FILTER_SANITIZE_SPECIAL_CHARS,
    'compania'  => FILTER_SANITIZE_SPECIAL_CHARS,
    'hotel'     => FILTER_SANITIZE_SPECIAL_CHARS,
    'desayuno'  => FILTER_VALIDATE_BOOL,
    'personas'  => ['filter' => FILTER_VALIDATE_INT, 
                    'options' => ['min_range' => 5, 'max_range'=> 10]],
    'dias'      => FILTER_VALIDATE_INT,
    'extras'    => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS, 'flags' => FILTER_REQUIRE_ARRAY]
  ];

  $datos = filter_input_array(INPUT_POST, $filtros);

  // Validación por lógica de negocio
  
  // Fecha. Funciones strtotime() y date()
  $fecha = strtotime($datos['fecha']);
  $datos['fecha'] = $fecha && $fecha > time() ? date("l, d F  Y", $fecha) : false;

  // Destino, compañía y hotel
  $datos['destino'] = array_key_exists($datos['destino'], $destinos) ? $datos['destino'] : false;
  $datos['compania'] = array_key_exists($datos['compania'], $compañias) ? $datos['compania'] : false;
  $datos['hotel'] = array_key_exists($datos['hotel'], $hoteles) ? $datos['hotel'] : false;

  // Dias
  $diasPermitidos = [ 5, 10, 15 ];
  $datos['dias'] = in_array($datos['dias'], $diasPermitidos) ? $datos['dias'] : false;

  // Extras
  $datos['extras'] = array_filter($datos['extras'], fn($extra) => array_key_exists($extra, $extras));

  // Datos obligatorios
  $datosObligatorios = [ "nombre", "fecha", "email", "destino", "compania", "hotel", "personas", "dias"];
  $datosPresentes = array_filter($datos);
  $noEstanPresentes = array_diff($datosObligatorios, array_keys($datosPresentes));
  if( $noEstanPresentes ) {
    ErrorValidacion($noEstanPresentes);
  }  

  // 2º Guardar el archivo subido
  if( $_FILES['dni']['error'] == UPLOAD_ERR_FORM_SIZE ) {
    Error("El archivo supera el tamaño máximo de ". TAMAÑO_MAXIMO . " bytes" );
  }

  /*
  Se guarda en el directorio /viajes/<grupo>, siendo <grupo> una
  codificación formada con: <fecha>.<email>.

  El nombre del archivo será el nombre de la persona responsable (sustituir
  espacios en blanco por guiones bajos).
  */

  if( $_FILES['dni']['error'] == UPLOAD_ERR_OK ) {

    // Comprobar tipo MIME
    $tiposMimeValidos = [
      'image/jpeg'  => 'jpg',
      'image/png'   => 'png',
      'image/webp'  => 'webp'
    ];

    $tipoMimeSubido = $_FILES['dni']['type'];
    $tipoMimeFuncion = mime_content_type($_FILES['dni']['tmp_name']);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tipoMimeInfo = finfo_file($finfo, $_FILES['dni']['tmp_name']);

    if( $tipoMimeSubido != $tipoMimeFuncion || $tipoMimeSubido != $tipoMimeInfo ||
        !in_array($tipoMimeSubido, $tiposMimeValidos) ) {
      ErrorArchivo("El tipo mime no es válido");
    }

    $grupo = date("d-m-Y", $fecha) . ".$email";

    if( !file_exists($_SERVER['DOCUMENT_ROOT'] . "/viajes/$grupo") ||
        !is_dir($_SERVER['DOCUMENT_ROOT'] . "/viajes/$grupo") ) {
      if( !mkdir($_SERVER['DOCUMENT_ROOT'] . "/viajes/$grupo", 0755, true) ) {
        ErrorArchivo("No se ha podido crear el directorio /viajes/$grupo");
      }     
    }

    // Guardo el archivo
    $nombre = str_replace(" ", "_", $datos['nombre']) . $tiposMimeValidos[$tipoMimeSubido];
    $pathCompleto = $_SERVER['DOCUMENT_ROOT'] . "/viajes/$grupo/$nombre";
    if( !move_uploaded_file($_FILES['dni']['tmp_name'], $pathCompleto) ) {
      ErrorArchivo("El archivo no ha podido guardarse");
    }
  }

  // 3º Calcular el presupuesto
  echo "<h3>Cálculo del coste del viaje</h3>";
  echo <<<TABLA
    <table>
      <thead>
        <tr>
          <th>Persona Responsable</th>
          <th>Fecha del viaje</th>
          <th>Destino</th>
          <th>Compañía</th>
          <th>Hotel</th>
          <th>Con desayuno</th>
          <th>Nº personas</th>
          <th>Dias</th>
          <th>Extras</th>
        </tr>
      </thead>
      <tbody>
        <tr>
    TABLA;
    echo "<td>{$datos['nombre']}<br>{$datos['email']}</td>";
    echo "<td>" . date("d/m/Y", $datos['fecha']) . "</td>";

    echo "<td>{$destinos[$datos['destino']]['nombre']}</td>";
    
    $precioDestino = $datos['dias'] * $datos['personas'] * $destinos[$datos['destino']]['precio'];
    $presupuesto = "Precio en destino: {$destinos[$datos['destino']]['nombre']}: $precioDestino €<br>";

    echo "<td>{$compania[$datos['compania']]['nombre']}</td>";
    $precioVuelo = $datos['personas'] * $compania[$datos['compania']]['precio'] * 2;
    $presupuesto.= "Precio de los vuelos: $precioVuelo<br>";

  // Si hay error en la validación, se envían mensajes de error
  // Si hay error en la subida de archivo, se envían mensajes de error
}



finHtml();
ob_flush();
?>