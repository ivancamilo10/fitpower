<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = (int)$_SESSION['id'];
$accion = $_POST['accion'] ?? '';

$stmt = $conn->prepare("SELECT id, progreso, dia_actual, total_dias FROM rutinas_usuario WHERE usuario_id = ? AND estado = 'activa' ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$rutina = $result->fetch_assoc();

if (!$rutina) {
    header("Location: rutinas.php");
    exit();
}

$id = (int)$rutina['id'];
$progreso = (int)$rutina['progreso'];
$dia_actual = (int)$rutina['dia_actual'];
$total_dias = (int)$rutina['total_dias'];

if ($accion === 'sumar') {
    $progreso += 20;
    if ($progreso > 100) {
        $progreso = 100;
    }

    if ($dia_actual < $total_dias) {
        $dia_actual++;
    }

    $estado = ($progreso >= 100) ? 'completada' : 'activa';

    $stmt = $conn->prepare("UPDATE rutinas_usuario SET progreso = ?, dia_actual = ?, estado = ? WHERE id = ?");
    $stmt->bind_param("iisi", $progreso, $dia_actual, $estado, $id);
    $stmt->execute();
}

if ($accion === 'reiniciar') {
    $estado = 'activa';
    $progreso = 0;
    $dia_actual = 1;

    $stmt = $conn->prepare("UPDATE rutinas_usuario SET progreso = ?, dia_actual = ?, estado = ? WHERE id = ?");
    $stmt->bind_param("iisi", $progreso, $dia_actual, $estado, $id);
    $stmt->execute();
}

if ($accion === 'finalizar') {
    $estado = 'completada';
    $progreso = 100;
    $dia_actual = $total_dias;

    $stmt = $conn->prepare("UPDATE rutinas_usuario SET progreso = ?, dia_actual = ?, estado = ? WHERE id = ?");
    $stmt->bind_param("iisi", $progreso, $dia_actual, $estado, $id);
    $stmt->execute();
}

header("Location: rutinas.php");
exit();
?>