<?php
session_start();
require '../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../Index.php");
    exit();
}


$id_emisor = $_SESSION['usuario']['id'] ?? null;

if ($id_emisor === null) {
    die("No se pudo obtener el ID del emisor");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido");
}

$id_remitente = intval($_GET['id']);

if ($id_emisor === $id_remitente) {
    die("No puedes chatear contigo mismo");
}

$sql_check = "SELECT id_chat FROM Chat_Privado 
              WHERE (id_remitente = :rem1 AND id_emisor = :em1)
                 OR (id_remitente = :rem2 AND id_emisor = :em2)";

$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([
    ':rem1' => $id_remitente,
    ':em1'  => $id_emisor,
    ':rem2' => $id_emisor,
    ':em2'  => $id_remitente
]);

$result = $stmt_check->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $id_chat = $result['id_chat'];
} else {
    $sql_insert = "INSERT INTO Chat_Privado (id_remitente, id_emisor) 
                   VALUES (:remitente, :emisor)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->execute([
        ':remitente' => $id_remitente,
        ':emisor'    => $id_emisor
    ]);
    $id_chat = $conn->lastInsertId();
}

header("Location: ../Chat.php?id=" . $id_chat);
exit();
?>
