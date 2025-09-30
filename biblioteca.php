<?php
session_start();
include "db.php";
include "cookie_handler.php";

// Generar token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['saldo'])) {
    $stmt = $conn->prepare("SELECT id, saldo FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $_SESSION['usuario']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user) {
        // Usuario no encontrado, cerrar sesión por seguridad
        session_destroy();
        header("Location: index.php");
        exit();
    }
    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['saldo'] = $user['saldo'];
}

function obtenerJuegosDisponibles($conn, $usuario_id) {
    $query = "SELECT * FROM juegos WHERE id NOT IN (
        SELECT juego_id FROM bibliotecas WHERE usuario_id = ?
    )";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result();
}

function obtenerBibliotecaUsuario($conn, $usuario_id) {
    $query = "SELECT j.id, j.titulo, j.descripcion, j.imagen_url FROM juegos j 
              INNER JOIN bibliotecas b ON j.id = b.juego_id 
              WHERE b.usuario_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Función para validar y sanitizar ID
function validarId($id) {
    if (!is_numeric($id) || intval($id) <= 0) {
        return false;
    }
    return intval($id);
}

// Procesar compra de juego
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comprar_juego'])) {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error de seguridad. Intenta nuevamente.";
    } else {
        $juego_id = validarId($_POST['juego_id']);
        if (!$juego_id) {
            $error = "ID de juego inválido.";
        } else {
            $stmt = $conn->prepare("SELECT precio FROM juegos WHERE id = ?");
            $stmt->bind_param("i", $juego_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $error = "Juego no encontrado.";
            } else {
                $juego = $result->fetch_assoc();
                $precio = floatval($juego['precio']);

                // Comprobar si ya comprado
                $stmt = $conn->prepare("SELECT * FROM bibliotecas WHERE usuario_id = ? AND juego_id = ?");
                $stmt->bind_param("ii", $_SESSION['usuario_id'], $juego_id);
                $stmt->execute();
                $ya_comprado = $stmt->get_result()->num_rows > 0;

                if ($ya_comprado) {
                    $error = "Ya has comprado este juego.";
                } elseif ($_SESSION['saldo'] >= $precio) {
                    // Insertar en biblioteca
                    $stmt = $conn->prepare("INSERT INTO bibliotecas (usuario_id, juego_id) VALUES (?, ?)");
                    $stmt->bind_param("ii", $_SESSION['usuario_id'], $juego_id);
                    if ($stmt->execute()) {
                        // Actualizar saldo sesión y BD
                        $_SESSION['saldo'] -= $precio;
                        $stmt = $conn->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?");
                        $stmt->bind_param("di", $_SESSION['saldo'], $_SESSION['usuario_id']);
                        $stmt->execute();

                        $mensaje = "¡Juego comprado con éxito!";
                    } else {
                        $error = "Error al procesar la compra.";
                    }
                } else {
                    $error = "Saldo insuficiente.";
                }
            }
        }
    }
}

// Procesar devolución de juego
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['devolver_juego'])) {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Error de seguridad. Intenta nuevamente.";
    } else {
        $juego_id = validarId($_POST['juego_id']);
        if (!$juego_id) {
            $error = "ID de juego inválido.";
        } else {
            // Primero recuperar el precio para devolver saldo
            $stmt = $conn->prepare("SELECT precio FROM juegos WHERE id = ?");
            $stmt->bind_param("i", $juego_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $error = "Juego no encontrado para devolución.";
            } else {
                $juego = $result->fetch_assoc();
                $precio = floatval($juego['precio']);

                // Eliminar de biblioteca
                $stmt = $conn->prepare("DELETE FROM bibliotecas WHERE usuario_id = ? AND juego_id = ?");
                $stmt->bind_param("ii", $_SESSION['usuario_id'], $juego_id);
                if ($stmt->execute()) {
                    // Devolver saldo
                    $_SESSION['saldo'] += $precio;
                    $stmt = $conn->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?");
                    $stmt->bind_param("di", $_SESSION['saldo'], $_SESSION['usuario_id']);
                    $stmt->execute();

                    $mensaje = "Juego devuelto correctamente y saldo reembolsado.";
                } else {
                    $error = "Error al devolver el juego.";
                }
            }
        }
    }
}

