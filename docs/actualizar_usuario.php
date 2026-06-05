<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$fecha = $_POST['fecha'];

$sql = "UPDATE usuarios SET nombre = ?, fecha_fin_mensualidad = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nombre, $fecha, $id);
$stmt->execute();

header("Location: admin.php");
exit();
?>
