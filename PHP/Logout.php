<?php
session_start();

// Destruir la sesión
session_destroy();


if (isset($_COOKIE['rememberMe'])) {
    setcookie('rememberMe', '', time() - 3600, "/");
}


header("Location: ../Index.php");
exit();
?>