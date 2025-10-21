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

  <fieldset>
    <legend>Detalles del trabajo</legend>
    <label for="fecha_disponible">Fecha disponibilidad</label>
    <input type="date" name="fecha_disponibilidad" id="fecha_disponibilidad">

    <label for="hora_entrevista">Hora</label>
    <input type="time" name="hora_entrevista" id="hora_entrevista">

    <label for="salario">Salario</label>
    <input type="number" name="salario" id="salario" min="1000">
  </fieldset>

  <fieldset>
    <legend>Preferencias del puesto</legend>
    <label for="areas">Áreas de trabajo preferidas</label>
    <div>
      <input type="checkbox" name="areas[]" id="area1" value="ds">Desarrollo Web<br>
      <input type="checkbox" name="areas[]" id="area2" value="dg">Diseño gráfico<br>
      <input type="checkbox" name="areas[]" id="area3" value="mk">Marketing<br>
      <input type="checkbox" name="areas[]" id="area4" value="rh">Recursos humanos
    </div>

    <label for="modalidad">Modalidad</label>
    <div>
      <input type="radio" name="modalidad" id="modalidad1" value="Presencial">Presencial<br>
      <input type="radio" name="modalidad" id="modalidad2" value="Teletrabajo">Teletrabajo<br>
      <input type="radio" name="modalidad" id="modalidad3" value="Mixto">Mixto
    </div>

    <label for="tipo_contrato">Tipo de contrato</label>
    <select name="tipo_contrato" id="tipo_contrato">
      <option value="">Elija un tipo de contrato</option>
      <option value="tc">Tiempo completo</option>
      <option value="mj">Media jornada</option>
      <option value="fr">Freelance</option>
    </select>

    <label for="habilidades">Habilidades</label>
    <select name="habilidades[]" multiple size="5" id="habilidades">
      <option value="js">JavaScript</option>
      <option value="py">Python</option>
      <option value="uxui">Diseño UX/UI</option>
      <option value="seo">SEO/SEM</option>
      <option value="gp">Gestión de proyectos</option>
    </select>

    <label for="comentarios">Comentarios</label>
    <textarea name="comentarios" rows="4" 
      cols="40" id="comentarios" placeholder="Cuéntame más de ti"></textarea>


  </fieldset>

  <div>
    <input type="submit" name="operacion" value="Enviar">
    <input type="reset" value="Empezar otra vez">
  </div>
  

</form>

<?php
finHtml();
?>