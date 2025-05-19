<?php
echo "si entro";
// Suponiendo que tienes conexión a la base de datos
include 'config.php'; // o como se llame tu archivo de conexión
session_start();
// Obtener JSON del carrito
$data = json_decode(file_get_contents("php://input"), true);
$carrito = $data["carrito"] ?? [];


if (!isset($_SESSION["usuario"]) || !isset($_SESSION["usuario"]["id"])) {
    echo "Usuario no autenticado";
    exit;
}

$id_comprador = $_SESSION["usuario"]["id"];

if (empty($carrito)) {
    echo "Carrito vacío";
    exit;
}

foreach ($carrito as $producto) {
    if (!isset($producto["id_producto"])) {
        echo "Falta id_producto en el carrito\n";
        continue;
    }

    $id_producto = $producto["id_producto"];
    $cantidad =$producto["cantidad"];
    $query = "INSERT INTO Compra (id_comprador, id_producto,cantidad) VALUES (?, ?,?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo "Error al preparar consulta: " . $conn->error;
        exit;
    }

    // Vinculamos los parámetros correctamente
    $stmt->bindParam(1, $id_comprador, PDO::PARAM_INT);
    $stmt->bindParam(2, $id_producto, PDO::PARAM_INT);
    
    $stmt->bindParam(3, $cantidad, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        echo "Error al insertar: " . $stmt->errorInfo()[2];
        exit;
    }
}

echo "ok";
?>

