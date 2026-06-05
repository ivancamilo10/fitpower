<?php
session_start();
include 'conexion.php';

$usuario = $_POST['user'];
$password = $_POST['pass'];

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario_db = $resultado->fetch_assoc();

    if (password_verify($password, $usuario_db['password'])) {
        $_SESSION['nombre'] = $usuario_db['nombre'];
        $_SESSION['id'] = $usuario_db['id'];
        $_SESSION['rol'] = $usuario_db['rol']; // 👈 GUARDAMOS EL ROL

        // Redirigir a la vista admin si es administrador
        if ($_SESSION['rol'] === 'admin') {
            header("Location: admin.php");
            exit();
        }

        // Redirección pendiente
        if (isset($_SESSION['redirigir_a'])) {
            $destino = $_SESSION['redirigir_a'];
            unset($_SESSION['redirigir_a']);
            header("Location: $destino");
            exit();
        }

        // Usuario normal
        header("Location: home_cliente.php");
        exit();
    }
}

// Credenciales incorrectas
echo "<script>alert('Credenciales incorrectas'); window.location.href='login.html';</script>";
?>
