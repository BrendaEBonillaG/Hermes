<?php
session_start();
require __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsu = $_POST['nombreUsu'];
    $contrasena = $_POST['contrasena'];
    $rememberMe = isset($_POST['rememberMe']);

    // Buscar el usuario en la base de datos
    $stmt = $conn ->prepare("SELECT id, nombreUsu, contrasena, rol FROM Usuarios WHERE nombreUsu = ?");
    $stmt->execute([$nombreUsu]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        // Iniciar sesión
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombreUsu' => $usuario['nombreUsu'],
            'rol' => $usuario['rol']
        ];

        // Recordar contraseña (cookie)
        if ($rememberMe) {
            $cookieValue = base64_encode($usuario['id'] . ':' . $usuario['nombreUsu']);
            setcookie('rememberMe', $cookieValue, time() + (86400 * 30), "/"); // 30 días
        }

        // Redirigir al dashboard
        header("Location: ../Dashboard.html");
        exit();
    } else {
        // Mostrar mensaje de error
        echo "Nombre de usuario o contraseña incorrectos.";
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Método no permitido.";
    exit;
}
?>