<?php
session_start();
require __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $buscar = $_GET['buscar'] ?? '';
    $buscarLike = "%$buscar%";
        $precioMax = $_GET['precio'] ?? null;

    // Inicializamos base SQL
    $sql = "
        SELECT 
            productos.id,
            productos.nombre,
            productos.descripcion,
            productos.precio,
            usuarios.nombreUsu AS nombreVendedor,
            GROUP_CONCAT(imagenes_productos.url_imagen) AS imagenes
        FROM productos
        INNER JOIN usuarios ON productos.id_vendedor = usuarios.id
        LEFT JOIN imagenes_productos ON productos.id = imagenes_productos.id_producto
        WHERE 1 = 1
    ";

    $params = [];

    // Si hay texto de búsqueda, filtramos
    if (!empty($buscar)) {
        $sql .= " AND (productos.nombre LIKE ? OR productos.descripcion LIKE ?)";
        $params[] = $buscarLike;
        $params[] = $buscarLike;
    }

      // Si hay filtro de precio, lo aplicamos
    if (!empty($precioMax)) {
        $sql .= " AND productos.precio <= ?";
        $params[] = $precioMax;
    }

    $sql .= " GROUP BY productos.id";

    // Ejecutar
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as &$producto) {
        $producto['imagenes'] = $producto['imagenes']
            ? explode(',', $producto['imagenes'])
            : [];
    }

    header('Content-Type: application/json');
    echo json_encode($productos);
    exit;
}
?>









