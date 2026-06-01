<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use exra600_2\util\Html;
use exra600_2\orm\ORMRegistro;

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

      session_destroy();
      session_start();
    }

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
  
  if( $_SERVER['REQUEST_METHOD'] === "POST") {

    if( isset($_POST['operacion']) && $_POST['operacion'] === 'IA') {
      $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
      if( !$email ) throw new Exception("El email enviado no es correcto");

      // Obtengo las filas de los registro del usuario
      $ormRegistro = new ORMRegistro();
      $registros = $ormRegistro->listar($email);
    }
    

    if( isset($_POST['operacion']) && $_POST['operacion'] === "Insertar") {

    }
  }
}
catch( Exception $e ) {
  Html::mostrarError($e);
  echo "<p><a href='{$_SERVER['PHP_SELF']}'>Volver al inicio</a></p>";
}

Html::finHtml();                     

?>