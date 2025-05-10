<?php
session_start();
require __DIR__ . '/../config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nombre = $_POST['name'];  // Aquí debería ser 'name'
        $descripcion = $_POST['description'];  // Aquí debería ser 'description'
        $precio = $_POST['price'];  // Aquí debería ser 'price'
        $cantidad = $_POST['quantity'];  // Aquí debería ser 'quantity'
        // Validar que precio y cantidad no sean negativos
        if ($precio < 0 || $cantidad < 0) {
            echo "Error: El precio y la cantidad no pueden ser negativos.";
            exit;
        }


        $categoria = $_POST['categoria']; // ID de categoría seleccionada
        $nueva_categoria = $_POST['nueva_categoria'] ?? null;

        // Verificar si se está añadiendo una nueva categoría
        if (!empty($nueva_categoria)) {


            $stmtCat = $pdo->prepare("INSERT INTO Categorias (nombre, descripcion, id_usuario) VALUES (?, ?, ?)");
            $stmtCat->execute([$nueva_categoria, 'Categoría creada automáticamente', $_SESSION['id_usuario']]);
            $id_categoria = $pdo->lastInsertId();
        } elseif (is_numeric($categoria)) {
            $id_categoria = $categoria;
        } else {

            echo "Error: categoría no válida.";
            exit;
        }

        // Insertar producto
        $id_vendedor = $_SESSION['id_usuario'] ?? 1;
        $tipo = 'venta';

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

        header("Location: ../Vendedor/CrearProduc.php?success=1");
        exit;

    } else {
        echo "No se enviaron datos.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>