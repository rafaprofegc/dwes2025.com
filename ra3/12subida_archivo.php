<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

function gestionaArchivo(string $nombre, array $tiposPermitidos): int {
  echo <<<ARCHIVO
    <p>Nombre de archivo: {$_FILES[$nombre]['name']}<br>
    Tipo de archivo: {$_FILES[$nombre]['type']}<br>
    Tamaño (bytes): {$_FILES[$nombre]['size']}<br>
    Archivo temporal: {$_FILES[$nombre]['tmp_name']}<br>
    Código de error: {$_FILES[$nombre]['error']}<br>
  ARCHIVO;

  if( $_FILES[$nombre]['error'] === UPLOAD_ERR_FORM_SIZE ) {
    return 3;
  }

  if( $_FILES[$nombre]['error'] === UPLOAD_ERR_INI_SIZE ) {
    return 4;
  }

  if( $_FILES[$nombre]['error'] == UPLOAD_ERR_NO_FILE ) {
    return 5;
  }

  if( $_FILES[$nombre]['error'] === UPLOAD_ERR_OK ) {

    // Compruebo el límite del archivo
    $limite = $_POST["max_file_$nombre"];
    if( $_FILES[$nombre]['size'] > $limite ) {
      return 8;
    }
    // Ha llegado el archivo y comprobamos el tipo MIME
    $tipoMIMESubido = $_FILES[$nombre]['type'];
    $tipoMIMEFuncion = mime_content_type($_FILES[$nombre]['tmp_name']);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tipoMIMEInfo = finfo_file($finfo, $_FILES[$nombre]['tmp_name']);

    if( $tipoMIMESubido != $tipoMIMEFuncion || 
        $tipoMIMESubido != $tipoMIMEInfo ||
        !in_array($tipoMIMESubido, $tiposPermitidos) ) {
  
      return 6;
    }
  
    // Tenemos el archivo y lo guardamos
    $nombreArchivo = DIRECTORIO_SUBIDA . "/{$_FILES[$nombre]['name']}";
    if( !move_uploaded_file($_FILES[$nombre]['tmp_name'], $nombreArchivo) ) {
      return 7;
    }     
  }

  return 0;
}
/*
SUBIDA DE ARCHIVOS EN PHP
-------------------------

- Un formulario permite subir un archivo si:
  - Se añade al elmento <form> el atributo enctype=multipart/form-data
  - Hay al menos un elemento <input type="file" ... 

- Puede haber varios <input type="file"... y entonces se suben varios archivos.

- ¿Qué tamaño máximo puede tener un archivo subido?
  Siempre hay que poner un límite a la subida de archivos. 

  Las directivas relacionadas con la subida de archivos en php.ini:
  - file_uploads <bool> -> On, la subida está activada, Off la subida no está activada.
  - upload_max_filesize <int> -> Por defecto 2MB. Tamaño máximo de archivo subido.
  - max_file_uploads <int> -> Nº máximo de archivos que se pueden subir en una petición.
  - post_max_size <int> -> Tamaño máximo de la petición POST. Por defecto 8MB
  - upload_tmp_dir <dir> -> Directorio donde se almacenan de forma temporal los archivos subidos.
                            Por defecto: C:\TEMP (Windows), /tmp (Linux)

  Todos los parámetros anteriores se configuran editando el archivo php.ini. En este caso, el 
  cambio afecta a todas las aplicaciones que se ejecuten en el servidor y haría falta un
  reinicio de Apache.

  Además del límite de tamaño establecido con upload_max_filesize tengo otros límites:
  - Duro -> Directiva upload_max_filesize
  - Blando -> Usar un campo oculto de formulario llamado MAX_FILE_SIZE (en bytes)
  - Usuario -> El desarrollador puede establecer límites en campos ocultos. Viene bien
               cuando quiero poner un límite diferente para diferentes tipos de archivo
               PHP no lo controla, queda bajo responsabilidad del desarrollador.

- Cómo se procesa un archivo subido. Qué tiene que hacer el script que recibe los datos
  del formulario con el archivo.

  1º Disponemos del array superglobal $_FILES que almacena los archivos subidos.
  2º El usuario ha incluido en el formulario un archivo para subir. 
  3º El tamño del archivo está dentro de los límites. Lo controla PHP automáticamente
  4º El tamaño del archivo está dentro de los límites establecidos por el usuario. Se
     controla en el script PHP que recibe el archivo.
  5º El archivo es del tipo requerido. 

  Lo habitual es guardar el archivo subido. También, puede abrirse el archivo, acceder a su
  contenido y procesarlo sin llegar a guardarlo.

  Si vamos a guardar, necesitamos un directorio para guardarlo, el directorio de subida de
  archivos. En este caso, el usuario del SO que ejecuta Apache (www-data) tiene que tener
  permiso de escritura sobre el directorio de subida.

  El directorio de subida, tiene que existir cuando se guarde el archivo. Puede crearse en
  el mismo script que guardar el archivo, pero antes de guardarse. Si se crea el directorio
  de subida, el usuario del SO que ejecuta Apache (www-data) tiene que tener permiso de 
  escritura sobre el directorio padre.

  Enfoque del script:
    - Página autoprocesadad o autogenerada.
    - Se suben archivos de forma cíclica
    - Petición GET: Se presenta el formulario.
    - Petición POST:
      - Procesamos la subida de archivo.
      - Si hay algún error, se presenta la salida producida hasta el momento.
      - Si el directorio de subida no está creado, se crea.
      - Si no hay error, se guarda el archivo y se vuelve a presentar el formulario.
*/

