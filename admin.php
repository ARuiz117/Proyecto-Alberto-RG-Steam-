<?php
session_start();
include "db.php";
include "cookie_handler.php";

if ($_SESSION['rol'] != "admin") {
    header("Location: home.php");
    exit();
}

echo "Panel de administración - Aquí puedes gestionar los juegos.";
?>