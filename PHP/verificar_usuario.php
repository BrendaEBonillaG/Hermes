<?php
error_reporting(E_ALL);
require_once '../config.php';
header('Content-Type: application/json');

$nombreUsu = $_POST['nombreUsu'] ?? '';

try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Usuarios WHERE nombreUsu = ?");
    $stmt->execute([$nombreUsu]);
    $count = $stmt->fetchColumn();

    echo json_encode(['exists' => $count > 0]);
} catch (PDOException $e) {
    echo json_encode([
        'exists' => false,
        'error' => 'Error en la consulta: ' . $e->getMessage()
    ]);
}
?>

