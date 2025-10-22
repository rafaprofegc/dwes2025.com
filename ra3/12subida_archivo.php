<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
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

define("DIRECTORIO_SUBIDA", "/archivos_cv");

inicioHtml("Subida de archivos", ["/estilos/general.css", "/estilos/formulario.css"]);

if( $_SERVER['REQUEST_METHOD'] === "POST" ) {
  // Procesamos el formulario


}

// Presenta el formulario
?>
<h3>Registro de CV de demandantes de empleo</h3>
<form method="POST" enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>">
  <!-- Límite blando de PHP. 1 MB -->
  <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?=1024*1024?>">
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
?>