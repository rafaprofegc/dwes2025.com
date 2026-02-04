<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
session_start();

// use ra5p\mvc\controlador\Controlador;
use ra5p\rest\controlador\Controlador;

$controlador = new Controlador();
//$controlador->despachaPeticion();
?>