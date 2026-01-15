<?php
// Creamos una imagen
$im = imagecreatetruecolor(100,200);

// Ponemos color de fondo
$red = imagecolorallocate($im, 255, 0, 0 );
imagefill($im, 0, 0, $red);

// Enviar la imagen a la salida
header("Content-type: image/png");
imagepng($im);
?>