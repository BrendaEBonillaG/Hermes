<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revisión de productos</title>
    <link rel="stylesheet" href="../CSS/Fondo.css">
    <link rel="stylesheet" href="../CSS/CrearProduc.css">
    <link rel="stylesheet" href="../CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>

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
            <li><a href="#" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>

    <!-- Formulario de registro-->
    <form class="upload-form" action="RegistrarProducto.php" method="POST" enctype="multipart/form-data">
        <div class="container">
            <!-- Sección de subida de multimedia -->
            <div class="upload-section">
                <div class="tabs">
                    <button class="tab">Fotos</button>
                    <button class="tab">Videos</button>
                </div>
                <div class="media-lists">
                    <div class="image-list">
                        <div class="image-item">
                            <img src="https://i.pinimg.com/736x/37/f3/e6/37f3e6e5f1dc0f4d12f821d673634abe.jpg"
                                alt="Imagen subida">
                            <button class="delete-button" title="Eliminar imagen">🗑️</button>
                        </div>
                        <div class="image-item">
                            <img src="https://i.pinimg.com/736x/2c/a1/99/2ca1990a8a8659d4ca9e7381758806b0.jpg"
                                alt="Imagen subida">
                            <button class="delete-button" title="Eliminar imagen">🗑️</button>
                        </div>
                    </div>

                    <div class="video-list">
                        <div class="video-item">
                            <video src="https://www.w3schools.com/html/mov_bbb.mp4" controls width="150"></video>
                            <button class="delete-button" title="Eliminar video">🗑️</button>
                        </div>
                        <div class="video-item">
                            <video src="https://www.w3schools.com/html/movie.mp4" controls width="150"></video>
                            <button class="delete-button" title="Eliminar video">🗑️</button>
                        </div>
                    </div>
                </div>
                <div class="upload-buttons">

                    <input type="file" id="imagenInput" name="imagenes[]" hidden multiple accept="image/*">
                    <button class="select-button" type="button"
                        onclick="document.getElementById('imagenInput').click()">Seleccionar imagen</button>


                    <input type="file" id="videoInput" name="videos[]" hidden multiple accept="video/*">
                    <button class="selectV-button" type="button"
                        onclick="document.getElementById('videoInput').click()">Seleccionar video</button>
                </div>

            </div>


            <div class="form-section">
                <input type="text" name="name" placeholder="Nombre..." class="input-field">
                <textarea name="description" placeholder="Descripción..." class="input-field"></textarea>
                <div class="row">
                    <?php
                    require_once '../config.php';
                    $categorias = [];

                    // Consulta con PDO (ya que usas $conn que es PDO)
                    $stmt = $conn->query("SELECT id, nombre FROM categorias");
                    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <div class="form-group">
                        <label for="categoria">Categoría:</label>
                        <select id="categoriaSelect" name="categoria" class="input-field"
                            onchange="mostrarInputCategoria(this)">
                            <option value="">-- Selecciona una categoría --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['id']) ?>">
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="nueva">+ Agregar nueva categoría</option>
                        </select>
                    </div>

                    <div id="nuevaCategoriaDiv" style="display: none; margin-top: 10px;">
                        <input type="text" id="nuevaCategoriaInput" name="categoria" class="input-field"
                            placeholder="Nombre de la nueva categoría">
                    </div>

                    <script>
                        function mostrarInputCategoria(select) {
                            const nuevaCategoriaDiv = document.getElementById('nuevaCategoriaDiv');
                            const nuevaCategoriaInput = document.getElementById('nuevaCategoriaInput');

                            if (select.value === 'nueva') {
                                nuevaCategoriaDiv.style.display = 'block';
                                nuevaCategoriaInput.required = true;
                                select.name = "categoria_omitida";
                                nuevaCategoriaInput.name = "categoria";
                            } else {
                                nuevaCategoriaDiv.style.display = 'none';
                                nuevaCategoriaInput.required = false;
                                nuevaCategoriaInput.value = "";
                                select.name = "categoria";
                                nuevaCategoriaInput.name = "categoria_omitida";
                            }
                        }
                    </script>

                    <input type="number" name="price" placeholder="Precio..." class="input-field small">
                    <input type="number" name="quantity" placeholder="Cantidad..." class="input-field small">
                </div>
                <div class="checkbox-container">
                <label>Acepta cotizaciones</label>
                <div class="checkbox-wrapper-12">
                    <div class="cbx">
                        <input type="checkbox" id="cbx-12" name="accept_quotes" checked>
                        <label for="cbx-12"></label>
                        <svg fill="none" viewBox="0 0 15 14" height="14" width="15">
                            <path d="M2 8.36364L6.23077 12L13 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <button type="submit" class="create-button">Crear</button>
            </div>

           
        </div>
        </div>
    </form>


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

        <script>

            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.delete-button');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const imageItem = this.parentElement;
                        imageItem.remove(); // Elimina todo el contenedor
                    });
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../JS/modalLogout.js"></script>
</body>

</html>