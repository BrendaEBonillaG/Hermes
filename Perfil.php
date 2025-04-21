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
    $sql = "SELECT * FROM productos WHERE id_vendedor = ?";
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
            <li><a href="Dashboard.html"><i class="bi bi-house-door"></i> Inicio</a></li>
            <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
            <li><a href="#"><i class="bi bi-list"></i> Pedidos</a></li>
            <li><a href="Chat.html"><i class="bi bi-chat-dots"></i> Chats</a></li>
            <li>
                <form class="search-form">
                    <input type="text" placeholder="Buscar productos..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </li>
            <li><a href="Perfil.html" class="profile-link">
                <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img-navbar"> 
            </a></li>
            <li><a href="#"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
    </nav>

    

    <div class="profile-container">
    
        <!-- Foto de perfil y nombre de usuario -->
        <div class="profile-header">
            <img src="img/perfil.jpg" alt="Foto de perfil" class="profile-img">
        
                <?php
          
                    echo '<h2 id="username">'.$user['nombreUsu'].   '</h2>'.'<br>';
                    echo '<h2 id="username">'.$user['rol'].   '</h2>';
               
          
                ?>
            
            <button id="edit_btn">Editar</button>


    </div> 
    <div id="editPopup" class="popup">
    <div class="popup-content">
        <span class="close-btn" id="closeBtn">&times;</span>
        <h3>Editar Información</h3>
        <form action="editar_perfil.php" method="POST">
            <!-- Campo para el nombre de usuario -->
        <label for="nombreUsu">Nombre de Usuario:</label>
        <input type="text" id="nombreUsu" name="nombreUsu" value="<?php echo htmlspecialchars($user['nombreUsu']); ?>" required><br>

        <!-- Campo correo -->
        <label for="correo">Correo:</label>
        <input type="text" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required><br>

        <!-- Campo nombres -->
        <label for="nombres">Nombres:</label>
        <input type="text" id="nombres" name="nombres" value="<?php echo htmlspecialchars($user['nombres']); ?>" required><br>

        <!-- Campo apellido paterno -->
        <label for="apePa">Apellido Paterno:</label>
        <input type="text" id="apePa" name="apePa" value="<?php echo htmlspecialchars($user['apePa']); ?>" required><br>

        <!-- Campo apellido materno -->
        <label for="apeMa">Apellido Materno:</label>
        <input type="text" id="apeMa" name="apeMa" value="<?php echo htmlspecialchars($user['apeMa']); ?>" required><br>

        <!-- Campo fecha de nacimiento -->
        <label for="fechaNacim">Fecha Nacimiento: <?php echo htmlspecialchars($user['fechaNacim']); ?></label>
        <input type="date" id="fechaNacim" name="fechaNacim" value="<?php echo htmlspecialchars($user['fechaNacim']); ?>" required><br>

        <!-- Campo sexo -->
        <label for="sexo">Sexo: <?php echo htmlspecialchars($user['sexo']); ?></label>
        <select class="input-field" id="sexo" name="sexo" >
                                <option value="" disabled selected>Sexo</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
        </select>
        

        <!-- Campo privacidad -->
        <label for="privacidad">Privacidad: <?php echo htmlspecialchars($user['privacidad']); ?></label>
        <select class="input-field" id="privacidad" name="privacidad" >
                                <option value="" disabled selected>Visibilidad</option>
                                <option value="publico">Público</option>
                                <option value="privado">Privado</option>
        </select>

      
        <!-- Campo contrasena -->
        <label for="contrasena">Contraseña:</label>
        <input type="text" id="contrasena" name="contrasena" value=""><br>


        <!-- Campo oculto con la contraseña actual -->
        <input type="hidden" id="contrasena_actual" name="contrasena_actual" value="<?php echo htmlspecialchars($user['contrasena']); ?>">

        <input type="hidden" id="rol_actual" name="rol_actual" value="<?php echo htmlspecialchars($user['rol']); ?>">

        <input type="hidden" id="sexo_actual" name="sexo_actual" value="<?php echo htmlspecialchars($user['sexo']); ?>">

        <input type="hidden" id="privacidad_actual" name="privacidad_actual" value="<?php echo htmlspecialchars($user['privacidad']); ?>">



        <input type="submit" value="Guardar Cambios">

        
    </div>
</div>


            
        </div>

        <!-- Mensaje de perfil privado -->
        <div id="privateMessage" class="profile-message hidden">
            <p>Este perfil es privado.</p>
        </div>

        <!-- Perfil público con listas de deseos -->
        <div id="publicProfile" class="profile-content <?php echo $rol_usuario === 'cliente' ? '' : 'hidden'; ?>">
            <h3>Listas de deseados</h3>
            <div class="similar-products">
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
            <div class="similar-products">
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
        <div id="adminProfile" class="profile-content <?php echo $rol_usuario === 'cliente' ? '' : 'hidden'; ?>">
            <h3>Productos Autorizados</h3>
            <div class="similar-products">
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg" alt="Producto Similar 1">
                    <p>Producto 1</p>
                    <span>$249.99</span>
                </div>
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg" alt="Producto Similar 2">
                    <p>Producto 2</p>
                    <span>$199.99</span>
                </div>
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg" alt="Producto Similar 3">
                    <p>Producto 3</p>
                    <span>$279.99</span>
                </div>
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/45/ee/02/45ee022351d5b3fe23246925a7732aa8.jpg" alt="Producto Similar 1">
                    <p>Producto 4</p>
                    <span>$249.99</span>
                </div>
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/ab/ee/ab/abeeab1b1d93e5b6a5b862f0b964853f.jpg" alt="Producto Similar 2">
                    <p>Producto 5</p>
                    <span>$199.99</span>
                </div>
                <div class="similar-item">
                    <img src="https://i.pinimg.com/474x/ec/65/fd/ec65fd4224a69cc1e546910aa00b61d2.jpg" alt="Producto Similar 3">
                    <p>Producto 6</p>
                    <span>$279.99</span>
                </div>
                
            </div>
        </div>
    </div>

    <script src="JS/profile.js"></script>
</body>
</html>
