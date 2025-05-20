<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
require '../config.php';

header('Content-Type: application/json');

$idUsuario = $_SESSION['usuario']['id'] ?? null;

if (!$idUsuario) {
    echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
    exit;
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $privacidad = $_POST['privacidad'] ?? '';
    $fotoRuta = null;

  
    // Procesar imagen si existe
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoNombre = uniqid() . "_" . basename($_FILES['foto']['name']);
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fotoRuta = $uploadDir . $fotoNombre;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $fotoRuta)) {
            $fotoRuta = null;
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO listas (nombre, descripcion, privacidad, foto, id_usuario) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $privacidad, $fotoRuta, $idUsuario]);

        $id_lista = $conn->lastInsertId();

       
        $response['success'] = true;
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['error'] = "Error en la base de datos: " . $e->getMessage();
    }

} else {
    $response['success'] = false;
    $response['error'] = "Método no permitido";
}

echo json_encode($response);
