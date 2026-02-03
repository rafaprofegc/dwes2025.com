<?php
namespace ra5p\rest\vista;

class VFormLogin extends Vista {
  public function generaSalida(mixed $datos): void {
    ob_start();
    $this->inicioHtml("Autenticación de cliente", ["/estilos/general.css", "/estilos/formulario.css"]);
    echo <<<LOGIN
    <header>Autenticación de cliente</header>
    <form method="POST" action="/login">
      <fieldset>
        <legend>Credenciales de cliente</legend>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" size="15" require>

        <label for="clave">Clave</label>
        <input type="password" name="clave" id="clave" size="15" require>
      </fieldset>
      <input type="submit" name="operacion" id="operacion" value="Abrir sesión">
    </form>
    LOGIN;
    $this->finHtml();
    ob_end_flush();

  }
}