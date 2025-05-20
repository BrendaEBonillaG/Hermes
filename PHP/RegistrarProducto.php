<?php
session_start();
require __DIR__ . '/../config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        // Validar cantidad de imágenes y videos subidos
        $imagenes_subidas = isset($_FILES['imagenes']) ? count(array_filter($_FILES['imagenes']['name'])) : 0;
        $videos_subidos = isset($_FILES['videos']) ? count(array_filter($_FILES['videos']['name'])) : 0;

        if ($imagenes_subidas < 3) {
            echo "Error: Debes subir al menos 3 imágenes.";
            exit;
        }
        if ($videos_subidos < 1) {
            echo "Error: Debes subir al menos 1 video.";
            exit;
        }
        $nombre = $_POST['name'];
        $descripcion = $_POST['description'];  
        $precio = $_POST['price'];  
        $cantidad = $_POST['quantity'];  

        $categoria = $_POST['categoria']; 
        $nueva_categoria = $_POST['nueva_categoria']; 


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
        $id_vendedor = $_SESSION['usuario']['id'] ?? 1;
        $tipo = isset($_POST['accept_quotes']) ? 'cotizacion' : 'venta';


        $stmt = $pdo->prepare("INSERT INTO Productos (nombre, descripcion, precio, cantidad_Disponible, tipo, id_vendedor, id_categoria, estado)
                               VALUES (?, ?, ?, ?, ?, ?, ?, 'pendiente')");
        $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $tipo, $id_vendedor, $id_categoria]);

        $id_producto = $pdo->lastInsertId();

        $raizServidor = $_SERVER['DOCUMENT_ROOT'] . '/Hermes/';

 
        if (!is_dir($raizServidor . 'uploads/imagenes')) {
            mkdir($raizServidor . 'uploads/imagenes', 0777, true);
        }
        if (!is_dir($raizServidor . 'uploads/videos')) {
            mkdir($raizServidor . 'uploads/videos', 0777, true);
        }

    
        if (isset($_FILES['imagenes'])) {
            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['imagenes']['error'][$key] === 0) {
                    $mime = mime_content_type($tmp_name);
                    if (str_starts_with($mime, 'image/')) {
                        $nombreOriginal = basename($_FILES['imagenes']['name'][$key]);

                
                        $nombreLimpio = preg_replace('/[^a-zA-Z0-9.\-_]/', '_', $nombreOriginal);

                        $nombreArchivo = uniqid() . '_' . $nombreLimpio;
                        $ruta_destino = $raizServidor . 'uploads/imagenes/' . $nombreArchivo;

                        move_uploaded_file($tmp_name, $ruta_destino);

                
                        $stmtImg = $pdo->prepare("INSERT INTO Imagenes_Productos (id_producto, url_imagen) VALUES (?, ?)");
                        $stmtImg->execute([$id_producto, 'uploads/imagenes/' . $nombreArchivo]);
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
                        $ruta_destino = $raizServidor . 'uploads/videos/' . $nombreArchivo;

                        move_uploaded_file($tmp_name, $ruta_destino);

                        $stmtVid = $pdo->prepare("INSERT INTO Videos_Productos (id_producto, url_video) VALUES (?, ?)");
                        $stmtVid->execute([$id_producto, 'uploads/videos/' . $nombreArchivo]);
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