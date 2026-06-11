<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use exra600_2\util\Html;
use exra600_2\orm\ORMRegistro;
use exra600_2\entidad\Registro;

$actividades = [
   'gns3' => "El simulador de red GNS3",
   'ftp'  => "Configuración cortafuegos para FTP",
   'dock' => "Despliegue rápido con Docker"
];

Html::inicioHtml("Examen RA6", 
                  ["/exra600_2/estilos/general.css", 
                   "/exra600_2/estilos/tabla.css",
                   "/exra600_2/estilos/formulario.css"]);



try {

  if( $_SERVER['REQUEST_METHOD'] === "GET" ) {
    if( isset($_GET['operacion']) && $_GET['operacion'] === 'cerrar') {
      $nombre = session_name();
      $par = session_get_cookie_params();
      setcookie($nombre, time() - 100, $par['path'], $par['domain'], $par['secure'], $par['httponly']);
      unset($_SESSION);

      session_destroy();
      session_start();
    }

    if( !isset($_SESSION['email'])) {
  ?>
    <h3>Identificador del usuario</h3>
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
      <input type="hidden" name="operacion" id="operacion" value="IA">
      <fieldset>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" size="10" required>
      </fieldset>
      <input type="submit" value="Abrir sesión">
    </form>
  <?php
    }
  }
  
  if( $_SERVER['REQUEST_METHOD'] === "POST") {
    $ormRegistro = new ORMRegistro();

    if( isset($_POST['operacion']) && htmlspecialchars($_POST['operacion']) === 'IA') {
      $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
      if( !$email ) throw new Exception("El email enviado no es correcto");

      $_SESSION['email'] = $email;
    }

    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

    if( isset($_POST['operacion']) && $_POST['operacion'] === "IR") {
      // Sanear y validar los datos
      $datosValidados['actividad'] = filter_input(INPUT_POST, 'actividad', FILTER_SANITIZE_SPECIAL_CHARS);
      $datosValidados['actividad'] = in_array($datosValidados['actividad'], array_keys($actividades)) ? $datosValidados['actividad'] : false;
      $datosValidados['fecha_inscripcion'] = filter_input(INPUT_POST, 'fecha_inscripcion', FILTER_SANITIZE_SPECIAL_CHARS);
      $datosValidados['fecha_inscripcion'] = new DateTime($datosValidados['fecha_inscripcion']);

      $datosValidados['id'] = null;
      $datosValidados['email'] = $email;

      if(!$datosValidados['email'] || !$datosValidados['actividad']) {
        throw new Exception("Los datos del registro de actividad no son válidos");
      }

      $registro = new Registro($datosValidados);
      if( $ormRegistro->insertar($registro) ) {
        echo <<<RESULTADO
        <h3>Registro insertado</h3>
        RESULTADO;
      }
    }

    // Obtengo las filas de los registro del usuario
    $registros = $ormRegistro->listar($email);
    echo <<<TABLA
      <table>
        <thead>
          <tr>
            <th>Id</th>
            <th>Email</th>
            <th>Fecha</th>
            <th>Actividad</th>
          </tr>
        </thead>
        <tbody>
    TABLA;
    foreach($registros as $registro) {
      echo <<<FILA
        <tr>
          <td>{$registro->id}</td>
          <td>{$registro->email}</td>
          <td>{$registro->fecha_inscripcion->format($registro::FECHA_ESPANOL)}</td>
          <td>{$actividades[$registro->actividad]}</td>
        </tr>
      FILA;
    }
    echo <<<TABLA
      </tbody>
    </table>
    TABLA;
?>
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
      <input type='hidden' name="operacion" id="operacion" value="IR">
      <fieldset>
        <legend>Insertar el registro de una actividad</legend>
        <label for="fecha_inscripcion">Fecha inscripcion</label>
        <input type="date" name="fecha_inscripcion" id="fecha_inscripcion" required>

        <label for="actividad">Actividad</label>
        <select name="actividad" id="actividad" size="1">
<?php
        foreach($actividades as $codigo => $actividad) {
          echo "<option value='$codigo'>$actividad</option>";
        }
?>
        </select>

      </fieldset>
      <input type="submit" value="Insertar actividad">
    </form>
<?php

  }
}
catch( Exception $e ) {
  Html::mostrarError($e);
  echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver al inicio</a></p>";
}

Html::finHtml();                     

?>