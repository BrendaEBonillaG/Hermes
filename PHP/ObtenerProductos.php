<?php
session_start();
header('Content-Type: application/json');

try {
    require '../config.php';

    if (!isset($_SESSION['usuario'])) {
        http_response_code(403);
        echo json_encode(['error' => 'No has iniciado sesión.']);
        exit;
    }

    $idVendedor = $_SESSION['usuario']['id'];  // <- aquí accedes al id numérico

   $sql = "SELECT id, nombre, descripcion, precio, cantidad_Disponible AS cantidad, tipo 
        FROM Productos 
        WHERE id_vendedor = :id AND estado = 'aceptado' AND tipo = 'cotizacion'";


    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $idVendedor, PDO::PARAM_INT);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la conexión o consulta.', 'detalle' => $e->getMessage()]);
}
?>
