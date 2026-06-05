<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM usuarios WHERE id = $id AND rol != 'admin'";
$resultado = $conn->query($sql);
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente - FitPower</title>
    <link rel="stylesheet" href="home_cliente.css">
    <link rel="icon" href="favicon.ico">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        .edit-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #222;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        input:focus {
            border-color: #ff3131;
            outline: none;
        }

        .btn-update {
            width: 100%;
            background: #ff3131;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-update:hover {
            background: #e02727;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .edit-container {
                padding: 25px;
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="admin.php" class="logo">FitPower Admin</a>
            <div class="user-menu">
                <div class="user-avatar">
                    <span class="avatar">🧑‍💼</span>
                    <span><?php echo $_SESSION['nombre']; ?></span>
                    <div class="dropdown">
                        <a href="logout.php">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <div class="edit-container">
                <h2>Editar Cliente</h2>
                <form action="actualizar_usuario.php" method="POST">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                    <label for="nombre">Nombre del Cliente:</label>
                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                    <label for="fecha">Fecha de fin de mensualidad:</label>
                    <input type="date" name="fecha" id="fecha" value="<?= $usuario['fecha_fin_mensualidad'] ?>" required>

                    <button type="submit" class="btn-update">Actualizar Cliente</button>
                </form>
                <a href="admin.php" class="back-link">← Volver al panel</a>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 FitPower Gym. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
