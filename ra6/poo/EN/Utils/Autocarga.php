<?php
namespace EN\Utils;

class Autocarga {
  public static function autocarga(string $clase): void {
    // Recibe una clase cualificada con su EN
    // Si se instancia un objeto de la clase Categoria
    // $clase = EN\BD\entidades\tiendaol\Categoria
    // Despues de cambiar \ por / en $clase
    // Archivo: $_SERVER['DOCUMENTO_ROOT'] . "/ra6/poo/" . $clase.php;

    $clase = str_replace("\\", "/", $clase);
    $archivo = $_SERVER['DOCUMENT_ROOT'] . "/ra6/poo/$clase.php";
    require_once($archivo);
  }
}