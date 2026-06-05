<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: rutinas.php");
    exit();
}

$usuario_id = (int)$_SESSION['id'];
$nombre_rutina = trim($_POST['nombre_rutina'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$total_dias = (int)($_POST['total_dias'] ?? 5);

if ($nombre_rutina === '') {
    header("Location: rutinas.php");
    exit();
}

$stmt = $conn->prepare("UPDATE rutinas_usuario SET estado = 'archivada' WHERE usuario_id = ? AND estado = 'activa'");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO rutinas_usuario (usuario_id, nombre_rutina, descripcion, progreso, dia_actual, total_dias, estado) VALUES (?, ?, ?, 0, 1, ?, 'activa')");
$stmt->bind_param("issi", $usuario_id, $nombre_rutina, $descripcion, $total_dias);
$stmt->execute();

header("Location: rutinas.php");
exit();
?>