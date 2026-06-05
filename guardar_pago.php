<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $monto = $_POST['monto'];
    $dias = intval($_POST['dias']);

    $fecha_pago = date('Y-m-d');
    $nueva_fecha_fin = date('Y-m-d', strtotime("+$dias days"));

    // Insertar en la tabla pagos
    $sqlPago = "INSERT INTO pagos (usuario_id, monto, fecha_pago) VALUES (?, ?, ?)";
    $stmtPago = $conn->prepare($sqlPago);

    if ($stmtPago) {
        $stmtPago->bind_param("ids", $usuario_id, $monto, $fecha_pago);
        $stmtPago->execute();
        $stmtPago->close();
    } else {
        die("Error al preparar pago: " . $conn->error);
    }

    // Actualizar fecha_fin_mensualidad
    $sqlUpdate = "UPDATE usuarios SET fecha_fin_mensualidad = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param("si", $nueva_fecha_fin, $usuario_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        die("Error al preparar actualización: " . $conn->error);
    }

    // Redirigir al panel admin
    header("Location: admin.php");
    exit();
} else {
    echo "Acceso no permitido";
}
?>
