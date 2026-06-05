<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Obtener clientes
$sql = "SELECT id, nombre FROM usuarios WHERE rol != 'admin'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia - FitPower</title>
    <link rel="icon" href="favicon.ico">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-surface-container-lowest text-on-surface font-body min-h-screen antialiased">

    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl bg-surface-container-low rounded-2xl border border-white/5 shadow-2xl overflow-hidden">
            <div class="p-8 md:p-10">
                <div class="mb-8">
                    <p class="text-primary uppercase tracking-[0.18em] text-xs font-bold mb-3">Attendance</p>
                    <h2 class="font-headline text-4xl md:text-5xl font-extrabold tracking-tighter uppercase">Registrar Asistencia</h2>
                    <p class="text-on-surface-variant mt-3">Controla ingresos por mensualidad o por día sin modificar el flujo actual.</p>
                </div>

                <form action="guardar_asistencia.php" method="POST" class="space-y-6">
                    <div>
                        <label for="tipo_pago" class="block text-xs uppercase tracking-[0.14em] text-on-surface-variant font-semibold mb-2">Tipo de pago</label>
                        <select name="tipo_pago" id="tipo_pago" onchange="toggleCampos()" required class="w-full rounded-xl border border-transparent bg-surface-container-highest px-4 py-4 text-on-surface focus:border-primary/20 focus:ring-2 focus:ring-primary/20">
                            <option value="">Seleccione...</option>
                            <option value="Mensualidad">Mensualidad</option>
                            <option value="Día">Día</option>
                        </select>
                    </div>

                    <div id="campo_mensual">
                        <label for="usuario_id" class="block text-xs uppercase tracking-[0.14em] text-on-surface-variant font-semibold mb-2">Cliente (registrado)</label>
                        <select name="usuario_id" id="usuario_id" class="w-full rounded-xl border border-transparent bg-surface-container-highest px-4 py-4 text-on-surface focus:border-primary/20 focus:ring-2 focus:ring-primary/20">
                            <option value="">Seleccione cliente</option>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div id="campo_dia" style="display: none;">
                        <label for="nombre_dia" class="block text-xs uppercase tracking-[0.14em] text-on-surface-variant font-semibold mb-2">Nombre del cliente por día</label>
                        <input type="text" id="nombre_dia" name="nombre_dia" placeholder="Ej: Pedro Díaz" class="w-full rounded-xl border border-transparent bg-surface-container-highest px-4 py-4 text-on-surface placeholder:text-on-surface-variant/50 focus:border-primary/20 focus:ring-2 focus:ring-primary/20">
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-primary to-primary-container px-6 py-4 font-headline text-lg font-bold uppercase tracking-tight text-on-primary transition hover:brightness-110 active:scale-[0.99]">
                        <span>Registrar asistencia</span>
                        <span class="material-symbols-outlined">fact_check</span>
                    </button>
                </form>

                <div class="mt-8 border-t border-white/5 pt-6 text-center">
                    <a href="admin.php" class="text-primary font-semibold hover:underline">← Volver al panel de administración</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCampos() {
            const tipo = document.getElementById("tipo_pago").value;
            document.getElementById("campo_mensual").style.display = tipo === "Mensualidad" ? "block" : "none";
            document.getElementById("campo_dia").style.display = tipo === "Día" ? "block" : "none";
        }
    </script>

</body>
</html>