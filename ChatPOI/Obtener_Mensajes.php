<?php

require __DIR__ . '/../conexion.php';

session_start();
if (!isset($_SESSION['id_usuario'], $_GET['id_chat'])) {
    http_response_code(400);
    exit('Faltan datos');
}

$id_usuario = $_SESSION['id_usuario'];
$id_chat = intval($_GET['id_chat']);


$stmt = $conn->prepare("SELECT id_usuario, contenido, fecha_envio FROM Mensajes_Privado WHERE id_chat_Privado = ? ORDER BY fecha_envio ASC");
$stmt->bind_param("i", $id_chat);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $clase = ($row['id_usuario'] == $id_usuario) ? 'outgoing' : 'incoming';
    echo '<div class="chat-message ' . $clase . '">';
    echo '<div class="message-content">';
    echo '<p>' . htmlspecialchars($row['contenido']) . '</p>';
    echo '<span class="message-time">' . date("H:i", strtotime($row['fecha_envio'])) . '</span>';
    echo '</div></div>';
}

$stmt->close();
$conn->close();
?>
