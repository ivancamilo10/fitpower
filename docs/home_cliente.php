<?php 
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html class="dark" lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>FitPower Gym</title>
    <link rel="stylesheet" href="home_cliente.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap');

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

button {
    font: inherit;
    border: none;
    background: none;
}

h1, h2, h3, h4, h5, h6,
.brand,
.footer-brand {
    font-family: 'Space Grotesk', sans-serif;
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
}

.glass-panel {
    background: rgba(38, 38, 38, 0.6);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.gradient-text {
    background: none !important;
    -webkit-background-clip: initial;
    background-clip: initial;
    -webkit-text-fill-color: var(--primary) !important;
    color: var(--primary) !important;
    text-shadow: 0 0 18px rgba(210, 255, 154, 0.18);
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
    -webkit-backdrop-filter: blur(18px);
    border-bottom: 1px solid var(--border);
}

.topbar-left,
.topbar-right,
.topbar-nav {
    display: flex;
    align-items: center;
    gap: 16px;
}

.menu-icon {
    color: var(--primary);
    cursor: pointer;
}

.brand {
    color: var(--primary);
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    text-transform: uppercase;
}

.topbar-nav {
    gap: 28px;
}

.topbar-nav a {
    color: var(--text-faint);
    font-size: 0.76rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    font-weight: 800;
    transition: color 0.2s ease;
}

.topbar-nav a:hover,
.topbar-nav a.active {
    color: var(--primary);
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
    transition: background 0.2s ease, border-color 0.2s ease;
}

.ghost-btn:hover {
    background: var(--surface-low);
    border-color: rgba(210, 255, 154, 0.35);
}

.user-menu {
    position: relative;
    z-index: 10050;
}

.user-avatar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 14px;
    background: rgba(30, 30, 30, 0.82);
    border: 1px solid var(--border-strong);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: var(--glow);
    color: var(--text);
    position: relative;
    z-index: 10051;
}

.user-avatar:hover {
    border-color: rgba(210, 255, 154, 0.35);
    background: rgba(36, 36, 36, 0.95);
}

.user-name {
    white-space: nowrap;
}

.dropdown-arrow {
    color: var(--primary);
    font-size: 1.2rem;
    transition: transform 0.2s ease;
}

.user-menu.open .dropdown-arrow {
    transform: rotate(180deg);
}

.avatar {
    width: 34px;
    height: 34px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: var(--surface-highest);
    border: 1px solid var(--border-strong);
}

.dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    min-width: 230px;
    border-radius: 18px;
    background: rgba(18, 18, 18, 0.98);
    border: 1px solid var(--border-strong);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    box-shadow: var(--shadow-panel);
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: all 0.22s ease;
    z-index: 10060;
    pointer-events: none;
    padding: 8px 0;
}

.user-menu.open .dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}

.dropdown a {
    display: block;
    padding: 16px 18px;
    color: var(--text-muted);
    transition: all 0.2s ease;
}

.dropdown a:hover {
    background: rgba(210, 255, 154, 0.08);
    color: var(--primary);
}

.main-content {
    padding-top: 72px;
    padding-bottom: 110px;
}

.hero-section {
    position: relative;
    min-height: 795px;
    overflow: hidden;
    display: flex;
    align-items: center;
    background: var(--surface-lowest);
}

.hero-bg-grid {
    position: absolute;
    inset: 0;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    z-index: 0;
}

.hero-bg-left {
    grid-column: span 7;
    position: relative;
    background: var(--surface-low);
    min-height: 795px;
}

.hero-bg-right {
    grid-column: span 5;
    position: relative;
    background: var(--surface-lowest);
    min-height: 795px;
}

.hero-bg-image,
.card-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    opacity: 0.30;
    mix-blend-mode: luminosity;
}

.hero-bg-fade {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 80%;
    background: linear-gradient(to top, var(--surface-lowest), transparent);
    z-index: 2;
}

.hero-side-image {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    opacity: 0.34;
    mix-blend-mode: screen;
    filter: grayscale(1);
}

.hero-content {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 48px;
}

