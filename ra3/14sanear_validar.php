<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

/* SANEAMIENTO Y VALIDACIÓN DE DATOS DE FORMULARIO
   -----------------------------------------------

   Sanear -> Consiste en eliminar de las entradas en $_POST o $_GET cualquier
             caráctar no deseado y que puede provocar problemas: < < & " 

   Validar -> Garantizar que el dato en una entrda $_POST o $_GET es conforme a
              formato y lógica de negocio. Ej. email -> usuario@loquesea.com
                                               telefono -> dígitos numéricos

                                               fechaCita -> Fecha (formato)
                                                         -> Del futuro 

  Funciones de PHP para sanear y validar:
  - htmlspecialchars() -> Solo para sanear quitando los carácteres especiales HTML
  - filter_input() y filter_input_array() -> Sanear y validar. La validación se refiere
    solamente al formato, no a la lógica de negocio. Se aplican a $_POST, $_GET, $_COOKIE
    $_SERVER
  - filter_var() y filter_var_array() -> Igual que la anteriores pero se aplica a cualquier
    variable.                                                   
*/

function presentarDatos(array $datos, string $titulo ): void {
   echo "<h3>Datos saneados con $titulo</h3>";
    echo "<p>";
    foreach($datos as $clave => $valor) {
      if( gettype($valor) == "array") $valor = implode(", ", $valor);
      echo "{$clave}: {$valor}<br>";
    }
    echo "</p>";
}

inicioHtml("Sanear y validar datos de formulario",
  ["/estilos/general.css", "/estilos/formulario.css"]);
ob_start();

$patologias = [ 'os' => "Osteoporosis",
                'di' => "Diabetes",
                'co' => "Colesterol",
                'an' => "Anemia",
                'ar' => "Arterioesclerosis" 
              ];

echo "<header>Suscripción al portal de salud</header>";

