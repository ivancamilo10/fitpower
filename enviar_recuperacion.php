<?php
session_start();
require __DIR__ . '/conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: recuperar_contrasena.html");
    exit();
}

$correo = trim($_POST['correo'] ?? '');

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: recuperar_contrasena.html?error=1");
    exit();
}

$stmt = $conn->prepare("SELECT id, nombre, correo FROM usuarios WHERE correo = ? LIMIT 1");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: recuperar_contrasena.html?ok=1");
    exit();
}

$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

$delete = $conn->prepare("DELETE FROM password_resets WHERE correo = ?");
$delete->bind_param("s", $correo);
$delete->execute();

$insert = $conn->prepare("INSERT INTO password_resets (correo, token, expira_en) VALUES (?, ?, ?)");
$insert->bind_param("sss", $correo, $token, $expira);
$insert->execute();

$resetLink = "http://localhost/fitpower/nueva_contrasena.php?token=" . urlencode($token) . "&correo=" . urlencode($correo);

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'tu_correo_real@gmail.com';
$mail->Password   = 'juan123456789'; // reemplázala por la App Password real, sin espacios
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

    $mail->setFrom('tucorreo@gmail.com', 'FitPower Gym');
    $mail->addAddress($correo, $usuario['nombre']);

    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de contraseña - FitPower';
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;color:#222;font-size:15px;max-width:500px;margin:0 auto'>
            <h2 style='color:#1a1a1a'>Recuperación de contraseña</h2>
            <p>Hola, <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>.</p>
            <p style='margin:12px 0'>Haz clic en el siguiente botón para restablecer tu contraseña:</p>
            <p>
                <a href='{$resetLink}'
                   style='display:inline-block;padding:12px 22px;background:#9ffb06;color:#1d3000;
                          text-decoration:none;border-radius:8px;font-weight:bold;font-size:15px'>
                    Restablecer contraseña
                </a>
            </p>
            <p style='margin-top:16px;color:#555'>Este enlace vencerá en <strong>1 hora</strong>.</p>
            <p style='color:#555'>Si no solicitaste este cambio, ignora este mensaje.</p>
        </div>
    ";
    $mail->AltBody = "Restablece tu contraseña aquí: $resetLink";
$mail->SMTPDebug = 2;
$mail->Debugoutput = 'html';
    $mail->send();
    header("Location: recuperar_contrasena.html?ok=1");
    exit();
} catch (Exception $e) {
    die("Error PHPMailer: " . $mail->ErrorInfo . "<br>Exception: " . $e->getMessage());
}