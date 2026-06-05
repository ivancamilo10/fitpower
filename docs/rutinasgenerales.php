<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: login.html");
    exit();
}

$nombre = htmlspecialchars($_SESSION['nombre']);

$rutinas = [
    [
        "titulo" => "Ganar Masa Muscular",
        "imagen" => "https://i.ytimg.com/vi/c9EwYbWLQ88/maxresdefault.jpg",
        "nivel" => "Hipertrofia / fuerza",
        "descripcion" => "Rutina enfocada en subir masa muscular con compuestos pesados, volumen moderado y progresión semanal.",
        "detalles" => [
            "Lunes: Pecho + Tríceps - 3x6 pesado al fallo",
            "Martes: Espalda + Bíceps - 3x6 pesado al fallo",
            "Miércoles: Pierna - 3x6 al fallo (sentadilla, prensa, curl femoral)",
            "Jueves: Hombros - 3x6 al fallo (press militar, elevaciones laterales)",
            "Viernes: Full body compuesto - Dominadas, fondos, peso muerto",
            "Cardio suave en ayunas opcional"
        ]
    ],
    [
        "titulo" => "Pérdida de Grasa",
        "imagen" => "https://www.neolifesalud.com/wp-content/uploads/Perdida-de-grasa-2-1024x576.jpg",
        "nivel" => "Déficit / acondicionamiento",
        "descripcion" => "Combinación de fuerza y cardio para aumentar el gasto energético y conservar masa muscular.",
        "detalles" => [
            "Lunes: Circuito HIIT + Tren superior",
            "Martes: Cardio LISS 45 min + Abdomen",
            "Miércoles: Circuito piernas + glúteo",
            "Jueves: Cardio HIIT + Full body",
            "Viernes: Rutina metabólica + cardio",
            "Sábado: Cardio libre o descanso activo"
        ]
    ],
    [
        "titulo" => "Cardio Inteligente",
        "imagen" => "https://cdn.pixabay.com/photo/2022/09/13/07/41/endurance-7451199_1280.jpg",
        "nivel" => "Resistencia / quema calórica",
        "descripcion" => "Opciones de cardio para combinar con etapas de volumen, definición o acondicionamiento general.",
        "detalles" => [
            "HIIT 20 min: correr 30 s / caminar 60 s, 3 veces por semana",
            "Boxeo o saco: 5 rounds de 3 minutos",
            "Saltar cuerda: 3x3 min",
            "LISS: caminata 45-60 min en ayunas"
        ]
    ],
    [
        "titulo" => "Arnold Split",
        "imagen" => "https://hips.hearstapps.com/hmg-prod/images/arnold-schwarzenegger-1576501420.jpg",
        "nivel" => "Volumen avanzado",
        "descripcion" => "División clásica con alta frecuencia y bastante volumen para atletas intermedios o avanzados.",
        "detalles" => [
            "Lunes: Pecho + Espalda",
            "Martes: Piernas",
            "Miércoles: Hombro + Brazos",
            "Jueves: Repite Lunes",
            "Viernes: Repite Martes",
            "Sábado: Repite Miércoles",
            "Domingo: Descanso"
        ]
    ],
    [
        "titulo" => "PPL (Push Pull Legs)",
        "imagen" => "https://cdn.pixabay.com/photo/2017/05/25/15/08/jogging-2343558_1280.jpg",
        "nivel" => "Intermedio / eficiente",
        "descripcion" => "Una de las divisiones más prácticas para entrenar 3 a 6 días y distribuir bien la fatiga.",
        "detalles" => [
            "Lunes: Push - Pecho, hombros, tríceps",
            "Martes: Pull - Espalda, bíceps",
            "Miércoles: Legs - Sentadilla, prensa, femoral",
            "Jueves: Descanso o core",
            "Viernes: Repite Push",
            "Sábado: Repite Pull o Legs",
            "Domingo: Descanso"
        ]
    ],
    [
        "titulo" => "PPL x Arnold Fusion",
        "imagen" => "https://i.ytimg.com/vi/Sq9us_zb7rM/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLA5dcPam9QIoQ7kHMxE3qXPLY_mEw",
        "nivel" => "Avanzado / alta intensidad",
        "descripcion" => "Mezcla de Push Pull Legs con volumen tipo Arnold para quienes toleran mucha carga semanal.",
        "detalles" => [
            "Push pesado lunes",
            "Arnold pecho/espalda martes",
            "Piernas divididas en cuádriceps y femoral/glúteo",
            "Alta intensidad con técnica estricta",
            "Descanso activo según fatiga acumulada"
        ]
    ],
    [
        "titulo" => "Rutina Recomendada 5 Días",
        "imagen" => "https://pumpx.app/wp-content/uploads/2025/04/hq720.jpg",
        "nivel" => "Equilibrada / general",
        "descripcion" => "Rutina sólida para alguien que quiere progresar en fuerza, masa y condición general sin complicarse.",
        "detalles" => [
            "Lunes: Empuje - press banca, militar, dips",
            "Martes: Tracción - remo, jalones, bíceps",
            "Miércoles: Pierna",
            "Jueves: Hombros + Core",
            "Viernes: Full Body",
            "Todo en 3x6 o series descendentes hasta el fallo"
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rutinas Generales - FitPower</title>
    <link rel="stylesheet" href="home_cliente.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0e0e0e;
            --surface: rgba(26,25,25,.86);
            --surface-2: rgba(255,255,255,.03);
            --text: #ffffff;
            --text-muted: #b5b2b2;
            --text-faint: #777;
            --primary: #d2ff9a;
            --primary-strong: #9ffb06;
            --primary-dark: #3d6500;
            --secondary: #00e3fd;
            --border: rgba(72,72,71,.16);
            --border-strong: rgba(72,72,71,.28);
            --shadow-soft: 0 8px 30px rgba(0,0,0,.28);
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
                radial-gradient(circle at top right, rgba(210,255,154,.08), transparent 24%),
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
            letter-spacing: -.04em;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .ghost-btn {
            padding: 10px 16px;
            border: 1px solid var(--border-strong);
            border-radius: 10px;
            color: var(--primary);
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            background: transparent;
            white-space: nowrap;
        }

        .ghost-btn:hover {
            background: rgba(255,255,255,.03);
            border-color: rgba(210,255,154,.35);
        }

        .page {
            padding: 28px 0 48px;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            padding: 28px;
            margin-bottom: 22px;
        }

        .eyebrow {
            color: var(--primary);
            letter-spacing: .2em;
            text-transform: uppercase;
            font-size: .76rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .hero h1 {
            font-size: clamp(2.1rem, 5vw, 4.6rem);
            line-height: .95;
            letter-spacing: -.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero p {
            color: var(--text-muted);
            max-width: 70ch;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 18px;
            min-height: 46px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            transition: transform .2s ease, opacity .2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: var(--primary-dark);
        }

        .btn-secondary {
            color: var(--primary);
            background: rgba(255,255,255,.02);
            border-color: var(--border-strong);
        }

        .rutinas-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .rutina-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-radius: 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
            min-width: 0;
        }

        .rutina-media {
            position: relative;
            min-height: 220px;
            background-size: cover;
            background-position: center;
        }

        .rutina-media::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,.85), rgba(0,0,0,.18));
        }

        .rutina-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 2;
            padding: 8px 10px;
            border-radius: 999px;
            font-size: .72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--primary-dark);
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
        }

        .rutina-title-wrap {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            z-index: 2;
        }

        .rutina-title-wrap h2 {
            font-size: 1.4rem;
            line-height: 1;
            letter-spacing: -.04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .rutina-title-wrap p {
            color: rgba(255,255,255,.82);
            line-height: 1.6;
            font-size: .95rem;
        }

        .rutina-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .rutina-body h3 {
            font-size: .85rem;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .rutina-list {
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .rutina-list li {
            padding: 12px 14px;
            border-radius: 14px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--text-muted);
            line-height: 1.6;
            word-break: break-word;
        }

        .rutina-footer {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .mini-chip {
            padding: 8px 10px;
            border-radius: 999px;
            border: 1px solid var(--border);
            color: var(--text-faint);
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .footer {
            padding: 28px 16px 18px;
            color: var(--text-faint);
            text-align: center;
            font-size: .82rem;
        }

        @media (max-width: 1100px) {
            .rutinas-grid {
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

            .ghost-btn {
                width: 100%;
                text-align: center;
            }

            .container {
                width: min(100% - 20px, 1180px);
            }

            .hero {
                padding: 20px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero-actions {
                flex-direction: column;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .rutinas-grid {
                grid-template-columns: 1fr;
            }

            .rutina-media {
                min-height: 210px;
            }

            .rutina-title-wrap h2 {
                font-size: 1.2rem;
            }

            .rutina-body {
                padding: 18px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: calc(100% - 16px);
            }

            .hero h1 {
                font-size: 1.7rem;
            }

            .eyebrow {
                font-size: .68rem;
                letter-spacing: .16em;
            }

            .rutina-media {
                min-height: 190px;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="home_cliente.php" class="brand">FitPower</a>

        <div class="topbar-right">
            <div class="user-pill">
                <span>💪</span>
                <span><?php echo $nombre; ?></span>
            </div>
            <a href="perfil.php" class="ghost-btn">Perfil</a>
            <a href="rutinas.php" class="ghost-btn">Mis rutinas</a>
            <a href="logout.php" class="ghost-btn">Salir</a>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <section class="hero">
                <div class="eyebrow">Entrenamiento / biblioteca / splits</div>
                <h1>Rutinas generales</h1>
                <p>
                    Aquí tienes varias divisiones de entrenamiento para fuerza, hipertrofia, definición y acondicionamiento. Rutinas tipo full body, PPL, Arnold y divisiones por objetivo suelen ser formatos comunes para organizar volumen, frecuencia y recuperación según la meta. 
                </p>
                <div class="hero-actions">
                    <a href="#rutinas" class="btn btn-primary">Ver rutinas</a>
                    <a href="home_cliente.php" class="btn btn-secondary">Volver al inicio</a>
                </div>
            </section>

            <section class="rutinas-grid" id="rutinas">
                <?php foreach ($rutinas as $rutina): ?>
                    <article class="rutina-card">
                        <div class="rutina-media" style="background-image: url('<?php echo htmlspecialchars($rutina['imagen']); ?>');">
                            <div class="rutina-badge"><?php echo htmlspecialchars($rutina['nivel']); ?></div>
                            <div class="rutina-title-wrap">
                                <h2><?php echo htmlspecialchars($rutina['titulo']); ?></h2>
                                <p><?php echo htmlspecialchars($rutina['descripcion']); ?></p>
                            </div>
                        </div>

                        <div class="rutina-body">
                            <h3>Distribución semanal</h3>
                            <ul class="rutina-list">
                                <?php foreach ($rutina['detalles'] as $detalle): ?>
                                    <li><?php echo htmlspecialchars($detalle); ?></li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="rutina-footer">
                                <span class="mini-chip">FitPower</span>
                                <span class="mini-chip">Entrenamiento</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <footer class="footer">
        &copy; 2025 FitPower Gym. Todos los derechos reservados.
    </footer>
</body>
</html>