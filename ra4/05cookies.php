<?php
/*
COOKIES
-------

- Dato que el servidor almacena en el disco del cliente.
- Cualquier información alfanumérica de hasta 4 KB puede ser una cookie
- Usos: 
    - Seguimiento de la sesión.
    - Mantener datos entre peticiones.
    - Detalles de inicio de sesión: usuario, ... (ya no es tan frecuente, si se hace es cifrado)

- Solo se pueden leer desde el dominio del servidor que las ha creado, por tanto otros servidores
  no tienen acceso.

- Cookies de 3ros -> Las cookies que establece un sitio (servidor) web diferente al que se hizo la petición.

Cualquier servidor puede acceder a las cookies de terceros.

- Funcionamiento: 

CLIENTE                                                   SERVIDOR

GET /index.html HTTP/1.1   ---------------------------->  HTTP/1.1 200 Ok
                                                          Encabezados (Date, Cache-control, Content-type, ...)

                                                          Set-Cookie: nombre=valor
                                                          Set-Cookie: nombre=Valor
                                                          ...
                          <-------------------------------
GET /news.html HTTP/1.1
Host: dwes.com
...
Cookie: nombre=valor (la misma que recibió) -----------> 

Ejemplo: Buardar las preferencias de usuario. Tema: color de fondo y color en perimer plano

El usuario envía un formulario solicitando sus preferencias: color de fondo y texto.
El servidor guardas estos valores como cookies. Cuando el cliente en sucesivas peticiones le envíe las
cookies, el servidor las recupera y establece el color de fondo y de texto. 

*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

// Comprobar si hay cookies. Si las hay las recogemos y
// devolvemos una respuesta con los colores de fondo y texto

// Si hay petición POST, se reciben nuevos colores que tendremos
// que establecer como cookies

// Si las cookies no existen o no son válidas, entonces se pone
// colores por defecto: negro sobre blanco.

$colorFondo = "white";
$colorTexto = "black";

$coloresValidos = [
  "white"       => "Blanco",
  "black"       => "Negro",
  "yellow"      => "Amarillo",
  "red"         => "Rojo",
  "blue"        => "Azul",
  "brown"       => "Marrón",
  "orange"      => "Naranja",
  "pink"        => "Rosa",
  "green"       => "Verde"
];

function sanearValidarCookies(int $origen): array {

  return [];
}

// ¿Dónde están las cookies? Si se reciben cookies del cliente (devueltas)
// están en el array superglobal $_COOKIES
if( isset($_COOKIE['colorFondo'], $_COOKIE['colorTexto']) ) {
  $cookieFondo = filter_input(INPUT_COOKIE, 'colorFondo', FILTER_SANITIZE_SPECIAL_CHARS);
  $cookieTexto = filter_input(INPUT_COOKIE, 'colorTexto', FILTER_SANITIZE_SPECIAL_CHARS);

  if( array_key_exists($cookieFondo, $coloresValidos) && array_key_exists($cookieTexto,$coloresValidos )){
    $colorFondo = $cookieFondo;
    $colorTexto = $cookieTexto;
  }
}

// Comprobar si hay petición POST
if( $_SERVER['REQUEST_METHOD'] === "POST" ){
  $cookieFondo = filter_input(INPUT_POST, 'colorFondo', FILTER_SANITIZE_SPECIAL_CHARS);
  $cookieTexto = filter_input(INPUT_POST, 'colorTexto', FILTER_SANITIZE_SPECIAL_CHARS);

  if( array_key_exists($cookieFondo, $coloresValidos) && array_key_exists($cookieTexto, $coloresValidos) ) {
    // Establecer las cookies
    // Función setcookie()
    setcookie("colorFondo", $cookieFondo, time() + 60 * 60, "/", "dwes.com", false, false);
    setcookie("colorTexto", $cookieTexto, time() + 60 * 60, "/", "dwes.com");

    // Que las nuevas preferencias de usuario entren en vigor
    $colorFondo = $cookieFondo;
    $colorTexto = $cookieTexto;
  }
} 


// Formulario para cambiar las preferencias
inicioHtml("Ejemplo de cookies", ["/estilos/general.css", "/estilos/formulario.css"]);
echo "<div style='background-color:$colorFondo;color:$colorTexto;'>";
echo <<<FORMULARIO
<header>Gestión de cookies</header>
<form method="POST" action="{$_SERVER['PHP_SELF']}">
  <fieldset>
    <legend>Preferencias de usuario</legend>
    <label for="colorFondo">Color de Fondo</label>
    <select name="colorFondo" size="1" id="colorFondo">
FORMULARIO;

array_walk($coloresValidos, function($color, $clave) use($colorFondo) {
  echo "<option value='$clave'" . ($clave == $colorFondo ? " selected" : "") . ">$color</option>";
});

echo <<<FORMULARIO
    </select>

    <label for="colorTexto">Color 1er plano</label>
    <select name="colorTexto" id="colorTexto" size="1">
FORMULARIO;

array_walk($coloresValidos, function($color, $clave) use($colorTexto) {
  echo "<option value='$clave'" . ($clave === $colorTexto ? " selected" : "") . ">$color</option>";
} );

echo <<<FORMULARIO
    </select>
  </fieldset>
  <input type="submit" name="operacion" id="operacion" value="Establecer preferencias">
</form>
FORMULARIO;

echo "</div>";
finHtml();

?>