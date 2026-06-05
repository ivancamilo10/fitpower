<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$usuario_id = (int) $_SESSION['id'];
$nombre_usuario = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario');

$rutinas = [
    [
        "nombre" => "Pecho y Tríceps",
        "descripcion" => "Rutina enfocada en empujes pesados, hipertrofia y fuerza del tren superior.",
        "dias" => 5,
        "imagen" => "https://cdn.pixabay.com/photo/2017/08/07/14/02/man-2604149_1280.jpg",
        "ejercicios" => [
            "Press banca plano - 4x10",
            "Press inclinado - 3x12",
            "Fondos - 3x8",
            "Extensión de tríceps - 3x12"
        ]
    ],
    [
        "nombre" => "Espalda y Bíceps",
        "descripcion" => "Rutina centrada en dominadas, remos y trabajo completo de tirón.",
        "dias" => 5,
        "imagen" => "https://cdn.pixabay.com/photo/2016/11/22/23/13/abs-1850926_1280.jpg",
        "ejercicios" => [
            "Dominadas - 4x8",
            "Remo con barra - 3x10",
            "Curl bíceps - 3x12",
            "Jalón al pecho - 3x12"
        ]
    ],
    [
        "nombre" => "Piernas y Glúteos",
        "descripcion" => "Entrenamiento para fuerza base y desarrollo de tren inferior.",
        "dias" => 5,
        "imagen" => "https://cdn.pixabay.com/photo/2017/03/13/21/34/girl-2142141_1280.jpg",
        "ejercicios" => [
            "Sentadilla profunda - 4x10",
            "Prensa - 4x12",
            "Hip thrust - 3x15",
            "Curl femoral - 3x12"
        ]
    ],
    [
        "nombre" => "Push Pull Legs",
        "descripcion" => "División práctica para repartir empuje, tirón y piernas durante la semana.",
        "dias" => 6,
        "imagen" => "https://cdn.pixabay.com/photo/2016/11/29/06/15/adult-1867743_1280.jpg",
        "ejercicios" => [
            "Push: Press banca, militar, fondos",
            "Pull: Dominadas, remo, curl",
            "Legs: Sentadilla, prensa, femoral",
            "Repetir ciclo y descansar"
        ]
    ]
];

