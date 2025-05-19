<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de productos</title>
    <link rel="stylesheet" href="CSS/Fondo.css">
    <link rel="stylesheet" href="CSS/Pedidos.css">
    <link rel="stylesheet" href="CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <?php
    session_start();
    require __DIR__ . '/config.php';

    // Variables de control
    $user_id = $_SESSION['usuario']['id'];
    $rol_usuario = $_SESSION['usuario']['rol'];
    $nombre_usuario = $_SESSION['usuario']['nombreUsu'];

    $user = [];

    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);  // Prepara la consulta
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Vincula el parámetro
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT 
            Compra.*, 
            productos.nombre AS nombre_producto,
            productos.descripcion,
            productos.precio,
            GROUP_CONCAT(ip.url_imagen) AS imagenes_producto
        FROM Compra
        INNER JOIN productos ON Compra.id_producto = productos.id
        LEFT JOIN Imagenes_Productos ip ON productos.id = ip.id_producto
        WHERE Compra.id_comprador = ?
        GROUP BY Compra.id";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC); // Aquí tienes todas las compras del usuario
    

    function sumarDiasHabilesPHP($fecha, $diasHabilesASumar)
    {
        $fecha = new DateTime($fecha);
        $diasSumados = 0;

        while ($diasSumados < $diasHabilesASumar) {
            $fecha->modify('+1 day');
            $diaSemana = $fecha->format('N'); // 1 = lunes, 7 = domingo
    
            if ($diaSemana < 6) {
                $diasSumados++;
            }
        }

        return $fecha;
    }
    foreach ($compras as $compra) {
        // Asumiendo que $compra['fechaIngreso'] contiene una fecha válida de la compra
        $fechaIngreso = $compra['fechaIngreso'];

        // Sumamos 2 días hábiles
        $fechaEntrega = sumarDiasHabilesPHP($fechaIngreso, 2);

        // Formateamos la fecha en español
        setlocale(LC_TIME, 'Spanish_Spain'); // En sistemas Unix/Linux
// Para Windows, podrías necesitar: setlocale(LC_TIME, 'Spanish_Spain');
    
        // Mostrar la fecha formateada
        $fechaFormateada = strftime('%A, %d de %B de %Y', $fechaEntrega->getTimestamp());

        // Capitalizar la primera letra manualmente
        $fechaFormateada = utf8_encode(strftime('%A, %d de %B de %Y', $fechaEntrega->getTimestamp()));
        $fechaFormateada = ucfirst($fechaFormateada);




    }

    ?>

    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>
            <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
            <li><a href="Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>
            <li><a href="Chat.php"><i class="bi bi-chat-dots"></i> Chats</a></li>
            <li>
                <form class="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>
            <li><a href="Perfil.php" class="profile-link">
                    <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img-navbar">
                </a></li>
            <li><a href="#" onclick="document.getElementById('logoutModal').style.display='block'"><i
                        class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>


    <div class="container">
        <div class="Botones">
            <button class="Proceso">En proceso</button>
            <button class="Enviados">Volver a comprar</button>
        </div>

        <?php
        if (count($compras) > 0) {
            // Mostrar cada producto
            foreach ($compras as $compra) {

                echo '<div class="card">';

                echo '<div class="image-slider">';

                if (!empty($compra['Imagenes_Productos'])) {
                    foreach ($compra['Imagenes_Productos'] as $imagen_url) {
                        echo '<div class="image-placeholder">';
                        echo '<img src="' . htmlspecialchars($imagen_url) . '" alt="Imagen del producto" style="width:100%; height:auto;">';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="image-placeholder">';
                    echo '<img src="ruta/a/imagen_por_defecto.jpg" alt="Sin imagen" style="width:100%; height:auto;">';
                    echo '</div>';
                }
                echo '</div>';



                echo '<div class="info">';
                echo '<h3>' . htmlspecialchars($compra['nombre_producto']) . '</h3>';
                echo '<p>' . htmlspecialchars($compra['descripcion']) . '</p>';
                echo '<p>Precio individual' . number_format($compra['precio'], 2) . '</p>';
                echo '<p>cantidad' . number_format($compra['cantidad']) . '</p>';
                $total = $compra['precio'] * $compra['cantidad'];

                echo '<p>Total: $' . number_format($total, 2) . '</p>';
                echo '<p>' . htmlspecialchars($compra['fechaIngreso']) . '</p>';
                echo "<p>Entrega el: $fechaFormateada</p>";


                echo '</div>';

                echo '</div>';



            }
        } else {
            echo '<p>No hay productos que revisar</p>';
        }
        ?>


        
    </div>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('logoutModal').style.display='none'">&times;</span>
            <h2>¿Deseas cerrar sesión?</h2>
            <div class="modal-actions">
                <button class="btn-modal confirm" onclick="window.location.href='../Hermes/PHP/Logout.php'">Sí, cerrar
                    sesión</button>
                <button class="btn-modal cancel"
                    onclick="document.getElementById('logoutModal').style.display='none'">Cancelar</button>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>