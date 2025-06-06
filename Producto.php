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
    <link rel="stylesheet" href="CSS/Fondo.css">
    <link rel="stylesheet" href="CSS/Navbar.css">
    <link rel="stylesheet" href="CSS/wishlist.css">
    <link href="CSS/Carrito.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>

    <!-- Barra de navegación -->
    <nav class="navbar">
        <ul class="navbar-menu">
            <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>

            <?php if ($_SESSION['usuario']['rol'] === 'cliente'): ?>
                <li><a href="#" id="abrirCarritoNavbar"><i class="bi bi-cart"></i> Carrito de compras</a></li>

                <li><a href="Pedidos.php"><i class="bi bi-list"></i> Pedidos</a></li>

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
                <div id="id_producto"></div>

                <div id="vendedor" class="product-vendedor"></div>
                <div id="precio" class="product-price"></div>
                <ul class="product-details">

                    <li>
                        <p id="cantidad_dis"></p>
                    </li>
                    <li><strong>Tiempo de envío:</strong> 2-4 días hábiles</li>
                    <li><strong>Política de devoluciones:</strong> Devoluciones gratuitas en 30 días</li>
                    <li><strong>Envío gratuito:</strong> Disponible en pedidos mayores a $500</li>

                    <li> <label for="calificacion">Calificación:</label>
                        <select id="calificacion">
                            <option value="1">1 estrella</option>
                            <option value="2">2 estrellas</option>
                            <option value="3">3 estrellas</option>
                            <option value="4">4 estrellas</option>
                            <option value="5">5 estrellas</option>
                        </select>
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

                    <button class="button add-to-cart">
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

                    <button id="btnCotizar" class="btn btn-primary">Cotizar</button>



                </li>

                <li>
                    <button id="openWishlistModal" class="btn btn-wishlist">
                        <svg width="56px" height="48px" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 0 0 L 56 0 L 40 48 L 0 48" stroke="none"></path>
                        </svg>
                        <div class="icon">
                            <i class="fa fa-heart"></i>
                        </div>
                        <div class="label">
                            <span class="label-text">Lista de deseos</span>
                        </div>
                    </button>



                </li>
                <li> Devoluciones gratuitas en 30 días</li>
                <li>Enviado por Monarca</li>
            </ul>
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
    </div>


    <!-- Modal del Carrito -->
    <div id="modalCarrito" class="modal">
        <div class="modal-content carrito-modal">
            <button id="cerrarModal" class="close" onclick="cerrarModal()">×</button>
            <h2>Carrito</h2>

            <div id="contenidoCarrito">
                <!-- Ejemplo de producto -->
                <!-- Esto lo debes generar dinámicamente con JS -->

            </div>

            <div class="total-compra">
                <strong>Valor Total</strong>
                <span id="totalCarrito">$155</span>
            </div>

            <button class="btn-modal confirm" onclick="abrirVentanaPago()">Finalizar Compra</button>

        </div>
    </div>

    <!-- Modal de wishlist -->
    <div id="wishlistModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span id="closeWishlistModal" class="close">&times;</span>

            <h2>Lista de deseos</h2>

            <!-- Opción 1: Agregar a lista existente -->
            <form id="formAgregarALista" method="post" enctype="multipart/form-data">
                <label for="listaExistente">Agregar a lista existente:</label>
                <select id="listaExistente" name="id_lista" required>
                    <option value="">Selecciona una lista</option>
                    <!-- Opciones se deben llenar con PHP o JS -->
                </select>
                <input type="hidden" name="id_producto" value="123"> <!-- ID del producto -->
                <button id="agregarAListaBtn">Agregar a lista</button>
            </form>

            <hr>

            <!-- Opción 2: Crear nueva lista -->
            <form id="formCrearLista" method="post" enctype="multipart/form-data">
                <h3>Crear nueva lista de deseos</h3>
                <input type="text" name="nombre" placeholder="Nombre de la lista" required><br>
                <textarea name="descripcion" placeholder="Descripción"></textarea><br>
                <select name="privacidad" required>
                    <option value="privada">Privada</option>
                    <option value="pública">Pública</option>
                </select><br>
                <label>Foto:</label>
                <input type="file" name="foto" accept="image/*"><br>

                <!-- Campo oculto para el ID del producto -->
                <input type="hidden" name="id_producto" id="idProductoHidden">

                <button type="submit">Crear lista y agregar producto</button>
            </form>

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

            // Mostrar información del producto
            document.getElementById("nombre").textContent = producto.nombre;
            document.getElementById("precio").textContent = "$" + producto.precio;
            document.getElementById("precio2").textContent = "$" + producto.precio;
            document.getElementById("descripcion").textContent = producto.descripcion;
            document.getElementById("id_producto").textContent = producto.id;
            document.getElementById("vendedor").textContent = "Vendido por: " + producto.nombreVendedor;
            document.getElementById("cantidad_dis").textContent = "Cantidad:" + producto.cantidad_Disponible;

            // Fecha de entrega en días hábiles
            const hoy = new Date();
            const fechaFinal = sumarDiasHabiles(hoy, 2);
            const opcionesFormato = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaFormateada = new Intl.DateTimeFormat('es-ES', opcionesFormato).format(fechaFinal);
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

            if (stock === 0) {
                btnIncrementar.disabled = true;
                btnDecrementar.disabled = true;
            }

            // Manejo de imágenes
            const mainImage = document.getElementById("mainImage");
            const thumbnailContainer = document.querySelector(".thumbnail-container");

            mainImage.src = producto.imagenes[0];
            thumbnailContainer.innerHTML = "";

            producto.imagenes.forEach((url, index) => {
                const thumb = document.createElement("img");
                thumb.src = url;
                thumb.alt = `Miniatura ${index + 1}`;
                thumb.classList.add("thumbnail");

                thumb.addEventListener("click", () => {
                    mainImage.src = url;
                    document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
                    thumb.classList.add("active");
                });

                thumbnailContainer.appendChild(thumb);
            });

            thumbnailContainer.firstChild.classList.add("active");

            // Botón de cotización
            const btnCotizar = document.getElementById("btnCotizar");
            const btnAgregarCarrito = document.querySelector(".button.add-to-cart");

            // Ocultar botón de cotizar si no es cotización
            if (producto.tipo !== "cotizacion") {
                btnCotizar.style.display = "none";
            } else {
                // Si es cotización, también ocultar el botón de agregar al carrito
                if (btnAgregarCarrito) {
                    btnAgregarCarrito.style.display = "none";
                }

                btnCotizar.addEventListener("click", () => {
                    const idVendedor = producto.id_vendedor;
                    window.location.href = `PHP/crear_chat.php?id=${idVendedor}`;
                });
            }



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
            document.getElementById("openWishlistModal").dataset.productoId = producto.id;
            console.log("Asignando id al botón: ", producto.id);


        });

    </script>

    <script src="JS/carritoDP.js"></script>
    <script src="JS/CrearLista.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>