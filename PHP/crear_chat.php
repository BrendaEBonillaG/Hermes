<?php
session_start();
require '../config.php'; // Asegúrate de que este archivo devuelve un objeto PDO en $conn

// VERIFICACIÓN DE USUARIO
if (!isset($_SESSION['usuario'])) {
    header("Location: Index.php");
    exit();
}

// VERIFICACIÓN DE ID VÁLIDO
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido");
}

$id_emisor = $_SESSION['usuario'];
$id_remitente = intval($_GET['id']);

if ($id_emisor === $id_remitente) {
    die("No puedes chatear contigo mismo");
}

// VALIDACIÓN DE CHAT EXISTENTE
$sql_check = "SELECT id_chat FROM Chat_Privado 
              WHERE (id_remitente = :remitente1 AND id_emisor = :emisor1)
                 OR (id_remitente = :remitente2 AND id_emisor = :emisor2)";

$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([
    ':remitente1' => $id_remitente,
    ':emisor1'    => $id_emisor,
    ':remitente2' => $id_emisor,
    ':emisor2'    => $id_remitente
]);

$result = $stmt_check->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    // No existe, lo insertamos
    $sql_insert = "INSERT INTO Chat_Privado (id_remitente, id_emisor) VALUES (:emisor, :remitente)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->execute([
        ':emisor'    => $id_emisor,
        ':remitente' => $id_remitente
    ]);
    $id_chat = $conn->lastInsertId();
} else {
    // Ya existe, usamos ese ID
    $id_chat = $result['id_chat'];
}

// Redirigimos al chat
header("Location: ../Chat.php?id=" . $id_chat);
exit();
?>
