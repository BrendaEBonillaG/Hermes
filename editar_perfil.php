<?php
// Iniciar sesión para acceder al usuario actual
session_start();

require './config.php'; 

$usuario_id = $_SESSION['usuario']['id'];  // Obtener el ID del usuario desde la sesión

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar a la base de datos
    try {
        $conn  = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Recoger los datos del formulario
        $nombreUsu = $_POST['nombreUsu'];
        $correo = $_POST['correo'];
        $contrasena = $_POST['contrasena'];  // Si la contraseña está vacía, no la actualizamos
        $rol = $_POST['rol_actual'];
        $nombres = $_POST['nombres'];
        $apePa = $_POST['apePa'];
        $apeMa = $_POST['apeMa'];
        $fechaNacim = $_POST['fechaNacim'];
        $sexo = $_POST['sexo'];
        $privacidad = $_POST['privacidad'];

        if(empty($contrasena)){

            $contrasena = $_POST['contrasena_actual'];

        }else{
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        if(empty($sexo)){

            $sexo = $_POST['sexo_actual'];

        }else{
            
        }

        if(empty($privacidad)){

            $privacidad = $_POST['privacidad_actual'];

        }else{
            
        }





        
      
   
        // Preparar la consulta para actualizar el perfil del usuario
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
                foto = :foto
                WHERE id = :id";

        // Preparar la sentencia SQL
        $stmt = $conn ->prepare($sql);
        
       

        // Vincular los parámetros
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
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);  // Para almacenar una imagen binaria
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