include "includes/header.php";
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #1b2838;
    color: #c7d5e0;
    margin: 0;
    padding: 0;
}
.main {
    padding: 40px 20px;
    max-width: 1200px;
    margin: auto;
}
.biblioteca, .juegos-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    padding: 20px 0;
}
@media (max-width: 1200px) {
    .biblioteca, .juegos-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 768px) {
    .biblioteca, .juegos-grid {
        grid-template-columns: 1fr;
    }
}
h3 {
    font-size: 28px;
    border-left: 5px solid #66c0f4;
    padding-left: 15px;
    margin-bottom: 20px;
}
.juego-card {
    background-color: #2a475e;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    position: relative;
    box-shadow: 0 0 10px #00000044;
    animation: fadeIn 0.5s ease;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.juego-card:hover {
    transform: scale(1.03);
    box-shadow: 0 0 20px #00000088;
}
.juego-card h4 {
    margin: 0 0 10px;
    font-size: 20px;
    color: #66c0f4;
}
.juego-card p {
    font-size: 14px;
    color: #dfe3e6;
    height: 70px;
    overflow: hidden;
    margin: 0 0 10px 0;
}

/* Contenedor cuadrado para la imagen */
.img-container {
    width: 100%;
    aspect-ratio: 1 / 1; /* hace que el contenedor sea cuadrado */
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    margin: 15px 0;
}

/* Imagen adaptada al contenedor cuadrado, cubriendo y centrando */
.img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: block;
}
.img-container img:hover {
    transform: scale(1.07);
    box-shadow: 0 6px 14px rgba(0,0,0,0.5);
}

.precio {
    font-weight: bold;
    color: #a4dffd;
    margin-top: auto;
    font-size: 17px;
}

.btn {
    background-color: #66c0f4;
    border: none;
    color: #1b2838;
    padding: 10px 15px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    margin-top: 10px;
    width: 100%;
    transition: background-color 0.3s ease;
}
.btn:hover {
    background-color: #558ec6;
}
.error {
    background-color: #e63946;
    padding: 15px;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    margin-bottom: 1rem;
    text-align: center;
    box-shadow: 0 0 10px #e6394644;
}
.mensaje {
    background-color: #06d6a0;
    padding: 15px;
    border-radius: 8px;
    color: #034d3a;
    font-weight: bold;
    margin-bottom: 1rem;
    text-align: center;
    box-shadow: 0 0 10px #06d6a044;
}

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}
</style>

<div class="main">
    <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>. Tu saldo: <?php echo number_format($_SESSION['saldo'], 2); ?> €</h3>

    <?php if (isset($error)) : ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($mensaje)) : ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <section class="biblioteca">
        <h3>Tu Biblioteca</h3>
        <?php
        $biblioteca = obtenerBibliotecaUsuario($conn, $_SESSION['usuario_id']);
        if ($biblioteca->num_rows === 0) {
            echo "<p>Aún no has comprado ningún juego.</p>";
        } else {
            while ($juego = $biblioteca->fetch_assoc()) {
                ?>
                <article class="juego-card">
                    <h4><?php echo htmlspecialchars($juego['titulo']); ?></h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($juego['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($juego['titulo']); ?>">
                    </div>
                    <p><?php echo htmlspecialchars($juego['descripcion']); ?></p>
                    <form method="POST" onsubmit="return confirm('¿Seguro que quieres devolver este juego?');">
                        <input type="hidden" name="juego_id" value="<?php echo $juego['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <button class="btn" type="submit" name="devolver_juego">Devolver juego</button>
                    </form>
                </article>
                <?php
            }
        }
        ?>
    </section>

    <section class="juegos-grid">
        <h3>Juegos Disponibles para Comprar</h3>
        <?php
        $juegos_disponibles = obtenerJuegosDisponibles($conn, $_SESSION['usuario_id']);
        if ($juegos_disponibles->num_rows === 0) {
            echo "<p>Ya tienes todos los juegos disponibles.</p>";
        } else {
            while ($juego = $juegos_disponibles->fetch_assoc()) {
                ?>
                <article class="juego-card">
                    <h4><?php echo htmlspecialchars($juego['titulo']); ?></h4>
                    <div class="img-container">
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($juego['imagen_url']); ?>" alt="Portada de <?php echo htmlspecialchars($juego['titulo']); ?>">
                    </div>
                    <p><?php echo htmlspecialchars($juego['descripcion']); ?></p>
                    <div class="precio">Precio: <?php echo number_format($juego['precio'], 2); ?> €</div>
                    <form method="POST" <?php if ($_SESSION['saldo'] < $juego['precio']) echo 'style="opacity:0.5; pointer-events:none;" title="Saldo insuficiente"'; ?>>
                        <input type="hidden" name="juego_id" value="<?php echo $juego['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <button class="btn" type="submit" name="comprar_juego">Comprar</button>
                    </form>
                </article>
                <?php
            }
        }
        ?>
    </section>
</div>

<?php
include "includes/footer.php";
?>
