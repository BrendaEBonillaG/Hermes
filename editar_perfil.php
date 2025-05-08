<?php
// Iniciar sesión para acceder al usuario actual
session_start();

require './config.php'; 

$usuario_id = $_SESSION['usuario']['id'];  // Obtener el ID del usuario desde la sesión
$nombreActual = $_SESSION['usuario']['nombreUsu'];

// Función para validar la contraseña
function validarContrasena($contrasena) {
    if (strlen($contrasena) > 8) {
        return false;
    }


    $patron = '/^(?=.*[A-ZÑ])(?=.*[a-zñ])(?=.*\d).+$/';
    return preg_match($patron, $contrasena);
}

//validar nombre
function validarNombre($nombreUsu) {
 
    $patron = '/^[\p{L}-]+$/u';
    

    return preg_match($patron, $nombreUsu);
}

//validar nombres
function validarNombres($nombres) {
 
    $patron = '/^[\p{L}-]+$/u';
    

    return preg_match($patron, $nombres);
}

// Función para validar el correo electrónico
function validarCorreo($correo) {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return preg_match('/@(gmail\.com|hotmail\.com)$/i', $correo);
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

        $fechaHoy = date('Y-m-d');

        if($fechaHoy < $fechaNacim){
            echo "<script>
            alert('El nombre de usuario ya está en uso.');
            window.location.href = 'Perfil.php';
        </script>";
        exit;
        }
        if (!validarCorreo($correo)) {
            die("Error: El correo electrónico no es válido.");
        }

        
       

        if (!validarCorreo($correo)) {
            die("Error: El correo electrónico no es válido.");
        }
        if (!validarNombres($apeMa)) {
            die("Error: El apellido materno no es válido.");
        }
        if (!validarNombres($apePa)) {
            die("Error: El apellido Paterno no es válido.");
        }
        if (!validarNombres($nombres)) {
            die("Error: el nombre no es válido.");
        }
        if ($nombreUsu !== $nombreActual) {
            // Solo validar si el nombre ha cambiado
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Usuarios WHERE nombreUsu = :nombreUsu");
            $stmt->execute([':nombreUsu' => $nombreUsu]);
            $count = $stmt->fetchColumn();
        
            if ($count > 0) {
               
                echo "<script>
                alert('El nombre de usuario ya está en uso.');
                window.location.href = 'Perfil.php';
            </script>";
            exit;
            }
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
            
        $imageInfo = getimagesize($_FILES['imageUpload']['tmp_name']);
  
        $mimeType = $imageInfo['mime'];
        if (!in_array($mimeType, ['image/jpeg', 'image/png'])) {
            echo "<script>
            alert('seleccione un formato de imagen valido (jpg, jprg,png)');
            window.location.href = 'Perfil.php';
        </script>";
        exit;  
        }else {
   
        echo "El archivo es válido y puede ser procesado.";
        $fotoBinaria = file_get_contents($_FILES['imageUpload']['tmp_name']);
        $fotoNombre = $_FILES['imageUpload']['name'];
    
        }

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


        

