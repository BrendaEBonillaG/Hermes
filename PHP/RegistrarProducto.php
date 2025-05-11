<?php
session_start();
require __DIR__ . '/../config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Recuperar datos del formulario
        $nombre = $_POST['name'];
        $descripcion = $_POST['description'];
        $precio = $_POST['price'];
        $cantidad = $_POST['quantity'];

        // Validar precio y cantidad
        if ($precio < 0 || $cantidad < 0) {
            echo "Error: El precio y la cantidad no pueden ser negativos.";
            exit;
        }

        // Validar que la categoría esté seleccionada o se haya proporcionado una nueva categoría
        $categoria = $_POST['categoria'] ?? null;
        $categoria_id = $_POST['categoria_omitida'] ?? null;

        // Validar la categoría nueva o existente
        if ($categoria === 'nueva' && isset($_POST['nuevaCategoria']) && !empty($_POST['nuevaCategoria'])) {
            // Nueva categoría
            $nuevaCategoria = trim($_POST['nuevaCategoria']);
            $stmtCat = $pdo->prepare("INSERT INTO Categorias (nombre, descripcion, id_usuario) VALUES (?, ?, ?)");
            $stmtCat->execute([$nuevaCategoria, 'Categoría creada automáticamente', $_SESSION['id_usuario']]);
            $id_categoria = $pdo->lastInsertId();
        } elseif ($categoria !== 'nueva' && is_numeric($categoria_id) && $categoria_id > 0) {
            // Categoría existente
            $id_categoria = $categoria_id;
        } else {
            // Error si no se seleccionó o proporcionó una categoría válida
            echo "Error: Debes seleccionar una categoría existente o agregar una nueva.";
            exit;
        }

        // Insertar el producto en la base de datos
        $id_vendedor = $_SESSION['id_usuario'] ?? 1;  // ID del vendedor
        $tipo = 'venta';

        $stmt = $pdo->prepare("INSERT INTO Productos (nombre, descripcion, precio, cantidad_Disponible, tipo, id_vendedor, id_categoria, estado)
                               VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");
        $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $tipo, $id_vendedor, $id_categoria]);

        $id_producto = $pdo->lastInsertId();

        // Crear directorios de subida si no existen
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

        // Redirigir con éxito
        header("Location: ../Vendedor/CrearProduc.php?success=1");
        exit;

    } else {
        echo "No se enviaron datos.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
