<?php
require '../config.php';

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['usuario'], $_POST['mensaje'], $_POST['id_chat'])) {
    http_response_code(400);
    echo "Faltan datos.";
    exit;
}

$id_usuario = (int)$_SESSION['usuario'];
$mensaje = trim($_POST['mensaje']);
$id_chat = (int)$_POST['id_chat'];

// Validar que no esté vacío
if ($mensaje === '') {
    echo "Mensaje vacío.";
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO Mensajes_Privado (id_chat, id_usuario, contenido) VALUES (?, ?, ?)");
    $stmt->execute([$id_chat, $id_usuario, $mensaje]);
    echo "Mensaje enviado correctamente.";
} catch (PDOException $e) {
    echo "Error al enviar el mensaje: " . $e->getMessage();
}
?>
