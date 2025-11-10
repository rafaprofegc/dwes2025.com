<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

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

  2025-12-10
  // 2º Guardar el archivo subido

  // 3º Calcular el presupuesto

  // Si hay error en la validación, se envían mensajes de error
  // Si hay error en la subida de archivo, se envían mensajes de error
}



finHtml();
ob_flush();
?>