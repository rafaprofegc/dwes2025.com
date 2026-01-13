<?php
$imagen = imagecreatetruecolor(100,100);

$colorRojo = imagecolorallocate($imagen, 255,0,0);

imagefill($imagen, 0,0, $colorRojo);

header("Content-type: image/png");
imagepng($imagen);

?>