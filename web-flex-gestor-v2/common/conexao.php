<?php
$hostname = 'localhost';
$bancodedados = 'webflexgestor';
$usuario = 'root';
$senha = '';

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

if ($mysqli->connect_errno) {
  echo "Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  exit;
}
?>

