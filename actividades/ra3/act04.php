<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Actividad 04", ["/estilos/general.css", "/estilos/formulario.css"]);

define("CUOTA_INICIAL", 0.25);
define("CUOTA_FINAL", 0.25);

$modelos = [
  'mo'  => ['modelo' => "Monroy", 'pvp' => 20000],
  'mu'  => ['modelo' => "Muchopami", 'pvp' => 21000],
  'zv'  => ['modelo' => "Zapatoveloz", 'pvp' => 22000],
  'gu'  => ['modelo' => "Guperino", 'pvp' => 25500],
  'al'  => ['modelo' => "Alomejor", 'pvp' => 29750],
  'te'  => ['modelo' => "Telapegas", 'pvp' => 32550]
];

$motores = [
  'ga'  => ['motor' => "Gasolina", 'pvp' => 0],
  'di'  => ['motor' => "Diesel", 'pvp' => 2000],
  'hi'  => ['motor' => "Híbrido", 'pvp' => 5000],
  'el'  => ['motor' => "Eléctrico", 'pvp' => 10000]
];

$colores = [
  'gt'  => ['color' => "Gris triste", 'pvp' => 0],
  'rs'  => ['color' => "Rojo sangre", 'pvp' => 250],
  'rp'  => ['color' => "Rojo pasión", 'pvp' => 150],
  'an'  => ['color' => "Azul noche", 'pvp' => 175],
  'ca'  => ['color' => "Caramelo", 'pvp' => 300],
  'ma'  => ['color' => "Mango", 'pvp' => 275]
];

$extras = [
  'na'  => ['descripcion' => "Navegador GPS", 'pvp' => 500],
  'ca'  => ['descripcion' => "Calefacción de asientes", 'pvp' => 250],
  'at'  => ['descripcion' => "Antena aleta de tiburón", 'pvp' => 50],
  'sl'  => ['descripcion' => "Acceso y arranque sin llave", 'pvp' => 150],
  'ap'  => ['descripcion' => "Arranque en pendiente", 'pvp' => 200],
  'ci'  => ['descripcion' => "Cargador móvil inalámbrico", 'pvp' => 300],
  'cc'  => ['descripcion' => "Control de crucer", 'pvp' => 500],
  'am'  => ['descripcion' => "Detector ángulo muerto", 'pvp' => 350],
  'fl'  => ['descripcion' => "Faros led automáticos", 'pvp' => 400],
  'fe'  => ['descripcion' => "Frenada de emergencia", 'pvp' => 375]
];

$pago = [
  'co'    => "Contado",
  '24'    => "2 años",
  '60'    => "5 años",
  '120'   => "10 años"
];

