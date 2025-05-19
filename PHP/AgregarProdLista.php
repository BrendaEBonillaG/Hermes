<?php
session_start();
require '../config.php';

$idUsuario = $_SESSION['usuario']['id'] ?? null;

if (!$idUsuario) {
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$idLista = $data['id_lista'] ?? null;
$idProducto = $data['id_producto'] ?? null;

if (!$idLista || !$idProducto) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

// Verificar que la lista pertenezca al usuario
$stmt = $conn->prepare("SELECT id FROM Listas WHERE id = :idLista AND id_usuario = :idUsuario");
$stmt->bindParam(':idLista', $idLista, PDO::PARAM_INT);
$stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo json_encode(['error' => 'No tienes acceso a esta lista']);
    exit;
}

// Insertar en la tabla Listas_Productos
try {
    $stmt = $conn->prepare("INSERT INTO Listas_Productos (id_lista, id_producto) VALUES (:idLista, :idProducto)");
    $stmt->bindParam(':idLista', $idLista, PDO::PARAM_INT);
    $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => 'Producto agregado a la lista correctamente']);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al agregar el producto: ' . $e->getMessage()]);
}
?>
