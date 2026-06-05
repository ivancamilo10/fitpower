<?php
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$bd = 'fitpower';

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
