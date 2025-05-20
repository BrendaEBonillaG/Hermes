<?php
session_start();
header('Content-Type: application/json');

require '../config.php';

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
$id_chat = intval($data['id_chat'] ?? 0);

if ($id_producto <= 0 || $cantidad <= 0 || $precio <= 0 || $id_chat <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
    exit;
}

try {
    // Insertar cotización
    $stmt = $conn->prepare("INSERT INTO Cotizaciones (id_producto, id_comprador, precio, cantidad) VALUES (:id_producto, :id_comprador, :precio, :cantidad)");
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->bindParam(':id_comprador', $id_comprador, PDO::PARAM_INT);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar la cotización.']);
        exit;
    }

    // Obtener nombre y descripción del producto
    $stmtProd = $conn->prepare("SELECT nombre, descripcion FROM Productos WHERE id = :id_producto");
    $stmtProd->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmtProd->execute();

    $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
        exit;
    }



    $mensaje = "<div class='cotizacion-box'>";
    $mensaje .= "<p><strong>Producto:</strong> " . htmlspecialchars($producto['nombre']) . "</p>";
    $mensaje .= "<p><strong>Descripción:</strong> " . htmlspecialchars($producto['descripcion']) . "</p>";
    $mensaje .= "<p><strong>Cantidad:</strong> {$cantidad}</p>";
    $mensaje .= "<p><strong>Precio total:</strong> $" . number_format($precio, 2) . "</p>";
    $mensaje .= "<button class='btn-ver-cotizacion' data-producto-id='{$id_producto}'>Pagar</button>";
    $mensaje .= "</div>";



    $stmt2 = $conn->prepare("INSERT INTO Mensajes_Privado (id_chat, id_usuario, contenido) VALUES (:id_chat, :id_usuario, :contenido)");
    $stmt2->bindParam(':id_chat', $id_chat, PDO::PARAM_INT);
    $stmt2->bindParam(':id_usuario', $id_comprador, PDO::PARAM_INT);
    $stmt2->bindParam(':contenido', $mensaje);

    if ($stmt2->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cotización guardada y mensaje enviado al chat.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje al chat.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}
