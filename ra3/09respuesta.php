<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Proceso del formulario", ["/estilos/general.css"]);

echo "<h1>Proceso de la solicitud de empleo</h1>";

// Identificar el método de la petición.
if( $_SERVER['REQUEST_METHOD'] === "POST") {
  echo "<h3>Datos recibidos desde la petición</h3>";
  echo "<p>Nombre: {$_POST['nombre']}<br>";
  echo "Email: {$_POST['email']}<br>";
  echo "Clave: {$_POST['clave']}<br>";
  echo "URL perfil: {$_POST['linkedin']}<br>";
  echo "Fecha disponibilidad: {$_POST['fecha_disponibilidad']}<br>";
  echo "Hora entrevista: {$_POST['hora_entrevista']}<br>";
  echo "Salario: {$_POST['salario']}<br>";

  $areas = $_POST['areas'];
  $areas_interes = 
    ['ds' => "Desarrollo Web",
     'dg' => "Diseño gráfico",
     'mk' => "Marketing",
     'rh' => "Recursos Humanos" ];
  $areas_usuario = "";
  foreach($areas as $area ) {
    $areas_usuario.= $areas_interes[$area] . " - ";
  }   
  //echo "Áreas de interés: " . implode(", ", $areas) . "<br>";
  echo "Áreas de interés: $areas_usuario<br>";

  echo "Modalidad de contrato: {$_POST['modalidad']}<br>";
  echo "Tipo de contrato: {$_POST['tipo_contrato']}<br>";

  echo "Habilidades: " . implode(", ", $_POST['habilidades']) . "<br>";

  echo "Comentarios: {$_POST['comentarios']}<br>";
  echo "Operación: {$_POST['operacion']}<br>";

}
else {
  echo <<<ERROR
  <h3>Error en la petición</h3>
  <p>Solo puede acceder aquí a través del formulario en
  <a href="/ra3/09formulario.php">este enlace</a></p>
  ERROR;
}

finHtml();
?>