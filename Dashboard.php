<?php
session_start(); // Inicia la sesión
require_once './config.php';
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, redirige al usuario a la página de inicio de sesión
    header('Location: Index.php');
    exit; // Detiene la ejecución del script
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/Carrito.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="CSS/Fondo.css">
    <link rel="stylesheet" href="CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Productos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


        <?php
     $sql = "SELECT * FROM productos"; // por ejemplo
$stmt = $conn->prepare($sql);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$precios = array_column($productos, 'precio');
$precioMin = min($precios);

$precios = array_column($productos, 'precio');
$precioMax = max($precios);
        ?>

</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>

            <?php if ($_SESSION['usuario']['rol'] === 'cliente'): ?>
                <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
                <li><a href="Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>

            <?php elseif ($_SESSION['usuario']['rol'] === 'vendedor'): ?>
                <li><a href="Vendedor/CrearProduc.php"><i class="bi bi-list"></i> Subir producto</a></li>

            <?php endif; ?>

            <li><a href="Chat.php"><i class="bi bi-chat-dots"></i> Chats</a></li>

            <li>
                <form class="search-form" id="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input" id="search-input">
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

    <aside class="filtros" id="filtros">
    <h2>Filtros</h2>
    <form id="filtrosForm">
        <label for="precio">Precio:</label>
        <input type="range" id="precio" min="<?= $precioMin ?>" max="<?= $precioMax ?>" step="1"  onchange="updatePrecioValue()">
        <span id="precioValor">0</span>

        <!-- Otros filtros -->
        <label for="masVendidos">Más Vendidos:</label>
        <input type="radio" id="masVendidos" name="venta" value="masVendidos">

        <label for="menosVendidos">Menos Vendidos:</label>
        <input type="radio" id="menosVendidos" name="venta" value="menosVendidos">

        <label for="calificacion">Calificación:</label>
        <select id="calificacion">
            <option value="1">1 estrella</option>
            <option value="2">2 estrellas</option>
            <option value="3">3 estrellas</option>
            <option value="4">4 estrellas</option>
            <option value="5">5 estrellas</option>
        </select>

        <button type="submit" onclick="event.preventDefault(); aplicarFiltros();">Aplicar Filtros</button>
    </form>
</aside>

    <main>
        <!-- Contenedor principal para los productos -->
        <div class="contenedorProductos" id="contenedorProductos">
            <div class="contenedor" id="contenedor"></div>
        </div>

        <!-- Contenedor para la información de la compra -->
        <div id="contenedorCompra">
            <div class="informacionCompra" id="informacionCompra">
                <h2>Carrito</h2>
                <p class="x" id="x">x</p>
            </div>

            <div class="productosCompra" id="productosCompra"></div>
            <div class="total" id="total"></div>
            <button type="button" class="btn btn-primary" onclick="abrirVentanaPago()">
                Finalizar Compra
            </button>

        </div>
    </main>


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
    </div>

    <!-- Modal del Carrito -->
    <div id="modalCarrito" class="modal">
        <div class="modal-content carrito-modal">
            <span class="close" onclick="document.getElementById('modalCarrito').style.display='none'">&times;</span>
            <h2>Tu Carrito</h2>
            <div id="contenidoCarrito">
                <!-- Productos agregados se insertarán aquí dinámicamente -->
            </div>
            <div class="total-carrito">
                Total: <span id="totalCarrito">$0.00</span>
            </div>
            <button class="btn-modal confirm"  id="finalizarPago" onclick="abrirVentanaPago()">Finalizar compra</button>
        </div>
    </div>


    <script>
        function abrirVentanaPago() {
            window.open('tarjeta.html', '_blank', 'width=600,height=600');
        }
    </script>


    <script src="JS/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.getElementById("search-form").addEventListener("submit", function(e) {
    e.preventDefault(); // Evita que se recargue la página

    const busqueda = document.getElementById("search-input").value.trim();
    console.log("Término de búsqueda enviado:", busqueda); // ✅ Debug

    // Llama a tu función de visualización con el término de búsqueda
    visualizarProductos(busqueda);
});
</script>
</body>


</html>

  