<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite acceso desde cualquier origen

require 'config.php'; 

try {
    $stmt = $conn->prepare("
        SELECT 
            productos.*, 
            usuarios.nombreUsu AS nombreVendedor,
            GROUP_CONCAT(imagenes_productos.url_imagen) AS imagenes
        FROM productos
        INNER JOIN usuarios ON productos.id_vendedor = usuarios.id
        LEFT JOIN imagenes_productos ON productos.id = imagenes_productos.id_producto
        GROUP BY productos.id
    ");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir la cadena de imágenes a un array
    foreach ($productos as &$producto) {
        $producto['imagenes'] = $producto['imagenes']
            ? explode(',', $producto['imagenes'])
            : [];
    }

    echo json_encode($productos);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener productos: " . $e->getMessage()]);
}
?>


