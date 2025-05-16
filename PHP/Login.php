<?php
session_start();
require __DIR__ . '/../config.php';
$hash = password_hash('Denso75?', PASSWORD_DEFAULT);
echo $hash;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsu = $_POST['nombreUsu'];
    $contrasena = $_POST['contrasena'];
    $rememberMe = isset($_POST['rememberMe']);

    // Buscar el usuario en la base de datos
    $stmt = $conn ->prepare("SELECT id, nombreUsu, contrasena, rol,estado FROM Usuarios WHERE nombreUsu = ?");
    $stmt->execute([$nombreUsu]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasena, $usuario['contrasena']) && $usuario['estado']== 1) {
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

        if($usuario['rol']=='administrador'){
            header("Location: ../Admin/RevisionProd.php");
        }else{
// Redirigir al dashboard
header("Location: ../Dashboard.php");
        }

        
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