<?php
declare(strict_types=1);

function sumar(int|float $s1, int|float $s2): int|float {
  $suma = $s1 + $s2;
  return $suma;
}

function restar(int|float $s, int|float $m): int|float {
  $resta = $m - $s;
  return $resta;
}


