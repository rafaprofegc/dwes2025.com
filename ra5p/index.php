<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

// use ra5p\mvc\controlador\Controlador;
use ra5p\rest\controlador\Controlador;

$controlador = new Controlador();
//$controlador->despachaPeticion();
?>