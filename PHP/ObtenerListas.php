<?php
session_start();

require '../config.php';

$idUsuario = $_SESSION['usuario']['id'] ?? null;


if (!$idUsuario) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, nombre FROM Listas WHERE id_usuario = :usuario");
    $stmt->bindParam(':usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();
    $listas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($listas);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>