<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php");
/*
use ra7p\rpc\controlador\JsonRpcControlador;

$controlador = new JsonRpcControlador();
$controlador->ManejarPeticion();
*/
use ra7p\restful\enrutador\Enrutador;
$enrutador = new Enrutador();

?>