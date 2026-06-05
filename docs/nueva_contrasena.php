<?php
include 'conexion.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar que el token exista
    $sql = "SELECT * FROM usuarios WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];

        // Mostrar formulario para nueva contraseña
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nueva = password_hash($_POST['nueva'], PASSWORD_DEFAULT);
            $actualizar = $conn->prepare("UPDATE usuarios SET password = ?, token = NULL WHERE id = ?");
            $actualizar->bind_param("si", $nueva, $usuario_id);
            $actualizar->execute();

            echo "<script>alert('Contraseña actualizada con éxito'); window.location='login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Token inválido o expirado'); window.location='login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Acceso no autorizado'); window.location='login.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer Contraseña - FitPower</title>
  <link rel="stylesheet" href="home_cliente.css">
</head>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1');
      background-size: cover;
      background-position: center;
      backdrop-filter: blur(5px);
    }

    .login-container {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease;
      color: white;
    }

    .login-form h2 {
      text-align: center;
      margin-bottom: 24px;
    }

    .login-form label {
      display: block;
      margin-top: 15px;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .login-form input {
      width: 100%;
      padding: 10px 14px;
      border: none;
      border-radius: 8px;
      outline: none;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      transition: all 0.3s ease;
    }

    .login-form input:focus {
      background-color: rgba(255, 255, 255, 0.4);
      box-shadow: 0 0 10px #ff416c, 0 0 20px #ff4b2b;
    }

    .btn {
      width: 100%;
      margin-top: 24px;
      padding: 12px;
      background: linear-gradient(45deg, #fcff41, #e7d84b);
      color: black;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(255, 75, 43, 0.6);
    }

    .register-link {
      margin-top: 20px;
      text-align: center;
    }

    .register-link a {
      color: #00ffff;
      text-decoration: none;  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Montserrat', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1');
      background-size: cover;
      background-position: center;
      backdrop-filter: blur(5px);
    }

    .login-container {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 1s ease;
      color: white;
    }

    .login-form h2 {
      text-align: center;
      margin-bottom: 24px;
    }

    .login-form label {
      display: block;
      margin-top: 15px;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .login-form input {
      width: 100%;
      padding: 10px 14px;
      border: none;
      border-radius: 8px;
      outline: none;
      background-color: rgba(255, 255, 255, 0.2);
      color: white;
      transition: all 0.3s ease;
    }

    .login-form input:focus {
      background-color: rgba(255, 255, 255, 0.4);
      box-shadow: 0 0 10px #ff416c, 0 0 20px #ff4b2b;
    }

    .btn {
      width: 100%;
      margin-top: 24px;
      padding: 12px;
      background: linear-gradient(45deg, #fcff41, #e7d84b);
      color: black;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(255, 75, 43, 0.6);
    }

    .register-link {
      margin-top: 20px;
      text-align: center;
    }

    .register-link a {
      color: #00ffff;
      text-decoration: none;
      font-weight: bold;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
      font-weight: bold;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
<body class="login-body" style="background-image: url('https://images.unsplash.com/photo-1605296867304-46d5465a13f1'); background-size: cover; background-position: center;">
  <div class="login-container">
    <form method="POST" class="login-form">
      <h2>Restablecer Contraseña</h2>
      <label for="nueva">Nueva Contraseña</label>
      <input type="password" name="nueva" id="nueva" required>
      <button type="submit" class="btn">Guardar Contraseña</button>
    </form>
  </div>
</body>
</html>
