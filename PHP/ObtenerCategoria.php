<?php
require 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $conn ->query("SELECT nombre FROM Categorias ORDER BY nombre");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categorias);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>