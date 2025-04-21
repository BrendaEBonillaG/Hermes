<?php
session_start();

require '../config.php'; 

// Función para validar la contraseña
function validarContrasena($contrasena) {
    if (strlen($contrasena) > 8) {
        return false;
    }

    // Expresión regular para validar la contraseña
    $patron = '/^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d).+$/';
    return preg_match($patron, $contrasena);
}

// Función para validar el correo electrónico
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $correo = $_POST['correo'];
    $nombreUsu = $_POST['nombreUsu'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol'];
    $nombres = $_POST['nombres'];
    $apePa = $_POST['apePa'];
    $apeMa = $_POST['apeMa'];
    $fechaNacim = $_POST['fechaNacim'];
    $sexo = $_POST['sexo'];
    $privacidad = $_POST['privacidad'];

    // Validar el correo electrónico
    if (!validarCorreo($correo)) {
        die("Error: El correo electrónico no es válido.");
    }

    // Validar la contraseña
    if (!validarContrasena($contrasena)) {
        die("Error: La contraseña no cumple con los requisitos.");
    }

    // Hash de la contraseña
    $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Manejo de la imagen (avatar)
    $foto = null;
    $fotoNombre = null;

    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
        $fotoNombre = basename($_FILES['imageUpload']['name']);
        $foto = file_get_contents($_FILES['imageUpload']['tmp_name']);
    }

    try {
        // Preparar la llamada al procedimiento almacenado
        $stmt = $conn ->prepare("CALL sp_insert_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $correo, PDO::PARAM_STR);
        $stmt->bindParam(2, $nombreUsu, PDO::PARAM_STR);
        $stmt->bindParam(3, $contrasenaHash, PDO::PARAM_STR);
        $stmt->bindParam(4, $rol, PDO::PARAM_STR);
        $stmt->bindParam(5, $foto, PDO::PARAM_LOB);
        $stmt->bindParam(6, $fotoNombre, PDO::PARAM_STR);
        $stmt->bindParam(7, $nombres, PDO::PARAM_STR);
        $stmt->bindParam(8, $apePa, PDO::PARAM_STR);
        $stmt->bindParam(9, $apeMa, PDO::PARAM_STR);
        $stmt->bindParam(10, $fechaNacim, PDO::PARAM_STR);
        $stmt->bindParam(11, $sexo, PDO::PARAM_STR);
        $stmt->bindParam(12, $privacidad, PDO::PARAM_STR);

        // Ejecutar el procedimiento
        $stmt->execute();

        // Mostrar mensaje de éxito
        echo "Administrador registrado correctamente.";
    } catch (PDOException $e) {
        // Mostrar mensaje de error
        die("Error al registrar el administrador: " . $e->getMessage());
    }
} else {
    // Si no es una solicitud POST, devolver un error 405
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Método no permitido.";
    exit;
}
?>