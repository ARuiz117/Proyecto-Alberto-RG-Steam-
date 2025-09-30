<?php
session_start();
include "cookie_handler.php";
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
include "includes/header.php";
if ($_SESSION['rol'] == "admin") {
    echo '<div class="main"><a href="admin.php">Panel de administración</a></div>';
}
include "includes/footer.php";
?>