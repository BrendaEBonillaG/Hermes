<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permite acceso desde cualquier origen

require 'config.php'; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitiza por seguridad

    // Conectar a la base de datos
    // Buscar el producto con ese ID
    // Ejemplo ficticio:
    $conexion = new mysqli("localhost", "root", "", "tu_base_de_datos");
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($producto = $resultado->fetch_assoc()) {
        // Mostrar los datos
        echo "<h1>{$producto['nombre']}</h1>";
        echo "<p>{$producto['descripcion']}</p>";
        echo "<p>Precio: {$producto['precio']}</p>";
    } else {
        echo "Producto no encontrado.";
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "ID de producto no especificado.";
}
?>



