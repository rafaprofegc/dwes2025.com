<?php
/*
En un script PHP se puede incluir el código que haya en otro archivo .php
Para ello tenemos 4 funciones que funcionan de manera casi igual, pero con 
pequeñas diferencias.

- include($path_archivo) -> El contenido del archivo $path_archivo sustituye 
  a la propia sentencia include dentro de un script.

  Si el archivo no existe, genera un warning y la ejecución del script continua.

- require($path_archivo) -> Lo mismo que include(), pero si el archivo no 
  existe se genera un error fatal y el script se detiene.

- include_once($path_archivo) -> Igual que include, pero no incluye el archivo
  si ya fue incluido previamente.

- require_once($path_archivo) -> Igual que require, pero no incluye el archivo
  si ya fue incluido previamente.

  El path del archivo:
  - Puedo usar ruta absoluta. Es una ruta desde el punto de vista del sistema de archivos
    no del servidor web.

    El servidor web (el sitio web) tiene una raíz de documentos (/var/www/dwes.com). Si uso
    una ruta absoluta siempre es relativa a la raíz de documentos.

    /codigo/funciones/numeros.php -> /var/www/dwes.com/codigo/funciones/numeros.php

    Si quiero usar la raíz de documentos en el argumento de include(), ... dispongo del
    elemento $_SERVER['DOCUMENT_ROOT]

*/
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/include/funciones2.php");

inicioHtml("Inclusión de archivos");

echo "<h1>Inclusión de archivos</h1>";
$suma = sumar(4, 5);

finHtml();






?>