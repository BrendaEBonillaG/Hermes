<?php
session_start();

// Destruir la sesión
session_destroy();

// Eliminar la cookie de "Recordar contraseña"
if (isset($_COOKIE['rememberMe'])) {
    setcookie('rememberMe', '', time() - 3600, "/");
}

// Redirigir al login
header("Location: ../index.html");
exit();
?>