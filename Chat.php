<?php
require './config.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: Index.php");
  exit();
}

$id_usuario = $_SESSION['usuario'];

$sql = "SELECT DISTINCT u.id, u.nombreUsu, u.foto 
        FROM Usuarios u
        JOIN Mensajes cp ON (u.id = cp.id_receptor OR u.id = cp.id_emisor)
        WHERE (cp.id_receptor = ? OR cp.id_emisor = ?) 
        AND u.id != ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $id_usuario, $id_usuario]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // obtiene todos los resultados como arreglo asociativo
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat Privado</title>
  <link rel="stylesheet" href="CSS/Chats.css">
  <link rel="stylesheet" href="CSS/Navbar.css">
  <link rel="stylesheet" href="CSS/Fondo.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <!-- Barra de navegación -->
  <nav class="navbar">
    <ul class="navbar-menu">
      <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>
      <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
      <li><a href="Pedidos.html"><i class="bi bi-list"></i> Pedidos</a></li>
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

  <div class="usuarios-container">
    <div class="user-list">
      <div class="user-header">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
          <path fill="#DA961F"
            d="M20.95 4H5.05C4.473 4 4 4.476 4 5.059v12.536c0 .583.473 1.06 1.05 1.06h5.178v3.08c0 .212.23.34.41.223l5.134-3.304h5.178c.577 0 1.05-.476 1.05-1.059V5.06C22 4.476 21.527 4 20.95 4M8.918 14C8.416 14 8 13.563 8 13.011v-.022c0-.552.416-.989.918-.989h8.154c.512 0 .928.448.928 1s-.416 1-.928 1zm0-4C8.416 10 8 9.563 8 9.011V8.99c0-.553.416-.99.918-.99h8.154c.512 0 .928.448.928 1s-.416 1-.928 1z" />
        </svg>
        <span class="user">Chats</span>
      </div>
      <ul id="lista-usuarios">
        <!-- Aquí se insertarán los usuarios dinámicamente -->
        <?php
        if (count($result) > 0) {
          foreach ($result as $row) {
            $nombre_usuario = htmlspecialchars($row['nombreUsu'], ENT_QUOTES, 'UTF-8');

            $foto = $row['foto'];
            $id_otro_usuario = $row['id'];

            // Consulta para obtener el ID del chat
            $sql_chat = "SELECT id_chat FROM Chat_Privado 
                     WHERE (id_remitente = ? AND id_emisor = ?)
                     OR (id_remitente = ? AND id_emisor = ?) LIMIT 1";

            $stmt_chat = $conn->prepare($sql_chat);
            $stmt_chat->execute([$id_usuario, $id_otro_usuario, $id_otro_usuario, $id_usuario]);
            $chat_data = $stmt_chat->fetch(PDO::FETCH_ASSOC);
            $id_chat = $chat_data ? $chat_data['id_chat'] : '0';
            $stmt_chat = null; // Liberar
        
            // Imagen de perfil base64
            $imagen_perfil = $foto ?
              'data:image/jpeg;base64,' . base64_encode($foto) :
              'imagenes/USER.png';

            echo '<li class="nav-item">';
            echo '<a class="nav-link usuario-chat" href="#" data-nombre="' . $nombre_usuario . '" 
      data-foto="' . $imagen_perfil . '" data-id-chat="' . $id_chat . '">';
            echo '<img src="' . $imagen_perfil . '" class="profile-pic"> ' . $nombre_usuario;
            echo '</a>';
            echo '</li>';

          }
        } else {
          echo '<li class="nav-item"><span>No tienes chats activos</span></li>';
        }
        $stmt = null;
        $conn = null;
        ?>

      </ul>
    </div>
  </div>
  <div class="chat-container">
    <div class="chat-header">
      <img src="https://i.pinimg.com/736x/dc/6c/b0/dc6cb0521d182f959da46aaee82e742f.jpg" alt="foto de perfil"
        class="profile-pic">
      <div class="user-info">
        <span class="user">Juan</span>
        <p class="Estado" name="Estado">En linea</p>
      </div>
    </div>

    <div class="chat-box">
      <div class="message received">

        <p>Hola, ¿cómo estás?</p>
      </div>

    </div>

    <div class="chat-footer">
      <input type="text" placeholder="Escribe un mensaje..." id="message-input">
      <button class="send-btn" id="send-btn">Enviar</button>
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

    <script src="JS/mensajes.js"></script>


</body>

</html>