<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo_pago = $_POST['tipo_pago'];
    $fecha = date("Y-m-d");

    if ($tipo_pago === "Mensualidad") {
        if (empty($_POST['usuario_id']) || !is_numeric($_POST['usuario_id'])) {
            die("❌ Cliente inválido.");
        }

        $usuario_id = intval($_POST['usuario_id']);

        // Verificar que el usuario exista
        $check = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
        $check->bind_param("i", $usuario_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows === 0) {
            die("❌ El cliente no existe en la base de datos.");
        }

        $stmt = $conn->prepare("INSERT INTO asistencias (usuario_id, tipo_pago, fecha) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("❌ Error en prepare (Mensualidad): " . $conn->error);
        }
        $stmt->bind_param("iss", $usuario_id, $tipo_pago, $fecha);

    } elseif ($tipo_pago === "Día") {
        if (empty($_POST['nombre_dia'])) {
            die("❌ Debes ingresar un nombre.");
        }

        $nombre_dia = trim($_POST['nombre_dia']);

        $stmt = $conn->prepare("INSERT INTO asistencias (nombre_dia, tipo_pago, fecha) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("❌ Error en prepare (Día): " . $conn->error);
        }
        $stmt->bind_param("sss", $nombre_dia, $tipo_pago, $fecha);

    } else {
        die("❌ Tipo de pago no válido.");
    }

    if ($stmt->execute()) {
        header("Location: admin.php?msg=asistencia_guardada");
        exit();
    } else {
        echo "❌ Error al guardar asistencia: " . $stmt->error;
    }
}
?>