.hero-copy {
    width: 58%;
    padding-left: 0;
    text-align: left;
}

.hero-label {
    display: block;
    margin-bottom: 18px;
    color: var(--primary);
    font-size: 0.82rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    font-weight: 700;
}

.hero-copy h1 {
    font-size: clamp(2.8rem, 6vw, 5.4rem);
    line-height: 0.88;
    font-weight: 900;
    letter-spacing: -0.06em;
    text-transform: uppercase;
    margin-bottom: 24px;
    max-width: 11ch;
}

.hero-copy h1 .gradient-text {
    color: var(--primary);
    -webkit-text-fill-color: var(--primary);
    background: none;
    text-shadow: 0 0 18px rgba(210, 255, 154, 0.18);
}

.hero-copy p {
    max-width: 520px;
    color: var(--text-muted);
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 32px;
}

.hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.primary-btn,
.secondary-btn,
.cta-btn,
.round-btn,
.inline-link {
    transition: all 0.22s ease;
}

.primary-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 16px 28px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--primary-strong));
    color: var(--primary-dark);
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    box-shadow: 0 12px 30px rgba(159, 251, 6, 0.18);
}

.primary-btn:hover {
    opacity: 0.92;
    transform: translateY(-2px);
}

.secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 28px;
    border-radius: 10px;
    border: 1px solid var(--border-strong);
    color: var(--primary);
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
}

.secondary-btn:hover {
    background: var(--surface-low);
}

.hero-card {
    width: 38%;
    max-width: 360px;
    padding: 30px;
    border-radius: 18px;
    border: 1px solid var(--border);
    box-shadow: var(--glow);
    text-align: left;
}

.hero-card-head,
.hero-card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.hero-card-head {
    margin-bottom: 22px;
    color: var(--text-muted);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
}

.text-primary-icon,
.hero-card-user,
.menu-icon {
    color: var(--primary);
}

.hero-card-user {
    margin-bottom: 16px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    letter-spacing: -0.05em;
    color: var(--primary);
    text-transform: lowercase;
    line-height: 1;
}

.hero-card-bar {
    width: 100%;
    height: 4px;
    overflow: hidden;
    border-radius: 999px;
    background: var(--surface-highest);
    margin-bottom: 18px;
}

.hero-card-progress {
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, var(--secondary), var(--primary));
}

.hero-card-foot {
    justify-content: flex-start;
    gap: 10px;
    color: var(--text-muted);
    font-size: 0.92rem;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: var(--primary);
    box-shadow: 0 0 12px rgba(210, 255, 154, 0.7);
}

.content-grid-section {
    padding: 96px 24px;
    background: #0e0e0e;
}

.section-head {
    max-width: 1440px;
    margin: 0 auto 64px;
    display: flex;
    justify-content: space-between;
    gap: 24px;
}

.section-head h2 {
    font-size: clamp(2.3rem, 5vw, 4rem);
    line-height: 0.9;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.05em;
    margin-bottom: 14px;
}

.section-head p {
    max-width: 520px;
    color: var(--text-muted);
}

.bento-grid {
    max-width: 1440px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 16px;
    grid-auto-rows: 300px;
}

.card {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: var(--surface-low);
    box-shadow: var(--shadow-soft);
}

.card-large {
    grid-column: span 8;
    display: flex;
    align-items: flex-end;
    padding: 32px;
    min-height: 320px;
}

.card-medium {
    grid-column: span 4;
    display: flex;
    align-items: flex-end;
    padding: 32px;
    min-height: 320px;
}

.card-small {
    grid-column: span 5;
    padding: 32px;
    background: var(--surface-highest);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.card-wide {
    grid-column: span 7;
}

.card-bg {
    opacity: 0.38;
    mix-blend-mode: luminosity;
    transition: opacity 0.4s ease;
}

.card:hover .card-bg {
    opacity: 0.5;
}

.card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, var(--surface-lowest), rgba(0, 0, 0, 0.16));
}

.card-content {
    position: relative;
    z-index: 2;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 24px;
}

