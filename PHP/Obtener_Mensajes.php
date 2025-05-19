<?php

require '../config.php';


session_start();
if (!isset($_SESSION['usuario'], $_GET['id_chat'])) {
    http_response_code(400);
    exit('Faltan datos');
}

$id_usuario = $_SESSION['usuario'];
$id_chat = intval($_GET['id_chat']);


$stmt = $conn->prepare("SELECT id_usuario, contenido, fecha_envio FROM Mensajes_Privado WHERE id_chat = ? ORDER BY fecha_envio ASC");
$stmt->execute([$id_chat]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    $clase = ($row['id_usuario'] == $id_usuario) ? 'outgoing' : 'incoming';
    echo '<div class="chat-message ' . $clase . '">';
    echo '<div class="message-content">';
    $esMensajeSistema = strpos($row['contenido'], 'btn-ver-cotizacion') !== false;

    if ($esMensajeSistema) {
        echo '<p>' . $row['contenido'] . '</p>';
    } else {
        echo '<p>' . htmlspecialchars($row['contenido']) . '</p>';
    }

    echo '<span class="message-time">' . date("H:i", strtotime($row['fecha_envio'])) . '</span>';
    echo '</div></div>';
}

?>