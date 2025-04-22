<?php
session_start(); // Inicia la sesión

header('Content-Type: application/json');
require_once '../config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombres'] ?? ''; // Nombre
    $nom_usuario = $_POST['nombreUsu'] ?? ''; // Nombre de usuario
    $correo = $_POST['correo'] ?? ''; // Correo
    $contrasena = $_POST['contrasena'] ?? ''; // Contraseña
    $fecha_nacimiento = $_POST['fechaNacim'] ?? ''; // Fecha de nacimiento
    $genero = $_POST['sexo'] ?? ''; // Sexo
    $privacidad = $_POST['privacidad'] ?? ''; // Visibilidad
    $imagen = $_FILES['imageUpload'] ?? null; // Imagen
    $apellidoP = $_POST['apePa'] ?? ''; // Apellido Paterno
    $apellidoM = $_POST['apeMa'] ?? ''; // Apellido Materno
    $rol = $_POST['rol'] ?? ''; // Rol de usuario

    // Validación de campos
    if (!$nombre || !$nom_usuario || !$correo || !$contrasena || !$fecha_nacimiento || !$genero) {
        echo json_encode(["success" => false, "error" => "Faltan campos"]);
        exit;
    }

    // Subida de imagen (si existe)
    $fotoBinaria = null;
    if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        $fotoBinaria = file_get_contents($imagen['tmp_name']); // Leemos el archivo binario de la imagen
    }

    // Encriptación de la contraseña
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Consulta para insertar los datos del usuario
    $sql = "INSERT INTO Usuarios (nombreUsu, correo, contrasena, rol, foto, fotoNombre, nombres, apePa, apeMa, fechaNacim, sexo, privacidad)
            VALUES (:nombreUsu, :correo, :contrasena, 'Usuario', :foto, :fotoNombre, :nombres, :apePa, :apeMa, :fechaNacim, :sexo, :privacidad)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Error al preparar la consulta: " . $conn->errorInfo()]);
        exit;
    }

    // Vinculamos los parámetros a la consulta preparada
    $stmt->bindValue(':nombreUsu', $nom_usuario, PDO::PARAM_STR);
    $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindValue(':contrasena', $hash, PDO::PARAM_STR); // Contraseña encriptada
    $stmt->bindValue(':foto', $fotoBinaria, PDO::PARAM_LOB); // Foto en formato binario
    $stmt->bindValue(':fotoNombre', $imagen['name'], PDO::PARAM_STR); // Nombre de la imagen
    $stmt->bindValue(':nombres', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':apePa', $apellidoP, PDO::PARAM_STR);
    $stmt->bindValue(':apeMa', $apellidoM, PDO::PARAM_STR);
    $stmt->bindValue(':fechaNacim', $fecha_nacimiento, PDO::PARAM_STR);
    $stmt->bindValue(':sexo', $genero, PDO::PARAM_STR);
    $stmt->bindValue(':privacidad', $privacidad, PDO::PARAM_STR);

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        // Obtener el id del nuevo usuario registrado
        $user_id = $conn->lastInsertId(); // Usamos lastInsertId() en PDO

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

    // Cerramos la conexión
    $stmt->closeCursor();
    $conn = null; // Cerramos la conexión PDO
} else {
    echo json_encode(["success" => false, "error" => "Método no permitido"]);
}
?>
