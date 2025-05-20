<?php
session_start();
require '../Hermes/config.php';

if (isset($_COOKIE['rememberMe'])) {
    list($id, $nombreUsu) = explode(':', base64_decode($_COOKIE['rememberMe']));

    // Buscar el usuario en la base de datos
    $stmt = $conn ->prepare("SELECT id, nombreUsu, rol FROM Usuarios WHERE id = ? AND nombreUsu = ?");
    $stmt->execute([$id, $nombreUsu]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Iniciar sesión automáticamente
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombreUsu' => $usuario['nombreUsu'],
            'rol' => $usuario['rol']
        ];

        // Redirigir al dashboard
        header("Location: ../Dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hermes</title>
    <!-- BOXICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- STYLE -->
    <link rel="stylesheet" href="CSS/styleM.css">
    <link rel="stylesheet" href="CSS/Fondo.css">
</head>

<body>
    <section>
        <div class="Wave wave1"></div>
        <div class="Wave wave2"></div>
        <div class="Wave wave3"></div>
        <div class="Wave wave4"></div>
    </section>
    <div class="form-container">
        <div class="col col-1">
            <div class="image-layer">
                <img src="img/HERMES-W.png" class="form-image-main">
                <!-- <img src="assets/img/dots.png" class="form-image dots">
                <img src="assets/img/coin.png" class="form-image coin">
                <img src="assets/img/spring.png" class="form-image spring">
                <img src="assets/img/rocket.png" class="form-image rocket">
                <img src="assets/img/cloud.png" class="form-image cloud">
                <img src="assets/img/stars.png" class="form-image stars"> -->
            </div>
            <p class="featured-words"> <span></span> </p>
        </div>
        <div class="col col-2">
            <div class="btn-box">
                <button class="btn btn-1" id="login">Sign In</button>
                <button class="btn btn-2" id="register">Sign Up</button>
            </div>

            <!-- Login Form Container -->
            <div class="login-form">
                <div class="form-title">
                    <span>Iniciar sesión</span>
                </div>
                <form id="loginForm" class="form-inputs" action="PHP/Login.php" method="POST">
                    <div class="input-box">
                        <input type="text" class="input-field" name="nombreUsu" placeholder="Nombre de Usuario" required>
                        <i class="bx bx-user icon"></i>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="contrasena" placeholder="Contraseña" required>
                        <i class="bx bx-lock-alt icon"></i>
                    </div>
                    <div class="remember-forgot">
                        <label for="rememberMe" class="checkbox-label">
                            <input type="checkbox" id="rememberMe" name="rememberMe"> Recordar contraseña
                        </label>
                    </div>
                   
                    <div class="input-box">
                        <button type="submit" class="input-submit">
                            <span>Iniciar sesión</span>
                            <i class="bx bx-right-arrow-alt"></i>
                        </button>
                    </div>
                </form>
            </div>

           <!-- Register Form Container -->
           <div class="register-form">
                <div class="form-title">
                    <span>Crear cuenta</span>
                </div>
                <form class="form-inputs" id="registerForm" novalidate>

                    <div class="column1">
                        <!-- Correo Electrónico (único) -->
                        <div class="input-box">
                            <input type="email" class="input-field" id="correo" placeholder="Correo Electrónico" required>
                            <i class="bx bx-envelope icon"></i>
                        </div>
                        

                        <!-- Nombre de Usuario (mínimo 3 caracteres) -->
                        <div class="input-box">
                            <input type="text" class="input-field" id="nombreUsu" placeholder="Nombre de Usuario"
                                required minlength="3">
                            <i class="bx bx-user icon"></i>
                        </div>

                        <!-- Contraseña (mínimo 8 caracteres, 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial) -->
                        <div class="input-box">
                            <input type="password" class="input-field" id="contrasena" placeholder="Contraseña" required
                                minlength="8">
                            <i class="bx bx-lock-alt icon"></i>
                        </div>

                        <!-- Rol de Usuario -->
                        <div class="input-box">
                            <select class="input-field" id="rol" required>
                                <option value="" disabled selected>Rol de Usuario</option>
                                <option value="vendedor">Vendedor</option>
                                <option value="cliente">Cliente</option>
                            </select>
                        </div>
                    </div>

                    <div class="column1">
                        <!-- Imagen de Perfil (Avatar) -->
                        <div class="input-box-Image">
                            <label for="imageUpload" class="custom-file-upload">
                                <i class="bx bx-upload"></i> Subir Imagen
                            </label>
                            <input type="file" id="imageUpload" accept="image/*">
                            <span id="file-name">Ningún archivo seleccionado</span>
                        </div>

                        <!-- Nombre Completo -->
                        <div class="input-box">
                            <input type="text" class="input-field" id="nombres" placeholder="Nombres" required>
                            <i class="bx bx-user-circle icon"></i>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" id="apePa" placeholder="Apellido paterno" required>
                            <i class="bx bx-user-circle icon"></i>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" id="apeMa" placeholder="Apellido materno" required>
                            <i class="bx bx-user-circle icon"></i>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="input-box">
                            <input type="date" class="input-field" id="fechaNacim" required>
                            <i class="bx bx-calendar icon"></i>
                        </div>

                        <!-- Sexo -->
                        <div class="input-box">
                            <select class="input-field" id="sexo" required>
                                <option value="" disabled selected>Sexo</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>

                        <!-- Visibilidad -->
                        <div class="input-box">
                            <select class="input-field" id="privacidad" required>
                                <option value="" disabled selected>Visibilidad</option>
                                <option value="publico">Público</option>
                                <option value="privado">Privado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <div class="input-box">
                        <button type="submit" class="input-submit">
                            <span>Registrarse</span>
                            <i class="bx bx-right-arrow-alt"></i>
                        </button>
                    </div>
                </form>
            </div>


        </div>
        <script src="../Hermes/JS/main.js"></script>
       
</body>

</html>