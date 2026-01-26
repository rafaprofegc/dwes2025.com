<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra5p\mvc\controlador\Controlador;

$controlador = new Controlador();
$controlador->despachaPeticion();
?>