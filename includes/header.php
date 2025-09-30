<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Steam HRG - <?php echo isset($pageTitle) ? $pageTitle : 'Tu plataforma de videojuegos'; ?></title>
    <link rel="stylesheet" href="/ProyectoSteamHRG/style.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="/ProyectoSteamHRG/accessibility.js"></script>
    <script src="/ProyectoSteamHRG/session-manager.js"></script>
</head>
<body<?php if (isset($_SESSION['usuario'])): ?> class="logged-in"<?php endif; ?>>
    <div class="app">
        <header class="header">
            <div class="header-content">
                <div class="logo">
                    <span class="logo-title">Steam HRG</span>
                    <span class="logo-subtitle">Tu plataforma de videojuegos</span>
                </div>
                <div class="header-controls">
                    <div class="auth-buttons">
                        <?php if (isset($_SESSION['usuario'])): ?>
                        <a href="index.php" class="btn btn-primary">Inicio</a>
                        <a href="biblioteca.php" class="btn btn-primary">Biblioteca</a>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <a href="admin.php" class="btn btn-primary">Admin</a>
                        <?php endif; ?>
                            <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
                        <?php else: ?>
                            <a href="index.php" class="btn btn-primary">Inicio</a>
                            <a href="accessibility.php" class="btn btn-secondary">Accesibilidad</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>
