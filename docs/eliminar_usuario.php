<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID no válido.";
    exit();
}

$id = intval($_GET['id']);
$sql = "DELETE FROM usuarios WHERE id = ? AND rol != 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php");
    exit();
} else {
    echo "Error al eliminar el usuario." . $stmt->error;
}
?>
