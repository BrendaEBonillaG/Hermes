<?php
// Activar el reporte de errores solo para desarrollo (desactivado en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuración de cabeceras
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

// Incluir archivo de configuración de base de datos
include 'config.php';

// Leer usuarios
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM Usuarios");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($usuarios);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Error al obtener los usuarios: ' . $e->getMessage()]);
    }
}

// Función para validar si hay caracteres especiales (exceptuando ñ, Ñ y acentos)
function validar_caracteres_especiales($cadena) {
    return preg_match('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ@#$%^&*()_+\-=\[\]\{\};:\'",<>\./?\\|`~ ]/', $cadena);
}

// Recibir los datos del formulario y la imagen
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['correo'], $_POST['nombreUsu'], $_POST['contrasena'], $_POST['rol'], $_POST['nombres'], $_POST['apePa'], $_POST['apeMa'], $_POST['sexo'], $_POST['privacidad'])) {
        $correo = $_POST['correo'];
        $nombreUsu = $_POST['nombreUsu'];
        $contrasena = $_POST['contrasena'];
        $rol = $_POST['rol'];
        $nombres = $_POST['nombres'];
        $apePa = $_POST['apePa'];
        $apeMa = $_POST['apeMa'];
        $fechaNacim = $_POST['fechaNacim'] ?? null;
        $sexo = $_POST['sexo'];
        $privacidad = $_POST['privacidad'];

        // Validar caracteres especiales en el nombre de usuario y contraseña
        if (validar_caracteres_especiales($nombreUsu)) {
            echo json_encode(['message' => 'El nombre de usuario contiene caracteres no permitidos']);
            exit;
        }

        if (validar_caracteres_especiales($contrasena)) {
            echo json_encode(['message' => 'La contraseña contiene caracteres no permitidos']);
            exit;
        }

        $contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT);

        // Manejo de la imagen
        $foto = null;
        $fotoNombre = null;
        if (isset($_FILES['foto'])) {
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
            $fotoNombre = $_FILES['foto']['name'];
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO Usuarios (correo, nombreUsu, contrasena, rol, nombres, apePa, apeMa, fechaNacim, sexo, privacidad, foto, fotoNombre) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$correo, $nombreUsu, $contrasena_encriptada, $rol, $nombres, $apePa, $apeMa, $fechaNacim, $sexo, $privacidad, $foto, $fotoNombre]);
            echo json_encode(['message' => 'Usuario creado de manera exitosa']);
        } catch (Exception $e) {
            echo json_encode(['message' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
}

// Actualizar usuario
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents("php://input"));

    // Verificación de los campos
    if (isset($data->id) && isset($data->correo) && isset($data->nombreUsu) && isset($data->contrasena) && isset($data->rol) && isset($data->nombres) && isset($data->apePa) && isset($data->apeMa) && isset($data->sexo) && isset($data->privacidad)) {
        $id = $data->id;
        $correo = $data->correo;
        $nombreUsu = $data->nombreUsu;
        $contrasena = $data->contrasena;
        $rol = $data->rol;
        $nombres = $data->nombres;
        $apePa = $data->apePa;
        $apeMa = $data->apeMa;
        $fechaNacim = $data->fechaNacim ?? null; // Puede no estar presente
        $sexo = $data->sexo;
        $privacidad = $data->privacidad;
        $foto = isset($data->foto) ? $data->foto : null;
        $fotoNombre = isset($data->fotoNombre) ? $data->fotoNombre : null;

        // Validación de caracteres especiales en el nombre de usuario y contraseña
        if (validar_caracteres_especiales($nombreUsu)) {
            echo json_encode(['message' => 'El nombre de usuario contiene caracteres no permitidos']);
            exit;
        }

        if (validar_caracteres_especiales($contrasena)) {
            echo json_encode(['message' => 'La contraseña contiene caracteres no permitidos']);
            exit;
        }

        // Validar la contraseña
        $contrasena_valida = validar_contrasena($contrasena);
        if ($contrasena_valida !== true) {
            echo json_encode(['message' => $contrasena_valida]);
            exit;
        }

        $contrasena_encriptada = password_hash($contrasena, PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("UPDATE Usuarios SET correo = ?, nombreUsu = ?, contrasena = ?, rol = ?, nombres = ?, apePa = ?, apeMa = ?, fechaNacim = ?, sexo = ?, privacidad = ?, foto = ?, fotoNombre = ? WHERE id = ?");
            $stmt->execute([$correo, $nombreUsu, $contrasena_encriptada, $rol, $nombres, $apePa, $apeMa, $fechaNacim, $sexo, $privacidad, $foto, $fotoNombre, $id]);

            echo json_encode(['message' => 'Usuario actualizado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
}

// Eliminar usuario
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id)) {
        $id = $data->id;

        try {
            $stmt = $pdo->prepare("DELETE FROM Usuarios WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['message' => 'Usuario eliminado correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Error en la base de datos: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'ID no proporcionado']);
    }
}
?>