.card-content.stacked {
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-end;
    height: 100%;
}

.pill {
    display: inline-block;
    margin-bottom: 16px;
    padding: 7px 12px;
    border-radius: 999px;
    background: #006875;
    color: #e8fbff;
    font-size: 0.65rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    font-weight: 800;
}

.card h3 {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.04em;
    text-transform: uppercase;
    margin-bottom: 10px;
}

.card p {
    color: var(--text-muted);
    font-size: 0.95rem;
    max-width: 420px;
}

.round-btn {
    width: 48px;
    height: 48px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    background: var(--surface-highest);
    border: 1px solid var(--border-strong);
    color: var(--text);
    flex-shrink: 0;
}

.round-btn:hover {
    background: var(--primary);
    color: var(--primary-dark);
}

.accent-icon,
.inline-link {
    color: var(--primary);
}

.accent-icon {
    font-size: 2rem;
    margin-bottom: 16px;
}

.inline-link {
    display: inline-block;
    margin-top: 18px;
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.inline-link:hover {
    opacity: 0.85;
}

.cta-card {
    background: linear-gradient(135deg, var(--primary), var(--primary-strong));
    color: var(--primary-dark);
    padding: 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.cta-card p {
    color: #375b00;
    max-width: 420px;
}

.cta-btn {
    padding: 16px 26px;
    border-radius: 10px;
    background: #0e0e0e;
    color: var(--primary);
    font-size: 0.82rem;
    font-weight: 800;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    cursor: pointer;
}

.cta-btn:hover {
    background: #161616;
}

.mobile-nav {
    display: none;
}

.footer {
    width: 100%;
    padding: 48px 24px;
    background: #050505;
    margin-top: auto;
}

.footer-wrap {
    max-width: 1280px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
    justify-content: space-between;
}

.footer-brand {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--primary);
    letter-spacing: -0.04em;
    text-transform: uppercase;
}

.footer-links {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    justify-content: center;
}

.footer-links a,
.footer-copy {
    color: var(--text-faint);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
}

.footer-links a:hover {
    color: var(--primary);
}

@media (max-width: 1024px) {
    .topbar-nav {
        display: none;
    }

    .hero-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 32px;
        padding: 48px 24px;
    }

    .hero-copy,
    .hero-card {
        width: 100%;
        max-width: 100%;
        padding-left: 0;
    }

    .hero-bg-grid {
        grid-template-columns: 1fr;
    }

    .hero-bg-right {
        display: none;
    }

    .bento-grid {
        grid-template-columns: 1fr;
        grid-auto-rows: auto;
    }

    .card-large,
    .card-medium,
    .card-small,
    .card-wide {
        grid-column: span 1;
        min-height: 280px;
    }

    .cta-card {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .topbar {
        padding: 0 16px;
    }

    .ghost-btn {
        display: none;
    }

    .user-name {
        display: none;
    }

    .main-content {
        padding-bottom: 100px;
    }

    .hero-section {
        min-height: auto;
        padding-bottom: 40px;
    }

    .hero-bg-left,
    .hero-bg-right {
        min-height: 420px;
    }

    .hero-copy h1 {
        font-size: 3rem;
        max-width: 100%;
    }

    .hero-copy p {
        font-size: 1rem;
    }

    .content-grid-section {
        padding: 72px 16px;
    }

    .section-head {
        margin-bottom: 36px;
    }

    .mobile-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 999;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 12px 8px 28px;
        background: rgba(5, 5, 5, 0.88);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-top: 1px solid var(--border);
    }

    .mobile-nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: var(--text-faint);
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 8px 10px;
        border-radius: 12px;
    }

    .mobile-nav a.active,
    .mobile-nav a:hover {
        color: var(--primary);
        background: rgba(210, 255, 154, 0.08);
    }

    .footer {
        padding-bottom: 110px;
    }

    .hero-bg-image {
        background-position: center top;
        opacity: 0.24;
    }

    .hero-side-image {
        opacity: 0.22;
        object-position: center top;
    }

    .card-large,
    .card-medium,
    .card-small,
    .card-wide {
        min-height: 260px;
    }

    .user-menu {
        position: relative;
    }

    .user-avatar {
        width: 100%;
        justify-content: flex-start;
    }

    .dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        left: auto;
        min-width: 220px;
        width: max-content;
        max-width: calc(100vw - 32px);
        z-index: 10060;
    }

    .dropdown a {
        padding: 16px 18px;
        font-size: 0.95rem;
        text-align: left;
    }
}
</style>
<body>
    <header class="topbar">
        <div class="topbar-left">
            <span class="material-symbols-outlined menu-icon">menu</span>
            <a href="home_cliente.php" class="brand">FITPOWER</a>
        </div>

        <nav class="topbar-nav">
            <a class="active" href="rutinasgenerales.php">Rutinas</a>
            <a href="alimentacion_ganar.php">Ganar masa</a>
            <a href="alimentacion_perder.php">Perder peso</a>
            <a href="perfil.php">Perfil</a>
        </nav>

        <div class="topbar-right">
            <a href="rutinas.php" class="ghost-btn">Mis rutinas</a>

           <div class="user-photo-only">
    <div class="user-avatar static-avatar" aria-label="Usuario">
        <span class="avatar">👤</span>
    </div>
