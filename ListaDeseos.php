<?php
require 'config.php';

$idLista = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idLista <= 0) {
    die("Lista no encontrada.");
}

// Primero verificamos si la lista existe
$stmtLista = $conn->prepare("SELECT * FROM Listas WHERE id = ?");
$stmtLista->execute([$idLista]);
$lista = $stmtLista->fetch();

if (!$lista) {
    die("Lista no encontrada.");
}

// Ahora buscamos los productos de esa lista
// Ahora buscamos los productos de esa lista, con más campos (precio, stock, categoria)
$stmtProductos = $conn->prepare("
    SELECT p.nombre, p.descripcion, p.precio, p.cantidad_Disponible AS stock, c.nombre AS categoria
    FROM productos p
    INNER JOIN Listas_Productos lp ON p.id = lp.id_producto
    INNER JOIN Categorias c ON p.id_categoria = c.id
    WHERE lp.id_lista = ?
");
$stmtProductos->execute([$idLista]);
$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Revisión de productos - <?= htmlspecialchars($lista['nombre']) ?></title>
    <link rel="stylesheet" href="CSS/Fondo.css" />
    <link rel="stylesheet" href="CSS/ListaDeseos.css" />
    <link rel="stylesheet" href="CSS/Navbar.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>

    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>
            <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
            <li><a href="Pedidos.php"><i class="bi bi-list"></i> Pedidos</a></li>
            <li><a href="Chat.php"><i class="bi bi-chat-dots"></i> Chats</a></li>
            <li>
                <form class="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input" />
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>
            <li>
                <a href="Perfil.php" class="profile-link">
                    <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img-navbar" />
                </a>
            </li>
            <li><a href="#" onclick="document.getElementById('logoutModal').style.display='block'"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="InfoList">
            <img class="FotoLista" src="<?= htmlspecialchars($lista['foto'] ?: 'https://i.pinimg.com/736x/59/cf/e6/59cfe6edf6c814ab75ddf4c64d299053.jpg') ?>" alt="Foto de lista" />
            <div class="TextoLista">
                <h1><?= htmlspecialchars($lista['nombre']) ?></h1>
                <h2><?= nl2br(htmlspecialchars($lista['descripcion'])) ?></h2>
            </div>
        </div>

        <?php if (count($productos) > 0): ?>
            <?php foreach ($productos as $producto): ?>
                <div class="card">
                    <div class="image-slider">
                        <div class="image-placeholder"></div>
                        <div class="image-placeholder"></div>
                        <div class="image-placeholder"></div>
                    </div>

                    <div class="info">
                        <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
                        <p>Categoría: <?= htmlspecialchars($producto['categoria']) ?></p>
                        <p>Precio: $<?= number_format($producto['precio'], 2) ?> - Stock: <?= intval($producto['stock']) ?></p>
                    </div>

                    <div class="buttons">
                        <button class="button">
                            <svg viewBox="0 0 16 16" class="bi bi-cart-check" height="24" width="24" xmlns="http://www.w3.org/2000/svg" fill="#fff">
                                <path d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 
                                        1.5a.5.5 0 0 0 .708 0l3-3z"></path>
                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 
                                        1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 
                                        0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 
                                        0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 
                                        0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 
                                        0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 
                                        7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 
                                        2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z">
                                </path>
                            </svg>
                            <p class="text">Agregar al carrito</p>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos en esta lista.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
