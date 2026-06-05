<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Total de usuarios
$sqlUsuarios = "SELECT COUNT(*) AS total FROM usuarios WHERE rol != 'admin'";
$resultUsuarios = $conn->query($sqlUsuarios);
$totalUsuarios = $resultUsuarios->fetch_assoc()['total'];

// Lista de usuarios con fecha de fin de mensualidad
$sqlFechas = "SELECT id, nombre, fecha_fin_mensualidad FROM usuarios WHERE rol != 'admin'";
$resultFechas = $conn->query($sqlFechas);

// Top 5 pagadores
$sqlTopPagadores = "
    SELECT u.nombre, SUM(p.monto) AS total_pagado
    FROM pagos p
    INNER JOIN usuarios u ON u.id = p.usuario_id
    GROUP BY p.usuario_id
    ORDER BY total_pagado DESC
    LIMIT 5
";
$topPagadores = $conn->query($sqlTopPagadores);
?>
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - FitPower</title>
    <link rel="icon" href="favicon.ico">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#d2ff9a",
                        "primary-container": "#9ffb06",
                        surface: "#0e0e0e",
                        "surface-container": "#1a1919",
                        "surface-container-low": "#131313",
                        "surface-container-high": "#201f1f",
                        "surface-container-highest": "#262626",
                        "surface-container-lowest": "#000000",
                        "on-surface": "#ffffff",
                        "on-surface-variant": "#adaaaa",
                        secondary: "#00e3fd",
                        error: "#ff7351",
                        outline: "#767575",
                        "outline-variant": "#484847",
                        "on-primary": "#3d6500"
                    },
                    fontFamily: {
                        headline: ["Space Grotesk", "sans-serif"],
                        body: ["Manrope", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        body {
            min-height: 100vh;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .ghost-border {
            border: 1px solid rgba(255,255,255,0.08);
        }

        .table-dark {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }

        .table-dark th {
            background: #181818;
            color: #adaaaa;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .12em;
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .table-dark td {
            padding: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            color: white;
        }

        .table-dark tr:hover {
            background: rgba(255,255,255,0.02);
        }

        .link-action {
            text-decoration: none;
            margin-right: 10px;
        }

        .pagination a,
        .pagination strong {
            display: inline-block;
            margin: 0 6px;
            color: #d2ff9a;
            text-decoration: none;
        }

        .pagination strong {
            color: white;
        }

        .search-input {
            background: #262626;
            color: white;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 12px 14px;
            outline: none;
            width: 240px;
        }

        .search-input:focus {
            border-color: rgba(210,255,154,0.3);
            box-shadow: 0 0 0 2px rgba(210,255,154,0.12);
        }

        .btn-k {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-decoration: none;
            transition: .2s ease;
        }

        .btn-k:hover {
            opacity: .92;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-surface-container-lowest text-on-surface font-body antialiased">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="hidden md:flex w-72 bg-black border-r border-white/5 flex-col p-6">
            <div class="mb-10">
                <h2 class="font-headline text-3xl font-black tracking-tighter text-primary uppercase">Kinetic</h2>
                <p class="text-on-surface-variant text-sm mt-2">FitPower Admin</p>
            </div>

            <nav class="space-y-2">
                <a href="home_cliente.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gradient-to-r from-primary to-primary-container text-black font-bold">
                    <span class="material-symbols-outlined">dashboard</span>
                    Dashboard
                </a>
                <a href="registrar_pago.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition">
                    <span class="material-symbols-outlined">payments</span>
                    Registrar pago
                </a>
                <a href="registrar_asistencia.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition">
                    <span class="material-symbols-outlined">fact_check</span>
                    Asistencia
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-white/5 hover:text-white transition">
                    <span class="material-symbols-outlined">logout</span>
                    Cerrar sesión
                </a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1 p-4 md:p-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <header class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div>
                        <h1 class="font-headline text-4xl md:text-6xl font-extrabold tracking-tighter uppercase">Panel Admin</h1>
                        <p class="text-on-surface-variant uppercase tracking-[0.18em] text-xs md:text-sm mt-2">FitPower Control Center</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="registrar_pago.php" class="btn-k bg-gradient-to-br from-primary to-primary-container text-on-primary">Registrar nuevo pago</a>
                        <a href="registrar_asistencia.php" class="btn-k border border-white/10 text-primary hover:bg-white/5">Registrar asistencia</a>
                    </div>
                </header>

                <!-- Stats -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-surface-container-low rounded-xl p-6">
                        <p class="text-on-surface-variant text-xs uppercase tracking-[0.12em]">Clientes registrados</p>
                        <h2 class="font-headline text-5xl font-bold mt-2"><?php echo $totalUsuarios; ?></h2>
                    </div>

                    <div class="bg-surface-container-low rounded-xl p-6">
                        <p class="text-on-surface-variant text-xs uppercase tracking-[0.12em]">Sesión actual</p>
                        <h2 class="font-headline text-3xl font-bold mt-2"><?php echo $_SESSION['nombre']; ?></h2>
                    </div>

                    <div class="bg-surface-container rounded-xl p-6 border border-white/5">
                        <p class="text-on-surface-variant text-xs uppercase tracking-[0.12em]">Estado</p>
                        <h2 class="font-headline text-3xl font-bold mt-2 text-primary">Admin activo</h2>
                    </div>
                </section>

                <!-- Chart -->
                <section class="bg-surface-container-low rounded-xl p-6">
                    <h2 class="font-headline text-2xl font-bold uppercase tracking-tight mb-6">Clientes que más han pagado</h2>
                    <div class="relative h-[320px]">
                        <canvas id="graficaPagos"></canvas>
                    </div>
                </section>

                <!-- Vencimientos -->
                <section class="bg-surface-container-low rounded-xl p-6">
                    <h2 class="font-headline text-2xl font-bold uppercase tracking-tight mb-6">Vencimiento de mensualidades</h2>
                    <div class="overflow-x-auto">
                        <table class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha fin</th>
                                <th>Días restantes</th>
                                <th>Acciones</th>
                            </tr>
                            <?php
                            $hoy = new DateTime();
                            while ($fila = $resultFechas->fetch_assoc()) {
                                $fin = new DateTime($fila['fecha_fin_mensualidad']);
                                $restante = $hoy->diff($fin)->format('%r%a');
                                $diasTexto = $restante >= 0 ? "$restante días" : "VENCIDA";

                                echo "<tr>
                                        <td>{$fila['nombre']}</td>
                                        <td>{$fila['fecha_fin_mensualidad']}</td>
                                        <td>$diasTexto</td>
                                        <td>
                                            <a class='link-action' href='editar_usuario.php?id={$fila['id']}' style='color:#d2ff9a;'>✏️ Editar</a>
                                            <a class='link-action' href='eliminar_usuario.php?id={$fila['id']}' style='color:#ff7351;' onclick='return confirm(\"¿Estás seguro de eliminar a este cliente?\")'>🗑️ Eliminar</a>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </table>
                    </div>
                </section>

                <!-- Asistencias -->
                <section class="bg-surface-container-low rounded-xl p-6">
                    <h2 class="font-headline text-2xl font-bold uppercase tracking-tight mb-6">Historial de Asistencias</h2>

                    <form method="GET" class="mb-6 flex flex-wrap gap-3" style="color: black">
                        <input style="color:black"
                            class="search-input"
                            type="text"
                            name="buscar"
                            placeholder="Buscar cliente..."
                            value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>"
                        >
                        <button type="submit" class="btn-k bg-gradient-to-br from-primary to-primary-container text-on-primary">Buscar</button>
                    </form>

                    <?php
                    $limite = 7;
                    $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $inicio = ($pagina - 1) * $limite;
                    $buscar = isset($_GET['buscar']) ? $conn->real_escape_string($_GET['buscar']) : "";

                    $sqlTotal = "
                        SELECT COUNT(*) AS total
                        FROM asistencias a
                        LEFT JOIN usuarios u ON u.id = a.usuario_id
                        WHERE COALESCE(u.nombre, a.nombre_dia) LIKE '%$buscar%'
                    ";
                    $resTotal = $conn->query($sqlTotal);
                    $totalFilas = $resTotal->fetch_assoc()['total'];
                    $totalPaginas = ceil($totalFilas / $limite);

                    $sqlAsistencias = "
                        SELECT a.id, a.fecha, a.tipo_pago, COALESCE(u.nombre, a.nombre_dia) AS nombre
                        FROM asistencias a
                        LEFT JOIN usuarios u ON u.id = a.usuario_id
                        WHERE COALESCE(u.nombre, a.nombre_dia) LIKE '%$buscar%'
                        ORDER BY a.fecha DESC
                        LIMIT $inicio, $limite
                    ";
                    $resAsistencias = $conn->query($sqlAsistencias);
                    ?>

                    <div class="overflow-x-auto">
                        <table class="table-dark">
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Tipo de Pago</th>
                                <th>Acciones</th>
                            </tr>
                            <?php while ($row = $resAsistencias->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['nombre'] ?></td>
                                    <td><?= $row['fecha'] ?></td>
                                    <td><?= $row['tipo_pago'] ?></td>
                                    <td>
                                        <a class="link-action" href="eliminar_asistencia.php?id=<?= $row['id'] ?>" style="color:#ff7351;" onclick="return confirm('¿Estás seguro de eliminar esta asistencia?')">🗑️ Eliminar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>

                    <div class="pagination text-center mt-6">
                        <?php if ($pagina > 1): ?>
                            <a href="?pagina=<?= $pagina - 1 ?>&buscar=<?= $buscar ?>">&laquo; Anterior</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <?php if ($i == $pagina): ?>
                                <strong><?= $i ?></strong>
                            <?php else: ?>
                                <a href="?pagina=<?= $i ?>&buscar=<?= $buscar ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="?pagina=<?= $pagina + 1 ?>&buscar=<?= $buscar ?>">Siguiente &raquo;</a>
                        <?php endif; ?>
                    </div>
                </section>

                <footer class="text-center text-on-surface-variant text-sm py-6">
                    <p>&copy; 2025 FitPower Gym. Todos los derechos reservados.</p>
                </footer>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('graficaPagos').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php
                    $labels = [];
                    $topPagadores->data_seek(0);
                    while($row = $topPagadores->fetch_assoc()) {
                        $labels[] = '"' . $row['nombre'] . '"';
                    }
                    echo implode(',', $labels);
                ?>],
                datasets: [{
                    label: 'Total Pagado ($)',
                    data: [<?php
                        $topPagadores->data_seek(0);
                        $datos = [];
                        while($row = $topPagadores->fetch_assoc()) {
                            $datos[] = $row['total_pagado'];
                        }
                        echo implode(',', $datos);
                    ?>],
                    backgroundColor: '#A3FF12',
                    borderColor: '#9ffb06',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#adaaaa' },
                        grid: { color: 'rgba(255,255,255,0.06)' }
                    },
                    x: {
                        ticks: { color: '#adaaaa' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>