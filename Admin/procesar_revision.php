<?php
require __DIR__ . '/../config.php'; // Ajusta esta ruta si es necesario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_producto'];
    $accion = $_POST['accion'];

    // Validar acción
    if ($accion === 'aceptar') {
        $nuevoEstado = 'aceptado';
    } elseif ($accion === 'rechazar') {
        $nuevoEstado = 'rechazado';
    } else {
        exit('Acción no válida.');
    }

    // Actualizar el estado en la base de datos
    $stmt = $conn->prepare("UPDATE productos SET estado = ? WHERE id = ?");
    $stmt->execute([$nuevoEstado, $id]);

    // Redirigir de vuelta
    header("Location: RevisionProd.php");
    exit();
} else {
    echo "Método no permitido.";
    exit;
}