</div>
    </header>

    <main class="main-content">
        <section class="hero-section">
            <div class="hero-bg-grid">
                <div class="hero-bg-left">
                    <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1532029837206-abbe2b7620e3?auto=format&fit=crop&q=80&w=1400');"></div>
                </div>
                <div class="hero-bg-right">
                    <div class="hero-bg-fade"></div>
                   <div class="card-bg" style="background-image: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?auto=format&fit=crop&q=80&w=1400');"></div>
                </div>
            </div>

            <div class="hero-content">
                <div class="hero-copy">
                    <span class="hero-label">FitPower Performance System</span>
                    <h1>
                        Bienvenido/a,<br>
                        <span class="gradient-text"><?php echo $_SESSION['nombre'] ?? 'atleta'; ?></span>
                    </h1>
                    <p>
                        Accede a tus rutinas, planes de alimentación y herramientas para mejorar tu rendimiento físico con una experiencia visual moderna y enfocada.
                    </p>
                    <div class="hero-actions">
                        <a href="rutinasgenerales.php" class="primary-btn">
                            Ver rutinas
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                        <a href="rutinas.php" class="secondary-btn">Mis rutinas</a>
                    </div>
                </div>

                <div class="hero-card glass-panel">
                    <div class="hero-card-head">
                        <span>Acceso del usuario</span>
                        <span class="material-symbols-outlined text-primary-icon">verified_user</span>
                    </div>
                    <div class="hero-card-user"><?php echo $_SESSION['nombre'] ?? 'Usuario'; ?></div>
                    <div class="hero-card-bar">
                        <div class="hero-card-progress"></div>
                    </div>
                    <div class="hero-card-foot">
                        <div class="pulse-dot"></div>
                        <span>Sesión activa correctamente</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-grid-section">
            <div class="section-head">
                <div>
                    <h2>Servicios<br>disponibles</h2>
                    <p>Explora tus accesos principales dentro de FitPower Gym sin cambiar la lógica actual de tu sistema.</p>
                </div>
            </div>

            <div class="bento-grid">
                <div class="card card-large">
                    <div class="hero-bg-image" style="background-image: url('https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80&w=1600');"></div>
                    <div class="card-overlay"></div>
                    <div class="card-content">
                        <div>
                            <div class="pill">Entrenamiento</div>
                            <h3>Rutinas de entrenamiento</h3>
                            <p>Encuentra rutinas para fuerza, hipertrofia, resistencia o perder grasa adaptadas a tu nivel.</p>
                        </div>
                        <a href="rutinasgenerales.php" class="round-btn">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <div class="card card-medium">
                   <img alt="Gym" class="hero-side-image" src="https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&q=80&w=1400"/>
                    <div class="card-overlay"></div>
                    <div class="card-content stacked">
                        <span class="material-symbols-outlined accent-icon">restaurant</span>
                        <h3>Ganar masa muscular</h3>
                        <p>Planifica tu alimentación con recomendaciones orientadas al aumento muscular.</p>
                        <a href="alimentacion_ganar.php" class="inline-link">Ir ahora</a>
                    </div>
                </div>

                <div class="card card-small solid-card">
                    <div>
                        <span class="material-symbols-outlined accent-icon">monitor_weight</span>
                        <h3>Perder peso</h3>
                        <p>Accede a planes alimenticios bajos en calorías para perder peso de forma saludable.</p>
                    </div>
                    <a href="alimentacion_perder.php" class="inline-link">Ver plan</a>
                </div>

                <div class="card card-wide cta-card">
                    <div class="cta-copy">
                        <h3>Test de fuerza</h3>
                        <p>Responde según tu nivel actual con press banca, sentadilla y peso muerto.</p>
                    </div>
                    <button onclick="testFuerza()" class="cta-btn">Responder ahora</button>
                </div>
            </div>
        </section>
    </main>

    <nav class="mobile-nav">
        <a class="active" href="rutinasgenerales.php">
            <span class="material-symbols-outlined">fitness_center</span>
            <span>Rutinas</span>
        </a>
        <a href="alimentacion_ganar.php">
            <span class="material-symbols-outlined">restaurant</span>
            <span>Masa</span>
        </a>
        <a href="alimentacion_perder.php">
            <span class="material-symbols-outlined">monitor_weight</span>
            <span>Peso</span>
        </a>
        <a href="perfil.php">
            <span class="material-symbols-outlined">person</span>
            <span>Perfil</span>
        </a>
    </nav>

    <footer class="footer">
        <div class="footer-wrap">
            <div class="footer-brand">FITPOWER</div>
            <div class="footer-links">
                <a href="perfil.php">Perfil</a>
                <a href="rutinas.php">Mis rutinas</a>
                <a href="rutinasgenerales.php">Rutinas</a>
                <a href="logout.php">Cerrar sesión</a>
            </div>
            <div class="footer-copy">
                &copy; 2025 FitPower Gym. Todos los derechos reservados.
            </div>
        </div>
    </footer>

    <script>
        function testFuerza() {
            let banca = parseInt(prompt("¿Cuánto levantas en press banca (kg)?"));
            let sentadilla = parseInt(prompt("¿Cuánto levantas en sentadilla (kg)?"));
            let pesoMuerto = parseInt(prompt("¿Cuánto levantas en peso muerto (kg)?"));

            let bancaNivel = banca < 60 ? "Eres un flaco" : banca < 100 ? "Estás fuerte" : banca < 200 ? "Eres una bestia" : "Eres una máquina";
            let sentadillaNivel = sentadilla < 80 ? "Nivel básico" : sentadilla < 150 ? "Fuerte" : sentadilla < 250 ? "Bestia" : "Titan";
            let pesoMuertoNivel = pesoMuerto < 100 ? "Iniciado" : pesoMuerto < 180 ? "Avanzado" : pesoMuerto < 280 ? "Monstruo" : "Leyenda";

            alert(`Resultados:\n\nPress banca: ${banca} kg - ${bancaNivel}\nSentadilla: ${sentadilla} kg - ${sentadillaNivel}\nPeso Muerto: ${pesoMuerto} kg - ${pesoMuertoNivel}`);
        }

        const userMenu = document.getElementById("userMenu");
        const userMenuButton = document.getElementById("userMenuButton");

        userMenuButton.addEventListener("click", function (e) {
            e.stopPropagation();
            userMenu.classList.toggle("open");
            userMenuButton.setAttribute("aria-expanded", userMenu.classList.contains("open") ? "true" : "false");
        });

        document.addEventListener("click", function (e) {
            if (!userMenu.contains(e.target)) {
                userMenu.classList.remove("open");
                userMenuButton.setAttribute("aria-expanded", "false");
            }
        });

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                userMenu.classList.remove("open");
                userMenuButton.setAttribute("aria-expanded", "false");
            }
        });
    </script>
</body>
</html>