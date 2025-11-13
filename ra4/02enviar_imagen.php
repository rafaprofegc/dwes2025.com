<?php
$imagen = filter_input(INPUT_GET, 'imagen', FILTER_SANITIZE_SPECIAL_CHARS) ?? "default.jpg";

if( file_exists($_SERVER['DOCUMENT_ROOT'] . "/archivos_ra4/$imagen") ) {
  $tipoMime = mime_content_type($_SERVER['DOCUMENT_ROOT']. "/archivos_ra4/$imagen");
  $tamaño = filesize($_SERVER['DOCUMENT_ROOT']. "/archivos_ra4/$imagen");

  header("Content-type: $tipoMime");
  header("Content-length: $tamaño");
  readfile($_SERVER['DOCUMENT_ROOT']. "/archivos_ra4/$imagen");
  exit();
}


?>