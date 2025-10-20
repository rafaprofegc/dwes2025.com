<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

inicioHtml("Formulario", ["/estilos/general.css", "/estilos/formulario.css"]);
/*
5.1 Array EGPCS (Environment, GET, POST, Cookies, Server)
 Arrays asociativos superglobales (accesibles desde cualquier script y cualquier función)
 Sus valores son mantenidos automáticamente por PHP, por tanto son de solo lectura
 - $_ENV -> Variables de entorno. Depende del sistema operativo.
 - $_GET -> Datos enviados en una petición GET. 
 - $_POST -> Datos enviados en una petición POST.
 - $_COOKIE -> Cookies entre el cliente y el servidor.
 - $_REQUEST -> Contiene los datos en $_GET, $_POST y $_COOKIE
 - $_SERVER ->  Información del propio servidor web. 

 5.3 Métodos HTTP
 - GET -> Envía los datos en la URL de la petición
 - POST -> Envía los datos en el cuerpo de la petición.

 Si la petición es GET
  http://loquesea.com/url/script.php?campo1=valor1&campo2=valor2&...&campoN=valorN

  Formato de petición GET
      GET /ra3/09respuesta.php HTTP/1.1    -> Línea de petición
      Host: dwes.com                        -> Encabezados
      Accept-language: es
      Accept-content: text/html
      User-Agent: Mozilla ....
      .... 
                                            -> Línea en blanco


 Si la petición es POST los datos van en el cuerpo. Formato de petición POST
       
      POST /ra3/09respuesta.php HTTP/1.1    -> Línea de petición
      Host: dwes.com                        -> Encabezados
      Accept-language: es
      Accept-content: text/html
      User-Agent: Mozilla ....
      .... 
                                            -> Línea en blanco
      name=Juan+Perez&email=juan@loquesea.com  -> Cuerpo de la petición.

  Las peticiones GEt son idempotentes -> Dos peticiones iguales producen la misma respuesta. Por tanto,
  los navegadores pueden cachear una respuesta a una petición GET y, si se hace la misma petición,
  se devuelve la respuesta cacheada sin enviar la petición al servidor.

  Las peticiones POST no son idempotentes y por tanto no deben cachearse.

 */

?>
<h1>Proceso de Formularios</h1>
<h2>Diferencias entre GET y POST</h2>
<form method="POST" action="/ra3/09respuesta.php">
  <fieldset>
    <legend>Solicitud de empleo</legend>
    <label for="nombre">Nombre completo</label>
    <input type="text" name="nombre" id="nombre" size="50" 
      placeholder="Escribe tu nombre completo">

    <label for="email">Email</label>
    <input type="email" name="email" id="email" size="30">

    <label for="clave">Clave</label>
    <input type="password" name="clave" id="clave" size="10">

    <label for="linkedin">Likedin</label>
    <input type="url" name="linkedin" id="linkedin" size="50"> 

  </fieldset>

  <input type="submit" name="operacion" value="Enviar">

</form>

<?php
finHtml();
?>