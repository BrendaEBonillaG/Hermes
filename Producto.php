<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Si no hay sesión, redirige al usuario a la página de inicio de sesión
    header('Location: Index.php');
    exit; // Detiene la ejecución del script
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Producto</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link href="CSS/Carrito.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="CSS/Fondo.css">
    <link rel="stylesheet" href="CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <!-- Barra de navegación -->
    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>

            <?php if ($_SESSION['usuario']['rol'] === 'cliente'): ?>
                <li><a href="#" id="abrirCarritoNavbar"><i class="bi bi-cart"></i> Carrito de compras</a></li>

                <li><a href="Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>

            <?php elseif ($_SESSION['usuario']['rol'] === 'vendedor'): ?>
                <li><a href="Vendedor/CrearProduc.php"><i class="bi bi-list"></i> Subir producto</a></li>

            <?php endif; ?>

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

    <div class="product-container">
        <div class="thumbnail-container">

        </div>

        <div>
            <img src="" alt="Imagen principal del producto" class="main-image" id="mainImage">
        </div>

        <div class="product-info">


            <div>
                <div id="nombre" class="product-title"></div>
                <div id="descripcion" class="product-description"></div>

                <div id="vendedor" class="product-vendedor"></div>
                <div id="precio" class="product-price"></div>
                <ul class="product-details">

                    <li>
                        <p id="cantidad_dis"></p>
                    </li>
                    <li><strong>Tiempo de envío:</strong> 2-4 días hábiles</li>
                    <li><strong>Política de devoluciones:</strong> Devoluciones gratuitas en 30 días</li>
                    <li><strong>Envío gratuito:</strong> Disponible en pedidos mayores a $500</li>

                    <li><strong>Calificación:</strong>
                        <!-- From Uiverse.io by SelfMadeSystem -->
                        <div class="rating">
                            <input type="radio" id="star-1" name="star-radio" value="star-1">
                            <label for="star-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-2" name="star-radio" value="star-1">
                            <label for="star-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-3" name="star-radio" value="star-1">
                            <label for="star-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-4" name="star-radio" value="star-1">
                            <label for="star-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                            <input type="radio" id="star-5" name="star-radio" value="star-1">
                            <label for="star-5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path pathLength="360"
                                        d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z">
                                    </path>
                                </svg>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="botones" id="botones">
            <ul class="envio-details">
                <li id="precio2"></li>
                <li><span id="fecha-entrega"></span>, realiza tu pedido en 12 horas</li>
                <li>*Dirección del usuario*</li>
                <li><strong>Disponible!</strong></li>
                <li>
                    Cantidad:
                    <div class="cantidad-selector">
                        <button class="btn-cantidad" id="decrementar">-</button>
                        <input type="number" id="cantidad" value="1">
                        <button class="btn-cantidad" id="incrementar">+</button>
                    </div>
                </li>
                <li>
                    <!-- From Uiverse.io by JaydipPrajapati1910 -->
        
                        ...<button class="button add-to-cart">
                            <svg viewBox="0 0 16 16" class="bi bi-cart-check" height="24" width="24"
                                xmlns="http://www.w3.org/2000/svg" fill="#333">
                                <path
                                    d="M11.354 6.354a.5.5 0 0 0-.708-.708L8 8.293 6.854 7.146a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z">
                                </path>
                                <path
                                    d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z">
                                </path>
                            </svg>
                            <p class="text">Agregar al carrito</p>
                        </button>
     


                </li>
                <li>
                    <iframe src="wishlist.html" width="100%" height="500px" style="border:none; flex: 1;"></iframe>
                </li>
                <li> Devoluciones gratuitas en 30 días</li>
                <li>Enviado por Monarca</li>
            </ul>
        </div>
    </div>


    <div class="similar-products">
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg" alt="Producto Similar 1">
            <p>Producto Similar 1</p>
            <span>$249.99</span>
        </div>
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg" alt="Producto Similar 2">
            <p>Producto Similar 2</p>
            <span>$199.99</span>
        </div>
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg" alt="Producto Similar 3">
            <p>Producto Similar 3</p>
            <span>$279.99</span>
        </div>
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg" alt="Producto Similar 4">
            <p>Producto Similar 4</p>
            <span>$249.99</span>
        </div>
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg" alt="Producto Similar 5">
            <p>Producto Similar 5</p>
            <span>$199.99</span>
        </div>
        <div class="similar-item" onclick="window.location.href='Producto.php'">
            <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg" alt="Producto Similar 6">
            <p>Producto Similar 6</p>
            <span>$279.99</span>
        </div>
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

        <!-- Modal del Carrito -->
        <div id="modalCarrito" class="modal">
            <div class="modal-content carrito-modal">
                <span class="close"
                    onclick="document.getElementById('modalCarrito').style.display='none'">&times;</span>
                <h2>Tu Carrito</h2>
                <div id="contenidoCarrito">
                    <!-- Productos agregados se insertarán aquí dinámicamente -->
                </div>
                <div class="total-carrito">
                    Total: <span id="totalCarrito">$0.00</span>
                </div>
                <button class="btn-modal confirm" onclick="abrirVentanaPago()">Finalizar compra</button>
            </div>
        </div>

        <script>
            function changeImage(src) {
                document.getElementById('mainImage').src = src;
            }
        </script>

        <script>
            function abrirVentanaPago() {
                window.open('tarjeta.html', '_blank', 'width=600,height=600');
            }
        </script>




        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const producto = JSON.parse(localStorage.getItem("productoSeleccionado"));

                if (!producto) {
                    document.body.innerHTML = "<p>Producto no encontrado.</p>";
                    return;
                }

                // Aquí muestra los datos del producto como quieras
                document.getElementById("nombre").textContent = producto.nombre;
                document.getElementById("precio").textContent = "$" + producto.precio;
                document.getElementById("precio2").textContent = "$" + producto.precio;
                document.getElementById("descripcion").textContent = producto.descripcion;

                document.getElementById("vendedor").textContent = "Vendido por: " + producto.nombreVendedor;
                document.getElementById("cantidad_dis").textContent = "Cantidad:" + producto.cantidad_Disponible;

                const hoy = new Date();
                const fechaFinal = sumarDiasHabiles(hoy, 2);
                const opcionesFormato = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                const fechaFormateada = new Intl.DateTimeFormat('es-ES', opcionesFormato).format(fechaFinal);

                // Ponerlo en el HTML
                document.getElementById("fecha-entrega").textContent = "Entrega el: " + fechaFormateada.charAt(0).toUpperCase() + fechaFormateada.slice(1);

                // Control de cantidad
                const inputCantidad = document.getElementById("cantidad");
                const btnIncrementar = document.getElementById("incrementar");
                const btnDecrementar = document.getElementById("decrementar");

                const stock = parseInt(producto.cantidad_Disponible);

                inputCantidad.max = stock;
                inputCantidad.min = stock > 0 ? 1 : 0;
                inputCantidad.value = stock > 0 ? 1 : 0;
                inputCantidad.readOnly = true;

                btnIncrementar.addEventListener("click", () => {
                    let valor = parseInt(inputCantidad.value);
                    if (valor < inputCantidad.max) {
                        inputCantidad.value = valor + 1;
                        actualizarPrecioTotal();

                    }
                });

                btnDecrementar.addEventListener("click", () => {
                    let valor = parseInt(inputCantidad.value);
                    if (valor > inputCantidad.min) {
                        inputCantidad.value = valor - 1;
                        actualizarPrecioTotal();

                    }
                });

                // Si no hay stock, deshabilita botones
                if (stock === 0) {
                    btnIncrementar.disabled = true;
                    btnDecrementar.disabled = true;
                }



                const mainImage = document.getElementById("mainImage");
                const thumbnailContainer = document.querySelector(".thumbnail-container");

                // Establecer la primera imagen como principal
                mainImage.src = producto.imagenes[0];

                // Limpiar contenedor de miniaturas
                thumbnailContainer.innerHTML = "";

                // Generar miniaturas dinámicamente
                producto.imagenes.forEach((url, index) => {
                    const thumb = document.createElement("img");
                    thumb.src = url;
                    thumb.alt = `Miniatura ${index + 1}`;
                    thumb.classList.add("thumbnail");

                    // Al hacer clic, cambia la imagen principal
                    thumb.addEventListener("click", () => {
                        mainImage.src = url;

                        // Opcional: resaltar la miniatura seleccionada
                        document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
                        thumb.classList.add("active");
                    });

                    thumbnailContainer.appendChild(thumb);
                });

                // Marcar como activa la primera miniatura
                thumbnailContainer.firstChild.classList.add("active");

                function actualizarPrecioTotal() {
                    const cantidad = parseInt(inputCantidad.value) || 0;
                    const total = producto.precio * cantidad;
                    document.getElementById("precio2").textContent = "$" + total.toFixed(2);
                }

                function sumarDiasHabiles(fecha, diasHabilesASumar) {
                    let resultado = new Date(fecha);
                    let diasSumados = 0;

                    while (diasSumados < diasHabilesASumar) {
                        resultado.setDate(resultado.getDate() + 1);
                        const diaSemana = resultado.getDay(); // 0 = domingo, 6 = sábado

                        if (diaSemana !== 0 && diaSemana !== 6) {
                            diasSumados++;
                        }
                    }

                    return resultado;
                }



            });



        </script>

        <script src="JS/app.js"></script>
</body>

</html>