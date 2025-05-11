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
            <li><a href="../Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>
            <li><a href="Vendedor/CrearProduc.php"><i class="bi bi-list"></i> Subir producto</a></li>
            <li><a href="../Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>
            <li><a href="../Chat.html"><i class="bi bi-chat-dots"></i> Chats</a></li>
            <li>
                <form class="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>
            <li><a href="../Perfil.php" class="profile-link">
                    <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img-navbar">
                </a></li>
            <li><a href="#" onclick="document.getElementById('logoutModal').style.display='block'"><i
                        class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <script>
            alert("Producto registrado exitosamente.");
        </script>
    <?php endif; ?>

    <!-- Formulario de registro-->
    <form class="upload-form" action="../PHP/RegistrarProducto.php" method="POST" enctype="multipart/form-data">
        <div class="container">
            <!-- Sección de subida de multimedia -->
            <div class="upload-section">
                <div class="tabs">
                    <button class="tab">Fotos</button>
                    <button class="tab">Videos</button>
                </div>
                <div class="media-lists">
                    <div class="image-list">

                    </div>

                    <div class="video-list">

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
                <div class="row" style="flex-direction: column;">

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
                        <input type="text" id="nuevaCategoriaInput" class="input-field"
                            placeholder="Nombre de la nueva categoría">
                        <button type="button" class="select-button" onclick="agregarNuevaCategoria()">Agregar</button>
                    </div>


                    <input type="number" name="price" min="0" step="0.01" placeholder="Precio..."
                        class="input-field small" required>
                    <input type="number" name="quantity" min="0" step="1" placeholder="Cantidad..."
                        class="input-field small" required>
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
                <button class="btn-modal confirm" onclick="window.location.href='../PHP/Logout.php'">Sí, cerrar
                    sesión</button>
                <button class="btn-modal cancel"
                    onclick="document.getElementById('logoutModal').style.display='none'">Cancelar</button>
            </div>
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
    <script src="../JS/MostCat.js"></script>
</body>

</html>