<?php
/*
Gestión de la caché del navegador
---------------------------------

¿Qué es la caché del navegador? 
Es un directorio del disco duro que utiliza el navegador para guardar las páginas web
que se reciben.

Si una página cacheada vuelve a solicitarse, el navegador sirve la cacheada, en lugar
de hacer una nueva solicitud al navegador.

Ventajas:
  - Rapidez en el acceso al contenido de una página.
  - Se libera el servidor de peticiones con respuesta redundante.

Inconvenientes:
  - Hay que gestionar el TTL de las páginas, es decir, las páginas cacheadas no son
    copias permanentes, sino que pueden actualizarse.

¿Cómo se gestiona? Con encabezados. 

  - Expires: <fecha hora> Indica la fecha y la hora en que la págian caduca. A partir de entonces
                          la copia del navegador se deshecha y se hace una nueva petición.
                          La fecha y hora siguen el formato RFC9110. Ej:
                          Thu, 13 Oct 2025 15:30:00 GMT


  - Cache-control: <valores> Indica cuánto tiempo, si se almacena la página, el ámbito y qué tiene
                             que hacer el navegador con las páginas cacheadas.
                             Esta cabecera invalida Expires: 
      Posibles valores:
      - no-cache -> La página se cachea, pero antes de mostrarla al usuario se tiene que validar
                    en el servidor.
      - no-store -> La página no se cachea.
      - max-age:<sg>  -> Tiempo durante el cual la página se considera reciente y después del cual
                         se considera obsoleta.
      - must-revalidate -> El navegador valida la página en el servidor si se ha superado el max-age
      - private | public -> Si es privado, solo el navegador puede cachear la página. Si es público,
                            el navegador y los dispositivos intermedios (proxies) pueden cachearla.

      Referencia completa: https://developer.mozilla.org/es/docs/Web/HTTP/Headers/Cache-control

  - Date: <fecha> Fecha a partir de la cual la página es válida y desde donde se comienza a contar
                  el tiempo para ver cuando se queda obsoleta.

  - Last-modified: Fecha última de modificación de la página.
*/

$ahora = time();
$masUnaHora = $ahora + 60 * 60;

// Thu, 13 Oct 2025 15:30:00 GMT
$formatoFecha = "D, j M Y H:i:s";
$caducidad = gmdate($formatoFecha, $masUnaHora);

// 1ª Opción. Encabezado Expires para indicar cuando caduca la página
// header("Expires: $caducidad GMT");

// 2ª Opción. Encabezado Cache-control
header("Cache-control: no-cache, max-age:3600, must-revalidate");

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
inicioHtml("Gestión de caché", ["/estilos/general.css"]);
echo "<header>Probando la caché del navegador</header>";
echo "<h3>Esta página caduca el $caducidad. ";
echo "Si repites la petición antes de que caduque, te la sirve el navegador</h3>";
finHtml();
?>