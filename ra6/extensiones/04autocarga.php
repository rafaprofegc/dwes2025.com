<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// Utilizar las clases del espacio de nombres EN\
use EN\BD\conexion\ConectarBD;
use EN\BD\entidades\tiendaol\Categoria;
use EN\BD\Usuario;
use EN\Utils\Html;

Html::inicioHTML("Autocarga con composer", ["/estilos/general.css"]);

echo "<h3>Instanciamos un objeto ConectarBD</h3>";
$cbd = new ConectarBD("usuario", "abc123");

echo "<h3>Instanciamos un objeto Usuario</h3>";
$usuario = new Usuario("pepe", "abc123");

echo "<h3>Instanciamos un objeto Categoría</h3>";
$categoria = new Categoria("ACIN", "Accesorios de Informática");

Html::finHtml();
?>
