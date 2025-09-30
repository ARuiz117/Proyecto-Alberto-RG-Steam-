<?php
session_start();
include "db.php";

// Si ya hay sesión iniciada, redirige a biblioteca.php
if (isset($_SESSION['usuario'])) {
    header("Location: biblioteca.php");
    exit();
}

if (isset($_POST['usuario']) && isset($_POST['clave'])) {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    $query = "SELECT * FROM usuarios WHERE nombre = ? AND clave = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usuario, $clave);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['saldo'] = 100;
        header("Location: biblioteca.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Steam HRG</title>
    <link rel="stylesheet" href="style.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="accessibility.js"></script>
    <script src="session-manager.js"></script>
</head>
<body>
    <!-- Video de fondo -->
    <video autoplay muted loop id="bgVideo">
        <source src="video/ingame.mp4" type="video/mp4">
        Tu navegador no soporta video en HTML5.
    </video>

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
                            <a href="biblioteca.php" class="btn btn-primary">Biblioteca</a>
                        <?php else: ?>
                            <a href="index.php" class="btn btn-primary">Iniciar Sesión</a>
                            <a href="registro.php" class="btn btn-secondary">Registrarse</a>
                        <?php endif; ?>
                        <a href="accessibility.php" class="btn btn-secondary">Accesibilidad</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">Bienvenido a Steam HRG</h1>
                    <p class="hero-subtitle">Compra, gestiona y reseña tus juegos favoritos. ¡Simula la experiencia Steam con accesibilidad mejorada!</p>
                </div>
            </section>

            <section id="login-form">
                <h2>Iniciar sesión</h2>
                <?php if (isset($error)): ?>
                    <p class="error-message" style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="usuario" placeholder="Usuario" required class="form-input" /><br />
                    <input type="password" name="clave" placeholder="Clave" required class="form-input" /><br />
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
                
                <div class="register-section">
                    <p class="register-text">¿No tienes cuenta?</p>
                    <a href="registro.php" class="btn btn-primary">Crear cuenta nueva</a>
                </div>
            </section>
        </main>

        <?php include "includes/footer.php"; ?>
    </div>
</body>
</html>
