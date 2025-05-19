<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require 'config.php'; 

// Activar errores para depuración si estás en desarrollo
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Obtener parámetros opcionales
$buscar = $_GET['buscar'] ?? '';
$precio = $_GET['precio'] ?? '';

// Base de la consulta
$sql = "
    SELECT 
        productos.*, 
        usuarios.nombreUsu AS nombreVendedor,
        GROUP_CONCAT(imagenes_productos.url_imagen) AS imagenes
    FROM productos
    INNER JOIN usuarios ON productos.id_vendedor = usuarios.id
    LEFT JOIN imagenes_productos ON productos.id = imagenes_productos.id_producto
";

$conditions = [];
$params = [];

if (!empty($buscar)) {
    $conditions[] = "productos.nombre LIKE ?";
    $params[] = "%$buscar%";
}

// Agregar condiciones si existen
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Agrupar para GROUP_CONCAT
$sql .= " GROUP BY productos.id";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as &$producto) {
        $producto['imagenes'] = $producto['imagenes']
            ? explode(',', $producto['imagenes'])
            : [];
    }

    echo json_encode($productos); // ✅ Esto es todo lo que necesitas

} catch (PDOException $e) {
    echo json_encode(["error" => "Error al obtener productos: " . $e->getMessage()]);
}
?>

