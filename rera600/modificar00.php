<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']. "/vendor/autoload.php");

use rera600\orm\ORMAlumno;
use rera600\entidad\FilaAlumno;
use rera600\util\Html;

Html::inicioHtml("Examen RA6", ["/rera600/estilos/general.css", "/rera600/estilos/formulario.css"]);
try {

  $ormAlumno = new ORMAlumno();
  $operacion = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);

  if( $_SERVER['REQUEST_METHOD'] === "POST" && $operacion === "Enviar") {
    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);

    if ( empty($dni) || !preg_match("/[0-9]{7,8}[A-Z]/", $dni) ) {
      throw new Exception("El dni no es correcto");
    }

    // El dni es válido
    $alumno = $ormAlumno->buscar($dni);
    if( !$alumno ) {
      throw new Exception("El alumno con dni $dni no existe");
    }

?>
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
      <fieldset>
        <legend>Actualización de un alumno</legend>
        <label for="dni">Dni</label>
        <input type="text" name="dni" id="dni" value="<?= $alumno->dni ?>" size="10">

        <label for="curso">Curso</label>
        <select name="curso" id="curso" size="1">
          <option value="daw1" <?= $alumno->curso === 'daw1' ? "selected" : "" ?>>1º DAW</option>
          <option value="daw2" <?= $alumno->curso === 'daw2' ? "selected" : "" ?>>2º DAW</option>
          <option value="dam1" <?= $alumno->curso === 'dam1' ? "selected" : "" ?>>1º DAM</option>
          <option value="dam2" <?= $alumno->curso === 'dam2' ? "selected" : "" ?>>2º DAM</option>
        </select>

        <label for="grupo">Grupo</label>
        <div>
          <input type="radio" name="grupo" id="grupo" value="A"<?= $alumno->grupo === "A" ? "checked" : "" ?>>Mañana &nbsp;
          <input type="radio" name="grupo" id="grupo" value="B"<?= $alumno->grupo === "B" ? "checked" : "" ?>>Tarde
        </div>

        <label for="fecha_nacimiento">Fecha nacimiento</label>
        <input type="date" name="fecha_nacimiento" value="<?= $alumno->fecha_nacimiento->format($alumno::FORMATO_FECHA_MYSQL) ?>">

      </fieldset>

      <input type="submit" name="operacion" id="operacion" value="Actualizar">
    </form>
<?php
  }

  if( $_SERVER['REQUEST_METHOD'] === "POST" && $operacion === "Actualizar") {
    $dni = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);
    $curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_SPECIAL_CHARS);
    $grupo = filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS);
    $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_SPECIAL_CHARS);

    if ( empty($dni) || !preg_match("/[0-9]{7,8}[A-Z]/", $dni) ) {
      throw new Exception("El dni $dni no es correcto");
    }


    if( !in_array($curso, ['daw1', 'daw2', 'dam1', 'dam2']) ) {
      throw new Exception("El curso $curso no es válido");
    }

    if( $grupo !== 'A' && $grupo !== 'B') {
      throw new Exception("El grupo $grupo no es válido");
    }

    $fecha = DateTime::createFromFormat(FilaAlumno::FORMATO_FECHA_MYSQL, $fecha_nacimiento);
    if( !$fecha ) {
      throw new Exception("La fecha de nacimiento $fecha no es correcta");
    }

    $alumno = new FilaAlumno(['dni' => $dni, 'curso' => $curso, 'grupo' => $grupo,
                              'fecha_nacimiento' => $fecha]);

    if( $ormAlumno->actualizar($alumno) ) {
      echo "<h3>Alumno con dni $dni actualizado</h3>";
    }
  }
  
  echo "<a href='/rera600/index00.php?cerrar=ok'>Cierre de la sesión</a>";
}
catch(Exception $e) {
  Html::mostrarError($e);
  echo "<a href='/rera600/index00.php'>Volver al inicio</a>";
}
Html::finHtml();
?>
