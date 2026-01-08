<?php
// Incluir las definiciones de clase

require_once("Utils/Autocarga.php");
use EN\Utils\Autocarga;
spl_autoload_register("\EN\Utils\Autocarga::autocarga");

use EN\BD\conexion\ConectarBD as CBD;
$cbd = new CBD("pepe", "localhost");
echo "<p>{$cbd->usuario} {$cbd->servidor}";

?>