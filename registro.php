<?php
session_start();
include "db.php";

// Si ya hay sesión iniciada, redirige a biblioteca.php
if (isset($_SESSION['usuario'])) {
    header("Location: biblioteca.php");
    exit();
}

$error = "";
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];
    $email = trim($_POST['email']);
    
    // Validaciones
    if (empty($usuario) || empty($clave) || empty($confirmar_clave) || empty($email)) {
        $error = "Todos los campos son obligatorios";
    } elseif (strlen($usuario) < 3) {
        $error = "El nombre de usuario debe tener al menos 3 caracteres";
    } elseif (strlen($clave) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif ($clave !== $confirmar_clave) {
        $error = "Las contraseñas no coinciden";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no es válido";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nombre = ? OR email = ?");
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "El usuario o email ya existe";
        } else {
            // Crear nuevo usuario
            $saldo_inicial = 100.00; // Saldo inicial para nuevos usuarios
            $rol = 'user'; // Rol por defecto (ajustado para coincidir con tu BD)
            
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, clave, rol, saldo) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssd", $usuario, $email, $clave, $rol, $saldo_inicial);
            
            if ($stmt->execute()) {
                $mensaje = "Cuenta creada exitosamente. Ya puedes iniciar sesión.";
            } else {
                $error = "Error al crear la cuenta. Intenta nuevamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro - Steam HRG</title>
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
                        <a href="index.php" class="btn btn-primary">Iniciar Sesión</a>
                        <a href="registro.php" class="btn btn-secondary">Registro</a>
                        <a href="accessibility.php" class="btn btn-secondary">Accesibilidad</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="main">
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">Crear Cuenta en Steam HRG</h1>
                    <p class="hero-subtitle">Únete a nuestra plataforma y disfruta de los mejores videojuegos</p>
                </div>
            </section>

            <section id="register-form">
                <h2>Crear nueva cuenta</h2>
                
                <?php if (!empty($error)): ?>
                    <p class="error-message" style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                
                <?php if (!empty($mensaje)): ?>
                    <p class="success-message" style="color:green;"><?= htmlspecialchars($mensaje) ?></p>
                    <p><a href="index.php" class="btn btn-primary">Ir a Iniciar Sesión</a></p>
                <?php else: ?>
                    <form method="POST">
                        <input type="text" name="usuario" placeholder="Nombre de usuario (mín. 3 caracteres)" required class="form-input" value="<?= isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : '' ?>" /><br />
                        
                        <input type="email" name="email" placeholder="Correo electrónico" required class="form-input" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" /><br />
                        
                        <input type="password" name="clave" placeholder="Contraseña (mín. 6 caracteres)" required class="form-input" /><br />
                        
                        <input type="password" name="confirmar_clave" placeholder="Confirmar contraseña" required class="form-input" /><br />
                        
                        <div class="button-container">
                            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
                        </div>
                    </form>
                    
                    <p style="text-align: center; margin-top: 1rem;">
                        ¿Ya tienes cuenta? <a href="index.php" style="color: #66c0f4;">Inicia sesión aquí</a>
                    </p>
                <?php endif; ?>
            </section>
        </main>

        <?php include "includes/footer.php"; ?>
    </div>
</body>
</html>
