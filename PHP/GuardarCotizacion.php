<?php
session_start();
header('Content-Type: application/json');

// Incluir archivo de conexión PDO
require '../config.php'; // Ajusta ruta

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado.']);
    exit;
}

$id_comprador = $_SESSION['usuario'];

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

$id_producto = intval($data['id_producto'] ?? 0);
$cantidad = intval($data['cantidad'] ?? 0);
$precio = floatval($data['precio'] ?? 0);

if ($id_producto <= 0 || $cantidad <= 0 || $precio <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO Cotizaciones (id_producto, id_comprador, precio, cantidad) VALUES (:id_producto, :id_comprador, :precio, :cantidad)");

    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->bindParam(':id_comprador', $id_comprador, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar la cotización.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}