// Formulario de enviar CV a una empresa. Se envia un archivo pdf con el CV 
// y un archivo jpg con la foto

// Límite para el archivo pdf: 256 KB
// Límite para el archivo jpg: 512 KB

define("DIRECTORIO_SUBIDA", $_SERVER['DOCUMENT_ROOT'] . "/archivos_cv");

inicioHtml("Subida de archivos", ["/estilos/general.css", "/estilos/formulario.css"]);

ob_start();

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  // Procesamos el formulario

  // 1º Comprobar si el directorio de subida está creado
  // Si no lo está se crea
  if( !file_exists(DIRECTORIO_SUBIDA) || !is_dir(DIRECTORIO_SUBIDA) ) {
    // El directorio de subida no existe. Se crea
    if( !mkdir(DIRECTORIO_SUBIDA, 0775) ) {     // 111 111 101
      echo "</h3>Error en la creación del directorio de subida</h3>";
      finHtml();
      ob_flush();
      exit(1);
    }   
  }

  // 2º Acceder al archivo subido.
  // Array superglobal $_FILES
  /*
    Contiene la información de los archivos que se han subido. Es un array asociativo
    donde la clave de indexación es el nombre del campo file del formulario

    <input type="file" name="archivo_cv" ...> -> Clave de indexación archivo_cv

    Cada elemento del array contiene información del archivo en otro array asociativo:

      - name -> Nombre original del archivo en el cliente.
      - type -> Tipo MIME del archivo
      - size -> Tamaño en bytes del archivo
      - tmp_name -> Nombre del archivo en el directorio temporal del servidor
      - error -> Código numérico indicando si hubo algún error, qué tipo de error, o si no lo hubo.
  */
  // Comprobamos si hay una clave para el archivo de subida
  /*
  if( !isset($_FILES['archivo_cv']) ) {
    echo "<h3>Error en la subida del archivo. El nombre del control de formulario no es válido</h3>";
    exit(2);
  }
  */

  // Existe la clave del archivo
  echo "<h3>Datos recibidos</h3>";
  echo "<p>Dni: {$_POST['dni']}<br>";
  echo "<p>Nombre: {$_POST['nombre']}</p>";
  
  $archivosSubidos = array_keys($_FILES);
  $tiposPermitidos = [
    'archivo_cv' => ["application/pdf"] ,
    'archivo_png' => ["image/png", "image/jpeg"]
  ];

  foreach($archivosSubidos as $archivo ) {
    $resultado = gestionaArchivo($archivo, $tiposPermitidos[$archivo]);
    if( !$resultado ) {
      echo "<h3>Archivo subido con éxito: {$_FILES[$archivo]['name']}</h3>";
    }
    else {
      ob_clean();
      switch( $resultado ) {
        case 3 : {
          echo "<h3>Error en la subida de archivo: Se ha sobrepasado el límite de formulario</h3>";
          break;
        }
        case 4: {
          echo "<h3>Error en la subida de archivo: Se ha sobrepasado el límite de php.ini</h3>";
          break;
        }
        case 5: {
          echo "<h3>Error en la subida de archivo: No se ha subido ningún archivo</h3>";
          break;
        }
        case 6: {
          echo "<h3>Error en la subida de archivo: El tipo mime no es admitido</h3>";
          break;
        }
        case 7: {
          echo "<h3>Error en la subida de archivo: No se ha guardado el archivo</h3>";
          break;
        }
        case 8: {
          echo "<h3>Error en la subida de archivo. Se sobrepasado el límite de usuario</h3>";
          break;
        }
      }
      echo "<p>Regresar al <a href='{$_SERVER['PHP_SELF']}'>formulario de subida</a></p>";
      finHtml();
      ob_flush();
      exit($resultado);
    }
  }
}

// Presenta el formulario
?>
<h3>Registro de CV de demandantes de empleo</h3>
<form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>">
  <!-- Límite blando de PHP. 1 MB -->
  <!-- <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?=1024*1024?>"> -->

  <input type="hidden" name="max_file_pdf" id="max_file_archivo_cv" value=<?=500*1024?>>
  <input type="hidden" name="max_file_png" id="max_file_archivo_png" value=<?=750*1024?>>
  <fieldset>
    <label for="dni">DNI</label>
    <input type="text" name="dni" id="dni" size="10">

    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" size="40">

    <label for="archivo_cv">Archivo CV (solo PDFs)</label>
    <input type="file" name="archivo_cv" id="archivo_cv" size="50" accept="application/pdf">

    <label for="archivo_png">Foto</label>
    <input type="file" name="archivo_png" id="archivo_png" size="50" accept="image/png">
  </fieldset>

  <input type="submit" name="operacion" id="operacion" value="Enviar">
</form>

<?php
finHtml();
ob_flush();
?>