<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de productos</title>
    <link rel="stylesheet" href="../CSS/Fondo.css">
    <link rel="stylesheet" href="../CSS/RevisionAdmin.css">
    <link rel="stylesheet" href="../CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

<?php
session_start();
require __DIR__ . '/../config.php';

// Variables de control
$user_id = $_SESSION['usuario']['id'];
$rol_usuario = $_SESSION['usuario']['rol'];
$nombre_usuario = $_SESSION['usuario']['nombreUsu'];

$user =[];

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn ->prepare($sql);  // Prepara la consulta
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Vincula el parámetro
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$products = [];

    // Consulta para obtener los productos del vendedor
    $sql = "SELECT * FROM Productos WHERE estado = 'pendiente'";
    $stmt = $conn ->prepare($sql);  // Prepara la consulta

    $stmt->execute();  // Ejecuta la consulta

    // Obtener los resultados con PDO
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>



    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>
            <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
             <li><a href="Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>
            <li><a href="Chat.html"><i class="bi bi-chat-dots"></i> Chats</a></li>
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
            <li><a href="#" onclick="document.getElementById('logoutModal').style.display='block'"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>

    <div class="container">
        

        <?php
                if (count($products) > 0) {
                    // Mostrar cada producto
                    foreach ($products as $product) {

                        echo '<div class="card">';
                             
                        echo '<div class="image-slider">';
                        echo '<div class="image-placeholder">'.'</div>';
                        
                        echo '<div class="image-placeholder">'.'</div>';
                        
                        echo '<div class="image-placeholder">'.'</div>';
                        echo '</div>';
                        
                       

                        echo '<div class="info">';
                        echo '<h3>' . htmlspecialchars($product['nombre']) . '</h3>';
                        echo '<p>' . htmlspecialchars($product['descripcion']) . '</p>';
                        echo '<p>' . htmlspecialchars($product['categoria']) . '</p>';
                        echo '<p>' .  number_format($product['precio'], 2) . htmlspecialchars($product['cantidad_Disponible']) .  '</p>';
                        echo '</div>';

                     
                      

                            // Formulario para aceptar el producto
    echo '<form method="POST" action="procesar_revision.php">';
    echo '<input type="hidden" name="id_producto" value="' . htmlspecialchars($product['id']) . '">';
    echo '<input type="hidden" name="accion" value="aceptar">';
    echo '<button type="submit" class="accept">Aceptar</button>';
    echo '</form>';

    // Formulario para rechazar el producto
    echo '<form method="POST" action="procesar_revision.php">';
    echo '<input type="hidden" name="id_producto" value="' . htmlspecialchars($product['id']) . '">';
    echo '<input type="hidden" name="accion" value="rechazar">';
    echo '<button type="submit" class="reject">Rechazar</button>';
    echo '</form>';

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
                <button class="btn-modal confirm" onclick="window.location.href='../PHP/Logout.php'">Sí, cerrar sesión</button>
                <button class="btn-modal cancel" onclick="document.getElementById('logoutModal').style.display='none'">Cancelar</button>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>