if( $_SERVER['REQUEST_METHOD'] == "POST") {

    // Saneamiento 1ª Forma: función htmlspecialchars()
    $datos['dni'] = htmlspecialchars($_POST['dni']);
    $datos['nombre'] = htmlspecialchars($_POST['nombre']);
    $datos['email'] = htmlspecialchars($_POST['email']);
    $datos['clave'] = $_POST['clave']; // La clave no se sanea ni valida.
    $datos['suscripcion'] = isset($_POST['suscripcion']) ? htmlspecialchars($_POST['suscripcion']) : false;
    $datos['sitio'] = htmlspecialchars($_POST['sitio']);
    $datos['peso'] = htmlspecialchars($_POST['peso']);
    $datos['edad'] = htmlspecialchars($_POST['edad']);
    $datos['patologias'] = array_map(fn($x) => htmlspecialchars($x), $_POST['patologias']);
    $datos['comentarios'] = htmlspecialchars($_POST['comentarios']);

    presentarDatos($datos, "htmlspecialchars()");

    // Saneamiento 2ª Forma: función filter_input()
    $datos['dni'] = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['nombre'] = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $datos['clave'] = filter_input(INPUT_POST, 'clave');  // Por defecto, FILTER_DEFAULT
    $datos['suscripcion'] = filter_input(INPUT_POST, 'suscripcion', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['sitio'] = filter_input(INPUT_POST, 'sitio', FILTER_SANITIZE_URL);

    /* Uso de los flags:
     - Si es uno, se indica directamente con su constante predefinida. Ej
       filter_input(INPUT_POST, 'peso', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

     - Si es más de uno, puedo usar un array. Ej
       filter_input(INPUT_POST, 'peso', FILTER_SANITIZE_NUMBER_FLOAT, 
         [FILTER_FLAG_ALLOW_FRACTION, FILTER_FLAG_ALLOW_SCIENTIFIC]);

     - Si es más de uno, puedo indicarlos separados por el operador |
       filter_input(INPUT_POST, 'peso', FILTER_SANITIZE_NUMBER_FLOAT, 
        FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC);
    */    

    $datos['peso'] = filter_input(INPUT_POST, 'peso', FILTER_SANITIZE_NUMBER_FLOAT, 
      FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC);

    $datos['edad'] = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_NUMBER_INT);
    $datos['patologias'] = filter_input(INPUT_POST, 'patologias', 
      FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);

    $datos['comentarios'] = filter_input(INPUT_POST, 'comentarios', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['operacion'] = filter_input(INPUT_POST, 'operacion', FILTER_SANITIZE_SPECIAL_CHARS);

  presentarDatos($datos, "filter_input()");

    // Saneamiento 3ª Forma: función filter_input_array()
    echo "<p><a href='{$_SERVER['PHP_SELF']}'>Introducir los datos</a> de nuevo</p>";
    $opcionesFiltrado = [
      'dni'         => FILTER_SANITIZE_SPECIAL_CHARS,
      'nombre'      => FILTER_SANITIZE_SPECIAL_CHARS,
      'email'       => FILTER_SANITIZE_EMAIL,
      'clave'       => FILTER_DEFAULT,
      'suscripcion' => FILTER_SANITIZE_SPECIAL_CHARS,
      'sitio'       => FILTER_SANITIZE_URL,
      'peso'        => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT,
                        'flags'  => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC],
      'edad'        => FILTER_SANITIZE_NUMBER_INT,
      'patologias'  => ['filter' => FILTER_SANITIZE_SPECIAL_CHARS,
                        'flags'  => FILTER_REQUIRE_ARRAY],
      'comentarios' => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    $datos = filter_input_array(INPUT_POST, $opcionesFiltrado);

    presentarDatos($datos, "filter_input_array()");

    // Validación 1ª Forma: función filter_input(), filter_var()
    /*
    Los datos que no tienen formato o tienen un formato del cual no disponemos
    de filtro, solo se sanean. 
    El DNI tiene formato, pero no tiene un filtro. Se sanea y después se valida
    con lógica de negocio 
    */
    $datos['dni'] = filter_input(INPUT_POST, 'dni', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['nombre'] = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS);
    $datos['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $datos['clave'] = $_POST['clave'];
    // $datos['suscripcion'] = isset($_POST['suscripcion']);
    $datos['suscripcion'] = filter_input(INPUT_POST, 'suscripcion', FILTER_VALIDATE_BOOLEAN );
    $datos['sitio'] = filter_input(INPUT_POST, 'sitio', FILTER_VALIDATE_URL);
    $datos['peso'] = filter_input(INPUT_POST, 'peso', 
      FILTER_VALIDATE_FLOAT, ['flags' => FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC,
                            'options' => ['min_range' => 40.0, 'max_range' => 300.0]]);
    $datos['edad'] = filter_input(INPUT_POST, 'edad', 
      FILTER_VALIDATE_INT, ['options' => ['min_range' => 18, 'max_range' => 65, 'default' => 18]]);
    $datos['patologias'] = filter_input(INPUT_POST, 'patologias', 
      FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
    $datos['comentarios'] = filter_input(INPUT_POST, 'comentarios', FILTER_SANITIZE_SPECIAL_CHARS);

    presentarDatos($datos, "filter_input() (validación)");




}

if( $_SERVER['REQUEST_METHOD'] == "GET") { ?>
  <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
    <fieldset>
      <legend>Introducir los datos</legend>
      <label for="dni">Dni</label>
      <input type="text" name="dni" id="dni" size="10"> <!-- pattern="[0-9]{7,8}[A-Z]" -->

      <label for="nombre">Nombre completo</label>
      <input type="text" name="nombre" id="nombre" size="40">

      <label for="email">Email</label>
      <input type="text" name="email" id="email" size="30">

      <label for="clave">Clave</label>
      <input type="password" name="clave" id="clave" size="10">

      <label for="suscripcion">Suscripción</label>
      <input type="checkbox" name="suscripcion" id="suscripcion" value="true">

      <label for="sitio">Sitio web personal</label>
      <input type="text" name="sitio" id="sitio" size="50">

      <label for="peso">Peso</label>
      <input type="text" name="peso" id="peso" size="4">

      <label for="edad">Edad (entre 18 y 65)</label>
      <input type="text" name="edad" id="edad" size="4">

      <label for="patologias">Patologías previas</label>
      <select name="patologias[]" id="patologias" size="5" multiple>
<?php
      foreach( $patologias as $clave => $valor) {
        echo "<option value='{$clave}'>{$valor}</option>\n";
      }
?>
      </select>
      <label for="comentarios">Comentarios</label>
      <textarea name="comentarios" id="comentarios" rows="4" cols="30"></textarea>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Enviar">
  </form>

<?php
}


finHtml();
ob_flush();
?>