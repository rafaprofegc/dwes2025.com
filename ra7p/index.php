<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");

use ra7p\rpc\controlador\JsonRpcControlador;

$controlador = new JsonRpcControlador();
$controlador->ManejarPeticion();

?>