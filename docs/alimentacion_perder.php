<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['redirigir_a'] = $_SERVER['REQUEST_URI'];
    header("Location: login.html");
    exit();
}

$usuario_id = (int)$_SESSION['id'];

$usuario = [
    'objetivo' => '',
    'banca' => '',
    'sentadilla' => '',
    'peso_muerto' => ''
];

$stmt_user = $conn->prepare("SELECT objetivo, banca, sentadilla, peso_muerto FROM usuarios WHERE id = ?");
$stmt_user->bind_param("i", $usuario_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($row_user = $result_user->fetch_assoc()) {
    $usuario = array_merge($usuario, $row_user);
}

$banca = htmlspecialchars((string)($usuario['banca'] ?? ''));
$sentadilla = htmlspecialchars((string)($usuario['sentadilla'] ?? ''));
$peso_muerto = htmlspecialchars((string)($usuario['peso_muerto'] ?? ''));
$objetivo_usuario = htmlspecialchars((string)($usuario['objetivo'] ?? ''));

$stmt = $conn->prepare("SELECT peso, estatura, peso_objetivo FROM definicion WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$has_data = (bool)$data;
$peso = $has_data ? (float)$data['peso'] : 0;
$estatura = $has_data ? (float)$data['estatura'] : 0;
$meta = $has_data ? (float)$data['peso_objetivo'] : 0;

$tmb = 0;
$mantenimiento = 0;
$objetivo_calorias = 0;
$kilos_perder = 0;
$dias_estimados = 0;
$progreso = 0;

if ($has_data && $peso > 0 && $estatura > 0 && $meta > 0) {
    $tmb = (10 * $peso) + (6.25 * $estatura) - (5 * 25) + 5;
    $mantenimiento = $tmb * 1.55;
    $objetivo_calorias = $mantenimiento - 500;
    $kilos_perder = $peso - $meta;
    $dias_estimados = max(0, round(abs($kilos_perder) * 15));

    if ($peso > 0) {
        $progreso = max(0, min(100, (($peso - $meta) / $peso) * 100));
    }
}

$nombre = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Definición - FitPower</title>
    <link rel="stylesheet" href="home_cliente.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0e0e0e;
            --surface: rgba(26,25,25,.82);
            --surface-2: rgba(255,255,255,.03);
            --text: #ffffff;
            --text-muted: #adaaaa;
            --text-faint: #6b6b6b;
            --primary: #d2ff9a;
            --primary-strong: #9ffb06;
            --primary-dark: #3d6500;
            --secondary: #00e3fd;
            --danger: #ff8a8a;
            --border: rgba(72,72,71,.15);
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
                radial-gradient(circle at top right, rgba(210, 255, 154, 0.08), transparent 24%),
                linear-gradient(180deg, #050505 0%, #0e0e0e 100%);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        h1, h2, h3 {
            font-family: 'Space Grotesk', sans-serif;
        }

        .topbar {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 9999;
            min-height: 72px;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .brand {
            color: var(--primary);
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -.04em;
            text-transform: uppercase;
        }

        .topbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
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

        .main-content {
            padding: 24px 0 48px;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(18px);
        }

        .hero-copy,
        .hero-card,
        .section {
            padding: 28px;
        }

        .eyebrow {
            color: var(--primary);
            letter-spacing: .22em;
            text-transform: uppercase;
            font-size: .78rem;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .hero-copy h1 {
            font-size: clamp(2.2rem, 5vw, 4.8rem);
            line-height: .95;
            letter-spacing: -.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero-copy p,
        .note {
            color: var(--text-muted);
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 22px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            transition: transform .2s ease, opacity .2s ease, background .2s ease;
            min-height: 46px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-strong));
            color: var(--primary-dark);
        }

        .btn-secondary {
            border-color: var(--border-strong);
            color: var(--primary);
            background: rgba(255,255,255,.02);
        }

        .hero-card h2,
        .section h2 {
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: -.04em;
            margin-bottom: 14px;
        }

        .mini-data {
            margin-top: 18px;
            display: grid;
            gap: 10px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat {
            padding: 18px;
            border-radius: 18px;
            background: rgba(26,25,25,.82);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
        }

        .stat label {
            display: block;
            color: var(--text-faint);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .16em;
            margin-bottom: 12px;
        }

        .stat .value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.05em;
            word-break: break-word;
        }

        .stat .meta {
            margin-top: 8px;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .section {
            margin-bottom: 20px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
            gap: 20px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            border-radius: 999px;
            background: var(--surface-2);
            overflow: hidden;
            border: 1px solid var(--border);
            margin: 14px 0 18px;
        }

        .progress-bar > span {
            display: block;
            height: 100%;
            width: <?php echo $progreso; ?>%;
            background: linear-gradient(90deg, var(--danger), var(--primary));
        }

        .list {
            list-style: none;
            display: grid;
            gap: 12px;
        }

        .list li {
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            line-height: 1.6;
            word-break: break-word;
        }

        .list strong {
            color: var(--primary);
        }

        .meal {
            padding: 18px;
            border-radius: 16px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            margin-bottom: 14px;
        }

        .meal h3 {
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: .12em;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .meal ul {
            margin-left: 18px;
            color: var(--text-muted);
            display: grid;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .field {
            display: grid;
            gap: 8px;
            min-width: 0;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        .field label {
            color: var(--text-muted);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .14em;
        }

        .field input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-strong);
            background: rgba(10,10,10,.7);
            color: var(--text);
            outline: none;
            min-height: 48px;
        }

        .field input:focus {
            border-color: rgba(210,255,154,.5);
            box-shadow: 0 0 0 3px rgba(210,255,154,.08);
        }

        .footer {
            padding: 30px 16px 18px;
            color: var(--text-faint);
            text-align: center;
            font-size: .82rem;
        }

        @media (max-width: 1024px) {
            .hero,
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 12px 16px;
                align-items: flex-start;
                flex-direction: column;
            }

            .brand {
                font-size: 1.3rem;
            }

            .main-content {
                padding-top: 20px;
            }

            .container {
                width: min(100% - 20px, 1180px);
            }

            .hero-copy,
            .hero-card,
            .section {
                padding: 20px;
            }

            .hero-copy h1 {
                font-size: 2rem;
            }

            .hero-actions,
            .topbar-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .hero-actions .btn,
            .topbar-actions .ghost-btn {
                width: 100%;
            }

            .stats,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .stat .value {
                font-size: 1.6rem;
            }

            .section h2,
            .hero-card h2 {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: calc(100% - 16px);
            }

            .hero-copy h1 {
                font-size: 1.7rem;
            }

            .eyebrow {
                font-size: .68rem;
                letter-spacing: .18em;
            }

            .btn,
            .ghost-btn,
            .field input {
                font-size: .85rem;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a href="home_cliente.php" class="brand">FitPower</a>
        <div class="topbar-actions">
            <a href="home_cliente.php" class="ghost-btn">Volver</a>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="hero">
                <article class="panel hero-copy">
                    <div class="eyebrow">Alimentación / definición / pérdida de peso</div>
                    <h1>Plan para perder peso</h1>
                    <p>
                        <?php echo $has_data
                            ? 'Aquí tienes tu plan de definición con calorías estimadas, déficit moderado y comidas sugeridas para perder peso de forma gradual.'
                            : 'Completa primero tu encuesta de definición para generar tu plan personalizado.'; ?>
                    </p>
                    <div class="hero-actions">
                        <a href="#plan" class="btn btn-primary">Ver plan</a>
                        <a href="#formulario" class="btn btn-secondary"><?php echo $has_data ? 'Actualizar datos' : 'Crear encuesta'; ?></a>
                    </div>
                </article>

                <aside class="panel hero-card">
                    <h2><?php echo $nombre; ?></h2>
                    <p class="note">
                        <?php echo $has_data
                            ? 'Tu fase actual está enfocada en definición y control del déficit.'
                            : 'Cuando guardes tus datos, el sistema calculará TMB, mantenimiento y meta calórica de definición.'; ?>
                    </p>

                    <div class="mini-data">
                        <?php if ($objetivo_usuario !== ''): ?>
                            <p class="note"><strong style="color: var(--primary);">Objetivo:</strong> <?php echo $objetivo_usuario; ?></p>
                        <?php endif; ?>

                        <?php if ($has_data): ?>
                            <p class="note"><strong style="color: var(--primary);">Peso:</strong> <?php echo $peso; ?> kg</p>
                            <p class="note"><strong style="color: var(--primary);">Meta:</strong> <?php echo $meta; ?> kg</p>
                            <p class="note"><strong style="color: var(--primary);">Tiempo estimado:</strong> <?php echo $dias_estimados; ?> días</p>
                        <?php endif; ?>
                    </div>
                </aside>
            </section>

            <?php if ($has_data): ?>
                <section class="stats">
                    <article class="stat">
                        <label>Peso actual</label>
                        <div class="value"><?php echo number_format($peso, 1); ?> kg</div>
                        <div class="meta">Registro actual</div>
                    </article>

                    <article class="stat">
                        <label>Peso objetivo</label>
                        <div class="value"><?php echo number_format($meta, 1); ?> kg</div>
                        <div class="meta">Meta final</div>
                    </article>

                    <article class="stat">
                        <label>Calorías objetivo</label>
                        <div class="value"><?php echo round($objetivo_calorias); ?></div>
                        <div class="meta">kcal / día</div>
                    </article>

                    <article class="stat">
                        <label>Pérdida estimada</label>
                        <div class="value"><?php echo number_format(max(0, $kilos_perder), 1); ?> kg</div>
                        <div class="meta">Meta por bajar</div>
                    </article>
                </section>

                <section class="grid-2" id="plan">
                    <article class="panel section">
                        <h2>Resumen del plan</h2>
                        <p class="note"><strong style="color: var(--primary);">TMB:</strong> <?php echo round($tmb, 2); ?> kcal/día</p>
                        <p class="note"><strong style="color: var(--primary);">Mantenimiento:</strong> <?php echo round($mantenimiento, 2); ?> kcal/día</p>
                        <p class="note"><strong style="color: var(--primary);">Meta calórica:</strong> <?php echo round($objetivo_calorias, 2); ?> kcal/día</p>
                        <p class="note"><strong style="color: var(--primary);">Peso a perder:</strong> <?php echo max(0, $kilos_perder); ?> kg</p>
                        <p class="note"><strong style="color: var(--primary);">Tiempo estimado:</strong> <?php echo $dias_estimados; ?> días</p>

                        <div class="progress-bar"><span></span></div>

                        <ul class="list">
                            <li><strong>Press banca:</strong> <?php echo $banca !== '' ? $banca : '—'; ?> kg</li>
                            <li><strong>Sentadilla:</strong> <?php echo $sentadilla !== '' ? $sentadilla : '—'; ?> kg</li>
                            <li><strong>Peso muerto:</strong> <?php echo $peso_muerto !== '' ? $peso_muerto : '—'; ?> kg</li>
                        </ul>
                    </article>

                    <article class="panel section">
                        <h2>Plan alimenticio recomendado</h2>

                        <div class="meal">
                            <h3>Desayuno</h3>
                            <ul>
                                <li>Avena con claras y fruta.</li>
                                <li>Café o agua sin azúcar añadida.</li>
                            </ul>
                        </div>

                        <div class="meal">
                            <h3>Media mañana</h3>
                            <ul>
                                <li>Yogur griego light.</li>
                                <li>Porción pequeña de nueces.</li>
                            </ul>
                        </div>

                        <div class="meal">
                            <h3>Almuerzo</h3>
                            <ul>
                                <li>Pechuga de pollo o pescado.</li>
                                <li>Arroz integral o papa cocida.</li>
                                <li>Brócoli o ensalada grande.</li>
                            </ul>
                        </div>

                        <div class="meal">
                            <h3>Merienda</h3>
                            <ul>
                                <li>Batido de proteína.</li>
                                <li>Huevo cocido o fruta.</li>
                            </ul>
                        </div>

                        <div class="meal">
                            <h3>Cena</h3>
                            <ul>
                                <li>Ensalada con atún o pollo.</li>
                                <li>Aceite de oliva en porción moderada.</li>
                            </ul>
                        </div>
                    </article>
                </section>

                <section class="panel section" id="formulario">
                    <h2>Actualizar datos</h2>
                    <p class="note">Si cambió tu peso actual o tu meta, actualiza aquí para recalcular el plan.</p>

                    <form action="guardar_definicion.php" method="post" class="form-grid">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">

                        <div class="field">
                            <label for="peso">Peso actual (kg)</label>
                            <input id="peso" name="peso" type="number" step="0.1" required value="<?php echo htmlspecialchars((string)$peso); ?>">
                        </div>

                        <div class="field">
                            <label for="estatura">Estatura (cm)</label>
                            <input id="estatura" name="estatura" type="number" required value="<?php echo htmlspecialchars((string)$estatura); ?>">
                        </div>

                        <div class="field">
                            <label for="peso_objetivo">Peso objetivo (kg)</label>
                            <input id="peso_objetivo" name="peso_objetivo" type="number" step="0.1" required value="<?php echo htmlspecialchars((string)$meta); ?>">
                        </div>

                        <div class="field">
                            <label for="objetivo">Tu objetivo principal</label>
                            <input id="objetivo" name="objetivo" type="text" required value="<?php echo $objetivo_usuario; ?>">
                        </div>

                        <div class="field">
                            <label for="banca">Máximo en press banca (kg)</label>
                            <input id="banca" name="banca" type="number" required value="<?php echo $banca; ?>">
                        </div>

                        <div class="field">
                            <label for="sentadilla">Máximo en sentadilla (kg)</label>
                            <input id="sentadilla" name="sentadilla" type="number" required value="<?php echo $sentadilla; ?>">
                        </div>

                        <div class="field full">
                            <label for="peso_muerto">Máximo en peso muerto (kg)</label>
                            <input id="peso_muerto" name="peso_muerto" type="number" required value="<?php echo $peso_muerto; ?>">
                        </div>

                        <div class="field full">
                            <button type="submit" class="btn btn-primary">Actualizar y recalcular</button>
                        </div>
                    </form>
                </section>
            <?php else: ?>
                <section class="panel section" id="formulario">
                    <h2>Crear encuesta</h2>
                    <p class="note">Completa estos datos para generar tu plan de definición.</p>

                    <form action="guardar_definicion.php" method="post" class="form-grid">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">

                        <div class="field">
                            <label for="peso">Peso actual (kg)</label>
                            <input id="peso" name="peso" type="number" step="0.1" required>
                        </div>

                        <div class="field">
                            <label for="estatura">Estatura (cm)</label>
                            <input id="estatura" name="estatura" type="number" required>
                        </div>

                        <div class="field">
                            <label for="peso_objetivo">Peso objetivo (kg)</label>
                            <input id="peso_objetivo" name="peso_objetivo" type="number" step="0.1" required>
                        </div>

                        <div class="field">
                            <label for="objetivo">Tu objetivo principal</label>
                            <input id="objetivo" name="objetivo" type="text" required value="<?php echo $objetivo_usuario; ?>">
                        </div>

                        <div class="field">
                            <label for="banca">Máximo en press banca (kg)</label>
                            <input id="banca" name="banca" type="number" required value="<?php echo $banca; ?>">
                        </div>

                        <div class="field">
                            <label for="sentadilla">Máximo en sentadilla (kg)</label>
                            <input id="sentadilla" name="sentadilla" type="number" required value="<?php echo $sentadilla; ?>">
                        </div>

                        <div class="field full">
                            <label for="peso_muerto">Máximo en peso muerto (kg)</label>
                            <input id="peso_muerto" name="peso_muerto" type="number" required value="<?php echo $peso_muerto; ?>">
                        </div>

                        <div class="field full">
                            <button type="submit" class="btn btn-primary">Guardar y generar plan</button>
                        </div>
                    </form>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">&copy; 2025 FitPower Gym. Todos los derechos reservados.</footer>
</body>
</html>