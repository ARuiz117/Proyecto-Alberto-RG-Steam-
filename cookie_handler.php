<?php
// Definir el tiempo de duración de la cookie (24 horas en segundos)
define('COOKIE_DURATION', 3600);
// Verificar si existe la cookie de sesión
if (isset($_COOKIE['session_time'])) {
    if (time() - $_COOKIE['session_time'] > COOKIE_DURATION) {
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        setcookie("session_time", time(), time() + COOKIE_DURATION);
    }
}
?>