$stmt = $conn->prepare("SELECT * FROM rutinas_usuario WHERE usuario_id = ? AND estado = 'activa' ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$rutina_activa = $result->fetch_assoc();

$progreso = $rutina_activa ? (int) $rutina_activa['progreso'] : 0;
$dia_actual = $rutina_activa ? (int) $rutina_activa['dia_actual'] : 1;
$total_dias = $rutina_activa ? (int) $rutina_activa['total_dias'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Rutinas - FitPower</title>
    <link rel="stylesheet" href="home_cliente.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0e0e0e;
            --surface: rgba(26, 25, 25, 0.86);
            --surface-2: rgba(255, 255, 255, 0.03);
            --text: #fff;
            --text-muted: #b5b2b2;
            --text-faint: #777;
            --primary: #d2ff9a;
            --primary-strong: #9ffb06;
            --primary-dark: #3d6500;
            --secondary: #00e3fd;
            --border: rgba(72, 72, 71, 0.16);
            --border-strong: rgba(72, 72, 71, 0.28);
            --shadow-soft: 0 8px 30px rgba(0, 0, 0, 0.28);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(210, 255, 154, 0.08), transparent 24%),
                linear-gradient(180deg, #050505 0%, #0e0e0e 100%);
            color: var(--text);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        h1, h2, h3, .brand {
            font-family: 'Space Grotesk', sans-serif;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 9999;
            min-height: 72px;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            color: var(--primary);
            font-size: 1.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.04em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .ghost-btn {
            padding: 10px 16px;
            border: 1px solid var(--border-strong);
            border-radius: 10px;
            color: var(--primary);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            background: transparent;
            white-space: nowrap;
        }

        .ghost-btn:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(210, 255, 154, 0.35);
        }

        .page {
            padding: 28px 0 48px;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero,
        .active-routine {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            padding: 28px;
            margin-bottom: 22px;
        }

        .eyebrow {
            color: var(--primary);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            font-size: 0.76rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .hero h1 {
            font-size: clamp(2.1rem, 5vw, 4.4rem);
            line-height: 0.95;
            letter-spacing: -0.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero p,
        .active-routine p {
            color: var(--text-muted);
            line-height: 1.7;
        }

        .welcome-name {
            color: var(--primary);
            font-weight: 800;
        }

        .active-routine h2 {
            margin-bottom: 10px;
        }

        .progress-bar {
            width: 100%;
            height: 14px;
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            margin: 14px 0 18px;
        }

        .progress-fill {
            height: 100%;
            width: <?php echo $progreso; ?>%;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
        }

        .routine-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 18px;
            min-height: 46px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: var(--primary-dark);
        }

        .btn-secondary {
            color: var(--primary);
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-strong);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
        }

        .card-media {
            min-height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .card-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.82), rgba(0, 0, 0, 0.18));
        }

        .card-title {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            z-index: 2;
        }

        .card-title h2 {
            font-size: 1.35rem;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 8px;
        }

        .card-title p {
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            height: 100%;
        }

        .card-body ul {
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .card-body li {
            padding: 12px 14px;
            border-radius: 14px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            line-height: 1.6;
        }

        .card-body form {
            margin-top: auto;
        }

        .footer {
            padding: 28px 16px 18px;
            color: var(--text-faint);
            text-align: center;
            font-size: 0.82rem;
        }

        @media (max-width: 1100px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 12px 16px;
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar-right {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .ghost-btn,
            .btn {
                width: 100%;
                text-align: center;
            }

            .container {
                width: min(100% - 20px, 1180px);
            }

            .hero,
            .active-routine {
                padding: 20px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .card-media {
                min-height: 200px;
            }

            .routine-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: calc(100% - 16px);
            }

            .hero h1 {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="home_cliente.php" class="brand">FitPower</a>
        <div class="topbar-right">
            <a href="home_cliente.php" class="ghost-btn">Inicio</a>
            <a href="rutinasgenerales.php" class="ghost-btn">Rutinas generales</a>
            <a href="logout.php" class="ghost-btn">Salir</a>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <section class="hero">
                <div class="eyebrow">Seguimiento / selección / progreso</div>
                <h1>Mis rutinas</h1>
                <p>Hola, <span class="welcome-name"><?php echo $nombre_usuario; ?></span>. Selecciona una rutina, guárdala como activa y lleva tu progreso día por día desde esta misma vista.</p>
            </section>

            <section class="active-routine">
                <?php if ($rutina_activa) { ?>
                    <div class="eyebrow">Rutina activa</div>
                    <h2><?php echo htmlspecialchars($rutina_activa['nombre_rutina']); ?></h2>
                    <p><?php echo htmlspecialchars($rutina_activa['descripcion']); ?></p>
                    <p style="margin-top: 10px; color: #fff;">
                        <strong>Día actual:</strong> <?php echo $dia_actual; ?> / <?php echo $total_dias; ?>
                    </p>
                    <p style="margin-top: 6px; color: #fff;">
                        <strong>Progreso:</strong> <?php echo $progreso; ?>%
                    </p>

                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>

                    <div class="routine-actions">
                        <form action="actualizar_progreso.php" method="post">
                            <input type="hidden" name="accion" value="sumar">
                            <button type="submit" class="btn btn-primary">+20% progreso</button>
                        </form>

                        <form action="actualizar_progreso.php" method="post">
                            <input type="hidden" name="accion" value="reiniciar">
                            <button type="submit" class="btn btn-secondary">Reiniciar progreso</button>
                        </form>

                        <form action="actualizar_progreso.php" method="post">
                            <input type="hidden" name="accion" value="finalizar">
                            <button type="submit" class="btn btn-secondary">Finalizar rutina</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="eyebrow">Sin rutina activa</div>
                    <h2>Aún no has elegido una rutina</h2>
                    <p>Escoge una de las opciones de abajo y empezaremos a registrar tu avance.</p>
                <?php } ?>
            </section>

            <section class="grid">
                <?php foreach ($rutinas as $rutina) { ?>
                    <article class="card">
                        <div class="card-media" style="background-image: url('<?php echo htmlspecialchars($rutina['imagen']); ?>');">
                            <div class="card-title">
                                <h2><?php echo htmlspecialchars($rutina['nombre']); ?></h2>
                                <p><?php echo htmlspecialchars($rutina['descripcion']); ?></p>
                            </div>
                        </div>

                        <div class="card-body">
                            <ul>
                                <?php foreach ($rutina['ejercicios'] as $ejercicio) { ?>
                                    <li><?php echo htmlspecialchars($ejercicio); ?></li>
                                <?php } ?>
                            </ul>

                            <form action="guardar_rutina.php" method="post">
                                <input type="hidden" name="nombre_rutina" value="<?php echo htmlspecialchars($rutina['nombre']); ?>">
                                <input type="hidden" name="descripcion" value="<?php echo htmlspecialchars($rutina['descripcion']); ?>">
                                <input type="hidden" name="total_dias" value="<?php echo (int) $rutina['dias']; ?>">
                                <button type="submit" class="btn btn-primary">Seleccionar rutina</button>
                            </form>
                        </div>
                    </article>
                <?php } ?>
            </section>
        </div>
    </main>

    <footer class="footer">
        &copy; 2025 FitPower Gym
    </footer>
</body>
</html>