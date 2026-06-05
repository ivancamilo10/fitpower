<?php
include 'conexion.php';
session_start();

$usuario_id = $_POST['usuario_id'];
$peso = $_POST['peso'];
$estatura = $_POST['estatura'];
$peso_objetivo = $_POST['peso_objetivo'];

$sql = "INSERT INTO definicion (usuario_id, peso, estatura, peso_objetivo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iidd", $usuario_id, $peso, $estatura, $peso_objetivo);

if ($stmt->execute()) {
    echo "<script>alert('Datos guardados correctamente.'); window.location.href='alimentacion_perder.php';</script>";
} else {
    echo "Error al guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
