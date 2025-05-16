<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Hermes</title>
    <link rel="stylesheet" href="CSS/Perfil.css">
    <link rel="stylesheet" href="CSS/Fondo.css">
    <link rel="stylesheet" href="CSS/Navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <?php
session_start();
require __DIR__ . '/config.php';

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
$listas=[];

// Verificar si el rol es 'vendedor'
if ($rol_usuario === 'vendedor') {


    // Consulta para obtener los productos del vendedor
    $sql = "SELECT * FROM Productos WHERE id_vendedor = ? AND estado = 'aceptado'";
    $stmt = $conn ->prepare($sql);  // Prepara la consulta
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Vincula el parámetro
    $stmt->execute();  // Ejecuta la consulta

    // Obtener los resultados con PDO
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else if($rol_usuario === 'cliente'){

    $sql = "SELECT * FROM Listas WHERE id_usuario = ?";
    $stmt = $conn ->prepare($sql);  // Prepara la consulta
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);  // Vincula el parámetro
    $stmt->execute();  // Ejecuta la consulta

    // Obtener los resultados con PDO
    $listas = $stmt->fetchAll(PDO::FETCH_ASSOC);


}



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
                <form class="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>

           <li><a href="Admin/RevisionProd.php"><i class="bi bi-chat-dots"></i> Revision Productos</a></li>

            <li><a href="Perfil.php" class="profile-link">
                    <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img-navbar">
                </a></li>
            <li><a href="#" onclick="document.getElementById('logoutModal').style.display='block'"><i
                        class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>



    <div class="profile-container">

        <!-- Foto de perfil y nombre de usuario -->
        <div class="profile-header">
            

            <?php
            // Convertir la imagen en base64
    $imagenBlob = $user['foto']; // Esto es el BLOB que viene de la base de datos
    $imagenBase64 = base64_encode($imagenBlob);

    // Puedes ajustar el tipo MIME si no es jpg
    echo '<img src="data:image/jpeg;base64,' . $imagenBase64 . '" alt="Foto de perfil" class="profile-img">';
    
          
                    echo '<h2 id="username">'.$user['nombreUsu'].   '</h2>'.'<br>';
                    echo '<h2 id="username">'.$user['rol'].   '</h2>';
               
          
            ?>

            <button id="edit_btn">Editar</button>
            <form method="POST" onsubmit="return confirmarBaja();" action="PHP/eliminar_perfil.php">
            
            <button type="submit" name="baja_usuario">Desactivar Usuario</button>
            </form>


        </div>

        <div id="editPopup" class="popup">
            <div class="popup-content" id="popup">
                <span class="close-btn" id="closeBtn">&times;</span>
                <h3>Editar Información</h3>
                <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
                    <!-- Campo para el nombre de usuario -->
                    <div class="form-group">
                        <label for="nombreUsu">Nombre de Usuario:</label>
                        <input type="text" id="nombreUsu" name="nombreUsu"
                            value="<?php echo htmlspecialchars($user['nombreUsu']); ?>" required><br>

                        <!-- Campo correo -->
                        <label for="correo">Correo:</label>
                        <input type="text" id="correo" name="correo"
                            value="<?php echo htmlspecialchars($user['correo']); ?>" required><br>

                        <!-- Campo nombres -->
                        <label for="nombres">Nombres:</label>
                        <input type="text" id="nombres" name="nombres"
                            value="<?php echo htmlspecialchars($user['nombres']); ?>" required><br>

                        <!-- Campo apellido paterno -->
                        <label for="apePa">Apellido Paterno:</label>
                        <input type="text" id="apePa" name="apePa"
                            value="<?php echo htmlspecialchars($user['apePa']); ?>" required><br>

                        <!-- Campo apellido materno -->
                        <label for="apeMa">Apellido Materno:</label>
                        <input type="text" id="apeMa" name="apeMa"
                            value="<?php echo htmlspecialchars($user['apeMa']); ?>" required><br>
                    </div>
                    <div class="form-group2">
                        <!-- Campo fecha de nacimiento -->
                        <label for="fechaNacim">Fecha Nacimiento:
                            <?php echo htmlspecialchars($user['fechaNacim']); ?></label>
                        <input type="date" id="fechaNacim" name="fechaNacim"
                            value="<?php echo htmlspecialchars($user['fechaNacim']); ?>" required><br>

                        <!-- Campo sexo -->
                        <label for="sexo">Sexo: <?php echo htmlspecialchars($user['sexo']); ?></label>
                        <select class="input-field" id="sexo" name="sexo">
                            <option value="" disabled selected>Sexo</option>
                            <option value="masculino">Masculino</option>
                            <option value="femenino">Femenino</option>
                        </select>


                        <!-- Campo privacidad -->
                        <label for="privacidad">Privacidad: <?php echo htmlspecialchars($user['privacidad']); ?></label>
                        <select class="input-field" id="privacidad" name="privacidad">
                            <option value="" disabled selected>Visibilidad</option>
                            <option value="publico">Público</option>
                            <option value="privado">Privado</option>
                        </select>

                        <!-- Campo foto de perfil -->
                        <div class="input-box-Image">
                              <!-- Imagen actual del usuario (preview) -->
                            <?php
                            echo '<img id="preview" src="data:image/jpeg;base64,' . $imagenBase64 . '" alt="Foto de perfil" class="profile-img">';
                            ?>

                            <!-- Botón personalizado para subir -->
                            <label for="imageUpload" class="custom-file-upload">
                            <i class="bx bx-upload"></i> Subir Imagen
                            </label>

                             <!-- Input oculto que responde al label -->
                            <input type="file" id="imageUpload" name="imageUpload" accept="image/*" style="display: none;">
    
                            <!-- Nombre del archivo -->
                            <span id="file-name">Ningún archivo seleccionado</span>
  
                        </div>



                        <!-- Campo contrasena -->
                        <label for="contrasena">Contraseña:</label>
                        <input type="text" id="contrasena" name="contrasena" value=""><br>


                        <!-- Campo oculto con la contraseña actual -->
                        <input type="hidden" id="contrasena_actual" name="contrasena_actual"
                            value="<?php echo htmlspecialchars($user['contrasena']); ?>">

                        <input type="hidden" id="rol_actual" name="rol_actual"
                            value="<?php echo htmlspecialchars($user['rol']); ?>">

                        <input type="hidden" id="sexo_actual" name="sexo_actual"
                            value="<?php echo htmlspecialchars($user['sexo']); ?>">

                        <input type="hidden" id="privacidad_actual" name="privacidad_actual"
                            value="<?php echo htmlspecialchars($user['privacidad']); ?>">



                        <input type="submit" value="Guardar Cambios">
                    </div>
                </form>

            </div>
        </div>



    </div>

    <!-- Mensaje de perfil privado -->
    <div id="privateMessage" class="profile-message <?php echo $privacidad_usuario === 'privado' ? '' : 'hidden'; ?>">
        <p>Este perfil es privado.</p>
    </div>

    <!-- Perfil público con listas de deseos -->
    <div id="publicProfile" class="profile-content <?php echo $rol_usuario === 'cliente' ? '' : 'hidden'; ?>">
        <h3>Listas de deseados</h3>
        <div class="similar-products" onclick="window.location.href='ListaDeseos.html'">
            <?php
                if (count($listas) > 0) {
                    // Mostrar cada producto
                    foreach ($listas as $lista) {
                        echo '<div class="similar-item">';
                        echo '<h3>' . htmlspecialchars($lista['nombre']) . '</h3>';
                        echo '<p>' . htmlspecialchars($lista['descripcion']) . '</p>';
                        echo '<p>' . htmlspecialchars($lista['privacidad']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No has creado ninguna lista.</p>';
                }
                ?>

        </div>
    </div>

    <!-- Perfil de vendedor -->
    <div id="sellerProfile" class="profile-content <?php echo $rol_usuario === 'vendedor' ? '' : 'hidden'; ?>">
        <h3>Productos Publicados</h3>
        <div class="similar-products" onclick="window.location.href='Producto.html'">
            <?php
                if (count($products) > 0) {
                    // Mostrar cada producto
                    foreach ($products as $product) {
                        echo '<div class="similar-item">';
                        echo '<p>' . htmlspecialchars($product['nombre']) . '</p>';
                        echo '<span>$' . number_format($product['precio'], 2) . '</span>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No has publicado ningún producto.</p>';
                }
            ?>
        </div>
    </div>


    <!-- Perfil de administrador -->
    <div id="adminProfile" class="profile-content <?php echo $rol_usuario === 'administrador' ? '' : 'hidden'; ?>">
        <h3>Productos Autorizados</h3>
        <div class="similar-products">
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg"
                    alt="Producto Similar 1">
                <p>Producto 1</p>
                <span>$249.99</span>
            </div>
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg"
                    alt="Producto Similar 2">
                <p>Producto 2</p>
                <span>$199.99</span>
            </div>
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg"
                    alt="Producto Similar 3">
                <p>Producto 3</p>
                <span>$279.99</span>
            </div>
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg"
                    alt="Producto Similar 1">
                <p>Producto 4</p>
                <span>$249.99</span>
            </div>
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg"
                    alt="Producto Similar 2">
                <p>Producto 5</p>
                <span>$199.99</span>
            </div>
            <div class="similar-item" onclick="window.location.href='Producto.html'">
                <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg"
                    alt="Producto Similar 3">
                <p>Producto 6</p>
                <span>$279.99</span>
            </div>

        </div>
    </div>
    </div>

    <div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('logoutModal').style.display='none'">&times;</span>
        <h2>¿Deseas cerrar sesión?</h2>
        <div class="modal-actions">
            <button class="btn-modal confirm" onclick="window.location.href='../Hermes/PHP/Logout.php'">Sí, cerrar sesión</button>
            <button class="btn-modal cancel" onclick="document.getElementById('logoutModal').style.display='none'">Cancelar</button>
        </div>
    </div>

    <script>
    document.getElementById('edit_btn').addEventListener('click', function() {
        document.getElementById('editPopup').style.display = 'block';
    });

    document.getElementById('closeBtn').addEventListener('click', function() {
        document.getElementById('editPopup').style.display = 'none';
    });

    
document.getElementById('imageUpload').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    const fileName = document.getElementById('file-name');

    if (file) {
        fileName.textContent = file.name;

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result; // Actualiza la imagen
        };
        reader.readAsDataURL(file); // Convierte a base64
    } else {
        fileName.textContent = "Ningún archivo seleccionado";

        
    }
});

function confirmarBaja() {
    return confirm("¿Estás seguro de que deseas desactivar este usuario?");
}





    </script>
  

</body>


</html>