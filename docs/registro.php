<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$correo = $_POST['correo'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Verificamos si la conexión funciona
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$sql = "INSERT INTO usuarios (nombre, usuario, password, correo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ssss", $nombre, $usuario, $password, $correo);

if ($stmt->execute()) {
    header("Location: login.html");
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
