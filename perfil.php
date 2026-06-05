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
    'nombre' => $_SESSION['nombre'] ?? 'Usuario',
    'objetivo' => '',
    'peso' => null,
    'banca' => null,
    'sentadilla' => null,
    'peso_muerto' => null
];

$stmt_user = $conn->prepare("SELECT nombre, objetivo, peso, banca, sentadilla, peso_muerto FROM usuarios WHERE id = ?");
$stmt_user->bind_param("i", $usuario_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
if ($row = $res_user->fetch_assoc()) {
    $usuario = array_merge($usuario, $row);
}

$stmt = $conn->prepare("SELECT peso, estatura, meta_peso FROM masa_muscular WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$has_data = (bool)$data;
$peso = $has_data ? (float)$data['peso'] : 0;
$estatura = $has_data ? (float)$data['estatura'] : 0;
$meta = $has_data ? (float)$data['meta_peso'] : 0;

$tmb = 0;
$mantenimiento = 0;
$objetivo_calorias = 0;
$kilos_subir = 0;
$dias_estimados = 0;
$estado = 'Pendiente';
$estado_desc = 'Aún no has completado tu encuesta corporal.';
$progress = 0;
$balance = 0;

if ($has_data && $peso > 0 && $estatura > 0 && $meta > 0) {
    $tmb = (10 * $peso) + (6.25 * $estatura) - (5 * 25) + 5;
    $mantenimiento = $tmb * 1.55;
    $objetivo_calorias = $mantenimiento + 500;
    $kilos_subir = $meta - $peso;
    $dias_estimados = max(0, round(abs($kilos_subir) * 15));
    $progress = min(100, max(0, ($peso / max($meta, 1)) * 100));
    $balance = $meta - $peso;

    if ($balance > 0) {
        $estado = 'Volumen';
        $estado_desc = 'Estás en fase de ganancia de masa muscular.';
    } elseif ($balance < 0) {
        $estado = 'Definición';
        $estado_desc = 'Tu meta está por debajo de tu peso actual.';
    } else {
        $estado = 'Mantenimiento';
        $estado_desc = 'Tu peso actual coincide con tu meta.';
    }
}

$nombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
$objetivo = htmlspecialchars($usuario['objetivo'] ?? '');
$banca = htmlspecialchars((string)($usuario['banca'] ?? ''));
$sentadilla = htmlspecialchars((string)($usuario['sentadilla'] ?? ''));
$peso_muerto = htmlspecialchars((string)($usuario['peso_muerto'] ?? ''));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Kinetic - FitPower</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0e0e0e;
            --surface-lowest: #000000;
            --surface-low: #131313;
            --surface: #1a1919;
            --surface-high: #201f1f;
            --surface-highest: #262626;
            --text: #ffffff;
            --text-muted: #adaaaa;
            --text-faint: #6b6b6b;
            --primary: #d2ff9a;
            --primary-strong: #9ffb06;
            --primary-dark: #3d6500;
            --secondary: #00e3fd;
            --border: rgba(72, 72, 71, 0.15);
            --border-strong: rgba(72, 72, 71, 0.28);
            --shadow-soft: 0 8px 30px rgba(0, 0, 0, 0.28);
            --shadow-panel: 0 20px 50px rgba(0, 0, 0, 0.35);
            --glow: 0 0 32px rgba(210, 255, 154, 0.06);
            --radius-md: 16px;
            --radius-lg: 22px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
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

        button, input, select, textarea {
            font: inherit;
        }

        h1, h2, h3, .brand {
            font-family: 'Space Grotesk', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
        }

        .topbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            height: 72px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0.88);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .topbar-left,
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand {
            color: var(--primary);
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -.04em;
            text-transform: uppercase;
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
        }

        .ghost-btn:hover {
            background: var(--surface-low);
            border-color: rgba(210,255,154,.35);
        }

        .main-content {
            padding-top: 92px;
            padding-bottom: 48px;
        }

        .container {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.3fr .9fr;
            gap: 20px;
            align-items: stretch;
            margin-bottom: 20px;
        }

        .panel {
            background: rgba(26,25,25,.82);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow-soft);
            backdrop-filter: blur(18px);
        }

        .hero-copy {
            padding: 28px;
            position: relative;
            overflow: hidden;
        }

        .hero-copy::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(210,255,154,.12), transparent 32%),
                linear-gradient(135deg, rgba(255,255,255,.04), transparent 52%);
            pointer-events: none;
        }

        .eyebrow {
            position: relative;
            z-index: 1;
            color: var(--primary);
            letter-spacing: .22em;
            text-transform: uppercase;
            font-size: .78rem;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .hero-copy h1 {
            position: relative;
            z-index: 1;
            font-size: clamp(2.4rem, 5vw, 4.8rem);
            line-height: .92;
            letter-spacing: -.06em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero-copy p {
            position: relative;
            z-index: 1;
            max-width: 62ch;
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 22px;
        }

        .hero-actions {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid transparent;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            transition: transform .2s ease, background .2s ease, border-color .2s ease, opacity .2s ease;
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

        .hero-card {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100%;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .avatar {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: var(--surface-highest);
            border: 1px solid var(--border-strong);
            box-shadow: var(--glow);
            font-size: 1.2rem;
        }

        .user-chip h2 {
            font-size: 1.5rem;
            letter-spacing: -.04em;
            text-transform: uppercase;
        }

        .user-chip p {
            color: var(--text-muted);
            font-size: .9rem;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            color: var(--text-muted);
            font-size: .92rem;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--primary);
            box-shadow: 0 0 12px rgba(210,255,154,.8);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .card {
            grid-column: span 3;
            padding: 20px;
            border-radius: 20px;
            background: rgba(26,25,25,.82);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
        }

        .card label {
            display: block;
            color: var(--text-faint);
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .16em;
            margin-bottom: 12px;
        }

        .card .value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -.05em;
        }

        .card .meta {
            margin-top: 8px;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .wide {
            grid-column: span 7;
        }

        .tall {
            grid-column: span 5;
        }

        .section {
            padding: 24px;
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 1.6rem;
            letter-spacing: -.04em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .progress-wrap {
            display: grid;
            gap: 14px;
        }

        .progress-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: baseline;
            flex-wrap: wrap;
        }

        .progress-top p {
            color: var(--text-muted);
        }

        .bar {
            width: 100%;
            height: 12px;
            border-radius: 999px;
            background: var(--surface-highest);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .bar > span {
            display: block;
            height: 100%;
            width: <?= (int)round($progress) ?>%;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
        }

        .stats-list {
            list-style: none;
            display: grid;
            gap: 12px;
        }

        .stats-list li {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 14px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
        }

        .stats-list span:first-child {
            color: var(--text-muted);
        }

        .stats-list strong {
            color: var(--text);
        }

        .recipe {
            padding: 18px;
            border-radius: 16px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
        }

        .recipe h3 {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .recipe ul {
            margin-left: 18px;
            color: var(--text-muted);
            display: grid;
            gap: 8px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field label {
            color: var(--text-muted);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .14em;
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--border-strong);
            background: rgba(10,10,10,.7);
            color: var(--text);
            outline: none;
        }

        .field textarea {
            min-height: 110px;
            resize: vertical;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: rgba(210,255,154,.5);
            box-shadow: 0 0 0 3px rgba(210,255,154,.08);
        }

        .full {
            grid-column: 1 / -1;
        }

        .note {
            font-size: .9rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer {
            padding: 30px 0 18px;
            color: var(--text-faint);
            text-align: center;
            font-size: .82rem;
        }

        @media (max-width: 1024px) {
            .hero,
            .grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .card,
            .wide,
            .tall {
                grid-column: auto;
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 0 16px;
            }

            .main-content {
                padding-top: 88px;
            }

            .container {
                width: min(100% - 20px, 1180px);
            }

            .hero-copy h1 {
                font-size: 2.4rem;
            }

            .card .value {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-left">
            <a href="home_cliente.php" class="brand">FitPower</a>
        </div>
        <div class="topbar-right">
            <a href="home_cliente.php" class="ghost-btn">Volver</a>
            <a href="logout.php" class="ghost-btn">Salir</a>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="hero">
                <article class="panel hero-copy">
                    <div class="eyebrow">Perfil / rendimiento / masa muscular</div>
                    <h1><?php echo $nombre; ?></h1>
                    <p><?php echo $has_data ? 'Aquí ves tu progreso, calorías y estado de entrenamiento en una sola pantalla.' : 'Completa tu encuesta inicial para generar tu plan de masa muscular.'; ?></p>
                    <div class="hero-actions">
                        <a href="#plan" class="btn btn-primary">Ver plan</a>
                        <a href="#editar" class="btn btn-secondary"><?php echo $has_data ? 'Editar datos' : 'Crear encuesta'; ?></a>
                    </div>
                </article>

                <aside class="panel hero-card">
                    <div>
                        <div class="user-chip">
                            <div class="avatar">👤</div>
                            <div>
                                <h2><?php echo $nombre; ?></h2>
                                <p>Objetivo: <?php echo $objetivo !== '' ? $objetivo : 'Sin definir'; ?></p>
                            </div>
                        </div>
                        <div class="status">
                            <span class="dot"></span>
                            <span><?php echo $estado; ?> · <?php echo $estado_desc; ?></span>
                        </div>
                    </div>
                    <div>
                        <p class="note">Usa esta vista como centro de control: progreso, métricas, plan y edición rápida.</p>
                    </div>
                </aside>
            </section>

            <section class="grid">
                <article class="card">
                    <label>Peso actual</label>
                    <div class="value"><?php echo $has_data ? number_format($peso, 1) : '—'; ?> kg</div>
                    <div class="meta">Registro actual</div>
                </article>

                <article class="card">
                    <label>Meta de peso</label>
                    <div class="value"><?php echo $has_data ? number_format($meta, 1) : '—'; ?> kg</div>
                    <div class="meta">Objetivo final</div>
                </article>

                <article class="card">
                    <label>Calorías objetivo</label>
                    <div class="value"><?php echo $has_data ? round($objetivo_calorias) : '—'; ?></div>
                    <div class="meta">kcal / día</div>
                </article>

                <article class="card">
                    <label>Progreso</label>
                    <div class="value"><?php echo $has_data ? round($progress) : '—'; ?>%</div>
                    <div class="meta">Avance hacia tu meta</div>
                </article>
            </section>

            <section class="grid">
                <article class="panel section wide" id="plan">
                    <h2>Progreso del plan</h2>
                    <div class="progress-wrap">
                        <div class="progress-top">
                            <p><strong>Estado:</strong> <?php echo $estado; ?></p>
                            <p><strong>Tiempo estimado:</strong> <?php echo $has_data ? $dias_estimados . ' días' : '—'; ?></p>
                        </div>

                        <div class="bar"><span></span></div>

                        <ul class="stats-list">
                            <li><span>TMB</span><strong><?php echo $has_data ? round($tmb) . ' kcal/día' : '—'; ?></strong></li>
                            <li><span>Mantenimiento</span><strong><?php echo $has_data ? round($mantenimiento) . ' kcal/día' : '—'; ?></strong></li>
                            <li><span>Diferencia con meta</span><strong><?php echo $has_data ? ($balance >= 0 ? '+' : '') . number_format($balance, 1) . ' kg' : '—'; ?></strong></li>
                            <li><span>Press banca / sentadilla / muerto</span><strong><?php echo $banca !== '' ? $banca : '—'; ?> / <?php echo $sentadilla !== '' ? $sentadilla : '—'; ?> / <?php echo $peso_muerto !== '' ? $peso_muerto : '—'; ?> kg</strong></li>
                        </ul>
                    </div>
                </article>

                <article class="panel section tall">
                    <h2>Plan alimenticio</h2>
                    <div class="recipe">
                        <h3>Recomendado</h3>
                        <ul>
                            <li>Desayuno: 4 claras, 2 huevos, 2 panes integrales, 1 banano.</li>
                            <li>Media mañana: batido de proteína y avena.</li>
                            <li>Almuerzo: arroz, pollo y aguacate.</li>
                            <li>Merienda: yogur griego y nueces.</li>
                            <li>Cena: pasta, carne y vegetales.</li>
                            <li>Antes de dormir: leche o caseína.</li>
                        </ul>
                    </div>

                    <div class="actions">
                        <a href="rutinasgenerales.php" class="btn btn-secondary">Ver rutinas</a>
                        <a href="alimentacion_ganar.php" class="btn btn-primary">Abrir alimentación</a>
                    </div>
                </article>
            </section>

            <section class="grid">
                <article class="panel section wide" id="editar">
                    <h2><?php echo $has_data ? 'Actualizar datos' : 'Crear encuesta'; ?></h2>
                    <p class="note"><?php echo $has_data ? 'Puedes editar tus datos y recalcular el plan desde aquí.' : 'Llena tus datos para generar tu perfil de masa muscular.'; ?></p>

                    <form action="guardar_masa.php" method="post" class="form-grid" style="margin-top:16px;">
                        <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">

                        <div class="field">
                            <label for="peso">Peso actual (kg)</label>
                            <input id="peso" name="peso" type="number" step="0.1" required value="<?php echo $has_data ? htmlspecialchars((string)$peso) : ''; ?>">
                        </div>

                        <div class="field">
                            <label for="estatura">Estatura (cm)</label>
                            <input id="estatura" name="estatura" type="number" required value="<?php echo $has_data ? htmlspecialchars((string)$estatura) : ''; ?>">
                        </div>

                        <div class="field">
                            <label for="meta_peso">Meta de peso (kg)</label>
                            <input id="meta_peso" name="meta_peso" type="number" step="0.1" required value="<?php echo $has_data ? htmlspecialchars((string)$meta) : ''; ?>">
                        </div>

                        <div class="field">
                            <label for="objetivo">Tu objetivo principal</label>
                            <input id="objetivo" name="objetivo" type="text" required value="<?php echo htmlspecialchars((string)$usuario['objetivo']); ?>">
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
                            <button type="submit" class="btn btn-primary"><?php echo $has_data ? 'Actualizar y recalcular' : 'Guardar y generar plan'; ?></button>
                        </div>
                    </form>
                </article>
            </section>
        </div>
    </main>

    <footer class="footer">
        &copy; 2025 FitPower Gym. Todos los derechos reservados.
    </footer>
</body>
</html>