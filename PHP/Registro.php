<?php
session_start(); 

header('Content-Type: application/json');
require_once '../config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombres'] ?? ''; 
    $nom_usuario = $_POST['nombreUsu'] ?? ''; 
    $correo = $_POST['correo'] ?? ''; 
    $contrasena = $_POST['contrasena'] ?? ''; 
    $fecha_nacimiento = $_POST['fechaNacim'] ?? ''; 
    $genero = $_POST['sexo'] ?? ''; 
    $privacidad = $_POST['privacidad'] ?? ''; 
    $imagen = $_FILES['imageUpload'] ?? null; 
    $apellidoP = $_POST['apePa'] ?? ''; 
    $apellidoM = $_POST['apeMa'] ?? ''; 
    $rol = $_POST['rol'] ?? ''; 


    if (!$nombre || !$nom_usuario || !$correo || !$contrasena || !$fecha_nacimiento || !$genero) {
        echo json_encode(["success" => false, "error" => "Faltan campos"]);
        exit;
    }


    $fotoBinaria = null;
    if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        $fotoBinaria = file_get_contents($imagen['tmp_name']); 
    }

    // Encriptación de la contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Usuarios (nombreUsu, correo, contrasena, rol, foto, fotoNombre, nombres, apePa, apeMa, fechaNacim, sexo, privacidad)
            VALUES (:nombreUsu, :correo, :contrasena, :rol, :foto, :fotoNombre, :nombres, :apePa, :apeMa, :fechaNacim, :sexo, :privacidad)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Error al preparar la consulta: " . $conn->errorInfo()]);
        exit;
    }

    // parámetros vinculados a la consulta preparada
    $stmt->bindValue(':nombreUsu', $nom_usuario, PDO::PARAM_STR);
    $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindValue(':contrasena', $hash, PDO::PARAM_STR); 
    $stmt->bindValue(':rol', $rol, PDO::PARAM_STR); 
    $stmt->bindValue(':foto', $fotoBinaria, PDO::PARAM_LOB); 
    $stmt->bindValue(':fotoNombre', $imagen['name'], PDO::PARAM_STR); 
    $stmt->bindValue(':nombres', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':apePa', $apellidoP, PDO::PARAM_STR);
    $stmt->bindValue(':apeMa', $apellidoM, PDO::PARAM_STR);
    $stmt->bindValue(':fechaNacim', $fecha_nacimiento, PDO::PARAM_STR);
    $stmt->bindValue(':sexo', $genero, PDO::PARAM_STR);
    $stmt->bindValue(':privacidad', $privacidad, PDO::PARAM_STR);

   
    if ($stmt->execute()) {
       
        $user_id = $conn->lastInsertId(); 

        // Almacenar la sesión
        $_SESSION['usuario'] = [
            'id' => $user_id,
            'rol' => $rol,
            'nombreUsu' => $nom_usuario,
        ];

        echo json_encode(["success" => true, "message" => "Registro exitoso, sesión iniciada"]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->errorInfo()]);
    }


    $stmt->closeCursor();
    $conn = null;
} else {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
}
?>
