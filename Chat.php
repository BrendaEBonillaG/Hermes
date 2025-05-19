<?php
require './config.php';
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: Index.php");
  exit();
}

$id_usuario = $_SESSION['usuario']['id']; // Suponiendo que 'id' es la clave correcta


$sql = "SELECT DISTINCT u.id AS id_usuario, u.nombreUsu, u.foto 
        FROM Usuarios u
        JOIN Chat_Privado cp ON (u.id = cp.id_remitente OR u.id = cp.id_emisor)
        WHERE (cp.id_remitente = ? OR cp.id_emisor = ?) 
        AND u.id != ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $id_usuario, $id_usuario]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hermes</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="CSS/chat.css">
  <link rel="stylesheet" href="CSS/Fondo.css">
  <link rel="stylesheet" href="CSS/Navbar.css">

  <link rel="icon" type="image/png" href="imagenes/TOGETHER.png">


</head>

<body>

  <!-- NAVBAR  -->
  <nav class="navbar">
    <ul class="navbar-menu">
      <li><a href="Dashboard.php"><i class="bi bi-house-door"></i> Inicio</a></li>

      <?php if ($_SESSION['usuario']['rol'] === 'cliente'): ?>
        <li><a href="#"><i class="bi bi-cart"></i> Carrito de compras</a></li>
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

  <!-- NAVBAR BLANCO -->
  <nav class="vertical-navbar-white">
    <div class="w-100 p-3">
      <form class="search-form">
        <div class="input-group">
          <input type="text" class="form-control" aria-label="Buscar">
          <button class="btn btn-outline-secondary" type="button">Buscar</button>
        </div>
      </form>
    </div>
    <ul class="nav flex-column w-100">
      <?php
      if (count($result) > 0) {
        foreach ($result as $row) {

          $nombre_usuario = htmlspecialchars($row['nombreUsu']);

          $foto = !empty($row['foto']) ? $row['foto'] : 'imagenes/USER.png';

          // Obtener el ID del chat entre estos dos usuarios
          $sql_chat = "SELECT id_chat FROM Chat_Privado 
             WHERE (id_remitente = ? AND id_emisor = ?)
             OR (id_remitente = ? AND id_emisor = ?)";

          $stmt_chat = $conn->prepare($sql_chat);
          $stmt_chat->execute([$id_usuario, $row['id_usuario'], $row['id_usuario'], $id_usuario]);
          $chat_data = $stmt_chat->fetch(PDO::FETCH_ASSOC);

          $id_chat = $chat_data['id_chat'];


          $imagen_perfil = !empty($row['foto']) ?
            'data:image/jpeg;base64,' . base64_encode($row['foto']) :
            'imagenes/USER.png';

          echo '<li class="nav-item">';
          echo '<a class="nav-link usuario-chat" href="#" data-nombre="' . $nombre_usuario . '" 
                  data-foto="' . $imagen_perfil . '" data-id-chat="' . $id_chat . '">';
          echo '<img src="' . $imagen_perfil . '" class="chat-icon me-2"> ' . $nombre_usuario;
          echo '</a>';
          echo '</li>';
        }
      } else {
        echo '<li class="nav-item"><a class="nav-link" href="#">No tienes chats activos</a></li>';
      }

      ?>
    </ul>
  </nav>
  <!-- CONTENEDOR DE CHAT -->
  <div class="main-content">
    <div class="chat-header d-flex justify-content-between align-items-center">
      <div class="user-info d-flex align-items-center">
        <img src="imagenes/USER.png" class="user-avatar me-2">
        <span class="user-name"></span>
      </div>
      <?php if ($_SESSION['usuario']['rol'] === 'vendedor'): ?>
        <button id="btnOpcionesVendedor" class="btn btn-ticket btn-sm d-none">
          <i class="bi bi-ticket-detailed"></i> Crear cotización
        </button>
      <?php endif; ?>




    </div>


    <div class="chat-container">

    </div>

    <!-- BARRA PARA ESCRIBIR -->
    <div class="chat-input">
      <input type="text" id="mensajeInput" class="form-control" placeholder="Escribe un mensaje...">
      <button id="btnEnviar" class="btn btn-primary">Enviar</button>
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

    <!-- Modal Crear Cotización -->
<div id="modalCotizacion" class="modal-cotizacion" style="display:none;">
  <div class="modal-content-cotizacion">
    <span class="close-cotizacion" style="cursor:pointer;">&times;</span>
    <h2>Crear Cotización</h2>

    <form id="formCotizacion">
      <div id="listaProductos" class="product-list">
        <!-- Aquí se cargarán los productos con radio buttons -->
        <p>Cargando productos...</p>
      </div>

      <div style="margin-top: 15px;">
        <label>
          Cantidad:
          <input type="number" id="cantidadCotizacion" name="cantidad" disabled min="1" value="1" />
        </label>
      </div>

      <div style="margin-top: 15px;">
        <label>
          Precio:
          <input type="number" id="precioCotizacion" name="precio" disabled min="0" step="0.01" value="0" />
        </label>
      </div>

      <button type="submit" id="btnGuardarCotizacion" class="btn btn-ticket" disabled>Guardar Cotización</button>
    </form>
  </div>
</div>

    <!-- SCRIPTS JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>


    <script>
      // ES PARA LOS TOOLTIPS
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });

    </script>



    <script src="./JS/ChatFunc.js"></script>
    <script src="./JS/cambiar-chat.js"></script>
      <script src="./JS/btnCotizar.js"></script>
</body>

</html>