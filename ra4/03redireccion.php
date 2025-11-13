<?php 
/*
REDIRECCIONES
-------------

Una petición provoca una respuesta del servidor con un encabezado para redirigir
la petición a otra URL. Hay 2 opciones:

1ª Opción: Redirección permanente
 - Código de estado 301
 - Frase de estado Moved Permanently

   header("HTTP/1.1 301 Moved Permanently");
   header("Location: <nueva_url>");

2ª Opción: Redirección temporal
 - Código de estado: 302
 - Frase de estado Moved Temporaly

   header("Location: <nueva_url>");

   En la redirección temporal el código de estado puede ser 307, indicando al cliente
   que en la 2ª petición (la redirigida) se haga con el mismo método que la petición 
   original.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");

if( $_SERVER['REQUEST_METHOD'] === "GET") {
  inicioHtml("Redirecciones", ["/estilos/general.css", "/estilos/formulario.css"]);
?>
<header>Opciones de la aplicación</header>
<form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
  <div class="bh">
    <button class="boton_bh" type="submit" name="operacion" value="1">Ver catálogo</button>
    <button class="boton_bh" type="submit" name="operacion" value="2">Tienda Online</button>
    <button class="boton_bh" type="submit" name="operacion" value="3">Quiénes somos</button>
    <button class="boton_bh" type="submit" name="operacion" value="4">Contacto</button>
  </div>
</form>

<?php
  finHtml();
}

if( $_SERVER['REQUEST_METHOD'] === "POST") {
  $operacion = filter_input(INPUT_POST, 'operacion', FILTER_VALIDATE_INT);
  $version = $_SERVER['SERVER_PROTOCOL'];
  switch($operacion) {
    case 1: {
      header("$version 301 Moved Permanently");
      header("Location: /ra4/03catalogo.php");
      exit();
    }
    case 2: {
      header("Location: /ra4/03tiendaol.php");
      exit();
    }
    case 3: {
      header("Location: /ra4/03quienes_somos.php");
      exit();
    }
    case 4: {
      header("Location: /ra4/03contacto.php");
      exit();
    }
    default: {
      header("$version 404 Not Found");
      exit();
    }
  }
}
?>