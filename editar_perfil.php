<?php
// Iniciar sesión para acceder al usuario actual
session_start();

require './config.php'; 

$usuario_id = $_SESSION['usuario']['id'];  // Obtener el ID del usuario desde la sesión

// Función para validar la contraseña
function validarContrasena($contrasena) {
    if (strlen($contrasena) > 8) {
        return false;
    }


    $patron = '/^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d).+$/';
    return preg_match($patron, $contrasena);
}

// Función para validar el correo electrónico
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar a la base de datos
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // DATOS DEL FORMULARIO
        $nombreUsu = $_POST['nombreUsu'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol_actual'];
        $nombres = $_POST['nombres'];
        $apePa = $_POST['apePa'];
        $apeMa = $_POST['apeMa'];
        $fechaNacim = $_POST['fechaNacim'];
        $sexo = $_POST['sexo'] ?? $_POST['sexo_actual'];
        $privacidad = $_POST['privacidad'] ?? $_POST['privacidad_actual'];
        $contrasena = $_POST['contrasena'];

        if (!validarCorreo($correo)) {
            die("Error: El correo electrónico no es válido.");
        }

        if (empty($contrasena)) {
            $contrasena = $_POST['contrasena_actual'];
        } else {
            if (!validarContrasena($contrasena)) {
                die("Error: La contraseña no cumple con los requisitos.");
            }
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        // FOTO NUEVA (si se subió)
        $fotoBinaria = null;
        $fotoNombre = null;

        if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
            
            $fotoBinaria = file_get_contents($_FILES['imageUpload']['tmp_name']);
            $fotoNombre = $_FILES['imageUpload']['name'];
        } else {
            // Recuperar imagen actual si no se sube nueva
            $stmtImg = $pdo->prepare("SELECT foto, fotoNombre FROM usuarios WHERE id = :id");
            $stmtImg->bindParam(':id', $usuario_id);
            $stmtImg->execute();
            $imgData = $stmtImg->fetch(PDO::FETCH_ASSOC);
            $fotoBinaria = $imgData['foto'];
            $fotoNombre = $imgData['fotoNombre'];
        }

        // CONSULTA SQL
        $sql = "UPDATE usuarios SET
            nombreUsu = :nombreUsu,
            correo = :correo,
            contrasena = :contrasena,
            rol = :rol,
            nombres = :nombres,
            apePa = :apePa,
            apeMa = :apeMa,
            fechaNacim = :fechaNacim,
            sexo = :sexo,
            privacidad = :privacidad,
            foto = :foto,
            fotoNombre = :fotoNombre
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        // BIND DE LOS DATOS
        $stmt->bindParam(':nombreUsu', $nombreUsu);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':nombres', $nombres);
        $stmt->bindParam(':apePa', $apePa);
        $stmt->bindParam(':apeMa', $apeMa);
        $stmt->bindParam(':fechaNacim', $fechaNacim);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':privacidad', $privacidad);
        $stmt->bindValue(':foto', $fotoBinaria, PDO::PARAM_LOB);
        $stmt->bindValue(':fotoNombre', $fotoNombre, PDO::PARAM_STR);
        $stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);

 
        
        // Ejecutar la consulta
        $stmt->execute();

        // Redirigir al usuario a su perfil actualizado
        header('Location: Perfil.php');  // O redirigir a la página que muestra el perfil actualizado
        exit;
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


        

