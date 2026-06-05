<?php
include 'conexion.php';
session_start();

$usuario_id = $_POST['usuario_id'];
$peso = $_POST['peso'];
$estatura = $_POST['estatura'];
$meta_peso = $_POST['meta_peso'];
$banca = $_POST['banca'];
$sentadilla = $_POST['sentadilla'];
$peso_muerto = $_POST['peso_muerto'];
$objetivo = $_POST['objetivo'];

// Guardar en tabla masa_muscular
$sql_masa = "INSERT INTO masa_muscular (usuario_id, peso, estatura, meta_peso) VALUES (?, ?, ?, ?)";
$stmt_masa = $conn->prepare($sql_masa);
$stmt_masa->bind_param("iddd", $usuario_id, $peso, $estatura, $meta_peso);
$masa_guardada = $stmt_masa->execute();
$stmt_masa->close();

// Actualizar en tabla usuarios
$sql_update = "UPDATE usuarios SET objetivo = ?, peso = ?, banca = ?, sentadilla = ?, peso_muerto = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("sddddi", $objetivo, $peso, $banca, $sentadilla, $peso_muerto, $usuario_id);
$usuario_actualizado = $stmt_update->execute();
$stmt_update->close();

// Verificar si ambas operaciones fueron exitosas
if ($masa_guardada && $usuario_actualizado) {
    echo "<script>alert('Datos guardados correctamente.'); window.location.href='perfil.php';</script>";
} else {
    echo "Error al guardar los datos.";
}

$conn->close();
?>
