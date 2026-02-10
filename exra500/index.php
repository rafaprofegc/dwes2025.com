<?php

use exra500\controlador\Controlador00;
use exra500\util\autocarga\Autocarga;

require_once($_SERVER['DOCUMENT_ROOT'] . "/exra500/util/autocarga/Autocarga.php");

Autocarga::registraAutocarga();

$controlador = new Controlador00();
?>
