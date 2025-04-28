<?php
session_start();
require 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $categoria = $_POST['categoria']; // Este puede ser ID o NOMBRE dependiendo tu formulario
        $aceptaCotizaciones = isset($_POST['acepta_cotizaciones']) ? 'Sí' : 'No';

        $id_vendedor = $_SESSION['id_usuario'] ?? 1;
        $tipo = 'venta';

        // 🔥 1. Revisar si la categoría es ID o texto
        if (is_numeric($categoria)) {
            $id_categoria = $categoria; // Ya es un ID
        } else {
            // Buscar si existe la categoría por nombre
            $stmtCat = $pdo->prepare("SELECT id FROM Categorias WHERE nombre = ?");
            $stmtCat->execute([$categoria]);
            $categoriaExistente = $stmtCat->fetch(PDO::FETCH_ASSOC);

            if ($categoriaExistente) {
                $id_categoria = $categoriaExistente['id']; // Ya existe
            } else {
                // Insertar nueva categoría
                $stmtNuevaCat = $pdo->prepare("INSERT INTO Categorias (nombre, descripcion, id_usuario) VALUES (?, ?, ?)");
                $stmtNuevaCat->execute([$categoria, 'Categoría creada automáticamente', $id_vendedor]);
                $id_categoria = $pdo->lastInsertId();
            }
        }

        // 🔥 2. Insertar el producto
        $stmt = $pdo->prepare("INSERT INTO Productos (nombre, descripcion, precio, cantidad_Disponible, tipo, id_vendedor, id_categoria, estado)
                               VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");
        $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $tipo, $id_vendedor, $id_categoria]);

        $id_producto = $pdo->lastInsertId();

        // Crear carpetas si no existen
        if (!is_dir('uploads/imagenes')) {
            mkdir('uploads/imagenes', 0777, true);
        }
        if (!is_dir('uploads/videos')) {
            mkdir('uploads/videos', 0777, true);
        }

        // Subir imágenes
        if (isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['imagenes']['error'][$key] === 0) {
                    $mime = mime_content_type($tmp_name);
                    if (str_starts_with($mime, 'image/')) {
                        $nombreArchivo = uniqid() . '_' . basename($_FILES['imagenes']['name'][$key]);
                        $ruta_destino = "uploads/imagenes/" . $nombreArchivo;
                        move_uploaded_file($tmp_name, $ruta_destino);

                        $stmtImg = $pdo->prepare("INSERT INTO Imagenes_Productos (id_producto, url_imagen) VALUES (?, ?)");
                        $stmtImg->execute([$id_producto, $ruta_destino]);
                    }
                }
            }
        }

        // Subir videos
        if (isset($_FILES['videos'])) {
            foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['videos']['error'][$key] === 0) {
                    $mime = mime_content_type($tmp_name);
                    if (str_starts_with($mime, 'video/')) {
                        $nombreArchivo = uniqid() . '_' . basename($_FILES['videos']['name'][$key]);
                        $ruta_destino = "uploads/videos/" . $nombreArchivo;
                        move_uploaded_file($tmp_name, $ruta_destino);

                        $stmtVid = $pdo->prepare("INSERT INTO Videos_Productos (id_producto, url_video) VALUES (?, ?)");
                        $stmtVid->execute([$id_producto, $ruta_destino]);
                    }
                }
            }
        }

        echo "Producto registrado exitosamente.";

    } else {
        echo "No se enviaron datos.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
