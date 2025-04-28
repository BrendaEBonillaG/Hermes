<?php
include '../conexion.php'; 
session_start();

if (!isset($_SESSION['id_usuario'], $_POST['mensaje'], $_POST['id_chat'])) {
    http_response_code(400);
    echo "Faltan datos.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$mensaje = mysqli_real_escape_string($conn, $_POST['mensaje']);
$id_chat = (int)$_POST['id_chat'];

$sql = "INSERT INTO Mensajes_Privado (id_chat_Privado, id_usuario, contenido) 
        VALUES ($id_chat, $id_usuario, '$mensaje')";

if (mysqli_query($conn, $sql)) {
    echo "Mensaje enviado";
} else {
    echo "Error al enviar el mensaje: " . mysqli_error($conn);
}
?>