if( $_SERVER['REQUEST_METHOD'] === "GET") {
?>
  <h2>Presupuesto compra vehículo</h2>
  <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?= 50 * 1024 ?>">
    <fieldset>
      <legend>Datos del vehículo</legend>
      
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" id="nombre" size="40" required>

      <label for="tlf">Teléfono</label>
      <input type="tel" name="tlf" id="tlf" size="10" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" size="40" required>

      <label for="modelo">Modelo</label>
      <select name="modelo" id="modelo" size="1">
<?php
      foreach( $modelos as $codigo => $modelo ) {
        echo "<option value='$codigo'>{$modelo['modelo']} - {$modelo['pvp']}€</option>";
      }
?>
      </select>

      <label for="motor">Motor</label>
      <div>
<?php
      
      foreach($motores as $codigo => $motor) {
        echo <<<RADIO
        <input type="radio" name="motor" id="radio_$codigo" value="$codigo">{$motor['motor']} - {$motor['pvp']}€&nbsp;
        RADIO;
        //echo "<input type='radio' name='radio' id='radio_$codigo' value='$codigo'>{$motor['motor']} - {$motor['pvp']}€&nbsp";

      }
?>
      </div>

      <label for="color">Color</label>
      <select name="color" id="color" size="1">
<?php
      foreach($colores as $codigo => $color) {
        $precio = intval($color['pvp']);
        echo "<option value='$codigo'>{$color['color']} - {$precio} €</option>";
      }
?>
      </select>

      <label for="extras">Extras</label>
      <div>
<?php
      foreach($extras as $codigo => $extra) {
        echo <<<EXTRA
        <input type="checkbox" name="extras[]" id="extras" value="$codigo">{$extra['descripcion']} - {$extra['pvp']} €<br>
        EXTRA;
      }
?>
      </div>

      <label for="pago">Forma de pago</label>
      <div>
<?php
      foreach($pago as $codigo => $descripcion) {
        echo <<<PAGO
        <input type="radio" name="pago" value="$codigo">$descripcion &nbsp;
        PAGO;
      }
?>
      </div>

      <div>
        <label for="nomina">Copia de la última nómina</label>
        <input type="file" name="nomina" id="nomina" accept="application/pdf">
      </div>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar">

  </form>
<?php
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {

  $filtros = [
    'nombre'      => FILTER_SANITIZE_SPECIAL_CHARS,
    'tlf'         => FILTER_VALIDATE_INT,
    'email'       => FILTER_VALIDATE_EMAIL,
    'modelo'      => FILTER_SANITIZE_SPECIAL_CHARS,
    'motor'       => FILTER_SANITIZE_SPECIAL_CHARS,
    'color'       => FILTER_SANITIZE_SPECIAL_CHARS,
    'extras'      => [ 'filter' => FILTER_SANITIZE_SPECIAL_CHARS, 'flags' => FILTER_REQUIRE_ARRAY],
    'pago'        => FILTER_SANITIZE_SPECIAL_CHARS
  ];

  $datosValidados = filter_input_array(INPUT_POST, $filtros);

  $datosValidados['modelo'] = array_key_exists($datosValidados['modelo'], $modelos) ? $datosValidados['modelo'] : false;
  $datosValidados['motor'] = array_key_exists($datosValidados['motor'], $motores) ? $datosValidados['motor'] : false;
  $datosValidados['color'] = array_key_exists($datosValidados['color'], $colores) ? $datosValidados['color'] : false;
  $datosValidados['extras'] = $datosValidados['extras'] ? 
                                array_filter($datosValidados['extras'], 
                                                fn($extra) => array_key_exists($extra, $extras)) :
                              [];

  /*
  $datosValidados['extras']                 Por cada elemento de $datosValidados['extras']
  ['na','ca','at','fl','fe','noexiste']     fn('na' => array_key_exists('na', $extras)) -> true
                                            fn('ca' => array_key_exists('ca', $extras)) -> true
                                            fn('at' => array_key_exists('at', $extras)) -> true
                                            ...                                         -> true
                                            fn('noexiste' => array_key_exists('noexiste', $extras)) -> false
  Resultado final: ['na','ca','at','fl','fe']
  */                                            
  $datosValidados['pago'] = array_key_exists($datosValidados['pago'], $pago) ? $datosValidados['pago'] : false;

  $datosObligatorios = ['nombre', 'tlf', 'email', 'modelo', 'motor', 'color', 'pago'];
  $datosRecibidos = array_filter($datosValidados);
  $diferencia = array_diff($datosObligatorios, array_keys($datosRecibidos));

  if( count($diferencia) === 0 ) {
    echo "<h3>Presupuesto del vehículo</h3>";
    $precioTotal = 0;
    $modelo = $datosValidados['modelo'];

    // Datos personales
    $descripcionVehiculo = "<p>Cliente: {$datosValidados['nombre']} - Tlf: {$datosValidados['tlf']}";
    $descripcionVehiculo.= " - Email: {$datosValidados['email']}</p>";

    // Modelo
    $descripcionVehiculo.= "<p>Vehículo modelo: " . $modelos[$modelo]['modelo'];
    $descripcionVehiculo.= ". PVP de salida: " . $modelos[$modelo]['pvp'] . "€<br>";
    $precioTotal += $modelos[$modelo]['pvp'];

    // Motor
    $descripcionVehiculo.= "Motor: " . $motores[$datosValidados['motor']]['motor'];
    $descripcionVehiculo.= ". Suplemento " . $motores[$datosValidados['motor']]['pvp'] . "€<br>";
    $precioTotal += $motores[$datosValidados['motor']]['pvp'];

    // Color
    $descripcionVehiculo.= "Color: " . $colores[$datosValidados['color']]['color'];
    $suplemento = $datosValidados['color'] === "gt" ? 0 : "Sin coste";
    $descripcionVehiculo.= ". Suplemento $suplemento €<br>";
    $precioTotal += $colores[$datosValidados['color']]['pvp'];

    // Extras
    if( !empty($datosValidados['extras']) ) {
      $descripcionVehiculo.= "Extras añadidos: <br>";
      foreach( $datosValidados['extras'] as $extra ) {
        $descripcionVehiculo.= $extras[$extra]['descripcion'] . " " . $extras[$extra]['pvp'] . "€<br>";
        $precioTotal += $extras[$extra]['pvp'];
      }
    }

    $descripcionVehiculo.= "Precio Total: $precioTotal €</p>";

    $descripcionVehiculo.= "<p>Forma de pago: {$pago[$datosValidados['pago']]}";
    if( $datosValidados['pago'] !== "co") {
      $cuotaInicial = $precioTotal * CUOTA_INICIAL;
      $cuotaFinal = $precioTotal * CUOTA_FINAL;
      $aFinanciar = $precioTotal - $cuotaInicial - $cuotaFinal;
      $plazo = intval($datosValidados['pago']);

      $descripcionVehiculo.= "<p>Plan de financiación:<br>";
      $descripcionVehiculo.= "Cuota Inicial: " . number_format($cuotaInicial, 2) . "€.";
      $descripcionVehiculo.= " Cuota Final " . number_format($cuotaFinal,2) . "€.<br>";
      $descripcionVehiculo.= "A financiar: " . number_format($aFinanciar,2) . "€ en $plazo meses<br>";
      $mensualidad = $aFinanciar / $plazo;
      $ahora = getDate();
      $mes = $ahora['mon'] + 1;
      $ayo = $ahora['year'];
      for( $i = 1; $i <= $plazo; $i++) {
        $resto = $aFinanciar - $i * $mensualidad;
        $descripcionVehiculo.= "Mes $mes Año $ayo: " . number_format($mensualidad,2) . "€. Resto: " . number_format($resto,2) . "€<br>";
        if( $mes === 12 ) {
          $mes = 1;
          $ayo++;
        }
        else {
          $mes++;
        }
      }
    }

    echo $descripcionVehiculo;

    // Subida del fichero
    // Tamaño admisible
    if( $_FILES["nomina"]['error'] === UPLOAD_ERR_FORM_SIZE ) {
      echo "<h3>Error en la subida de la nómina. Se ha sobrepasado el tamaño máximo</h3>";
      finHtml();
      exit();
    }

    // Comprobar que se haya subido
    if( $_FILES['nomina']['error'] !== UPLOAD_ERR_OK ) {
      echo "<h3>Error en la subida de la nómina. No se ha subido ningún archivo</h3>";
      finHtml();
      exit();
    }

    // Tipos mime
    $tiposMimeAdmitidos = ["application/pdf", "text/plain"];
    $tipoMimeSubido = $_FILES['nomina']['type'];
    $tipoMimeArchivo = mime_content_type($_FILES['nomina']['tmp_name']);
    if( $tipoMimeSubido !== $tipoMimeArchivo || !in_array($tipoMimeArchivo, $tiposMimeAdmitidos) ) {
      echo "<h3>Error en la subida de la nómina. NO se admite el tipo $tipoMimeSubido</h3>";
      finHtml();
      exit();
    }

    // Crear carpeta de subida si no está creada
    define("CARPETA_SUBIDA", $_SERVER['DOCUMENT_ROOT'] . "/actividades/ra3/act4/nomina/");
    if( !file_exists(CARPETA_SUBIDA) || !is_dir(CARPETA_SUBIDA) ) {
      if( mkdir(CARPETA_SUBIDA, 0755, true) ) {
        $extension = $tipoMimeArchivo === "application/pdf" ? ".pdf" : ".txt";
        $nombre = CARPETA_SUBIDA . $datosValidados['email'] . $extension;
        if( move_uploaded_file($_FILES['nomina']['tmp_name'], $nombre) ) {
          echo "<h3>Archivo subido con éxito</h3>";
        }
        else {
          echo "<h3>No se ha guardado el archivo subido</h3>";
        }
      }
      else {
        echo "</h3>No se ha podido crear la carpeta de subida</h3>";
      }
    }
    
  }
  else {
    echo "<h3>Error, los datos no han sido validados</h3>";
  }
  echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver a hacer otro presupuesto</a><p>";
}
finHtml();

?>
