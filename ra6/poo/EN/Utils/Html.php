<?php
namespace EN\Utils;

class Html {
  public static function inicioHTML($titulo, $estilos):void {
    echo "<!DOCTYPE html>";
    echo "<html>";
    
  }

  public static function finHtml() {
    echo "</body>";
    echo "</html>";
  }
}
