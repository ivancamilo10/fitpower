<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM asistencias WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=asistencia_eliminada");
    } else {
        echo "❌ Error al eliminar asistencia.";
    }
} else {
    echo "❌ ID no válido.";
}
?>
