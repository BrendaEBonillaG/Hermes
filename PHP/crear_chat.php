<?php
session_start();
require './config.php'; 

// VERFIICACION USUARIO
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// VERIFICA ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuario inválido");
}

$id_emisor = $_SESSION['id_usuario'];
$id_remitente = $_GET['id'];

if ($id_emisor == $id_remitente) {
    die("No puedes chatear contigo mismo");
}

// VALIDACION DE CHAT EXISTENTE
$sql_check = "SELECT id_chat FROM Chat_Privado 
              WHERE (id_remitente = ? AND id_emisor = ?)
              OR (id_remitente = ? AND id_emisor = ?)";

$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("iiii", $id_remitente, $id_emisor, $id_emisor, $id_remitente);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows == 0) {

    $sql_insert = "INSERT INTO Chat_Privado (id_remitente, id_emisor) 
                   VALUES (?, ?)";
    
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $id_emisor, $id_remitente);
    $stmt_insert->execute();
    $id_chat = $conn->insert_id;
    $stmt_insert->close();
} else {

    $row = $result->fetch_assoc();
    $id_chat = $row['id_chat'];
}

$stmt_check->close();
$conn->close();

header("Location: ../chat.php?id=" . $id_chat);
exit();
?>