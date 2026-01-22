<?php
namespace EN\Utils;

class Html {
  public static function inicioHTML($titulo, $estilos):void {
    echo "<!DOCTYPE html>";
    echo "<html>";
    echo "<head><title>$titulo</title></head>";
    echo "<body>";
  }

  public static function finHtml() {
    echo "</body>";
    echo "</html>";
  }
}
