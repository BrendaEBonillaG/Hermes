<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT DISTINCT u.id_usuario, u.nombre_usuario, u.foto 
        FROM Usuarios u
        JOIN Chat_Privado cp ON (u.id_usuario = cp.id_remitente OR u.id_usuario = cp.id_emisor)
        WHERE (cp.id_remitente = ? OR cp.id_emisor = ?) 
        AND u.id_usuario != ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_usuario, $id_usuario, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TOGETHER</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/chat.css">

  <link rel="icon" type="image/png" href="imagenes/TOGETHER.png">


</head>
<body>
  
  <!-- NAVBAR ROSITA -->
<nav class="vertical-navbar">
  <div class="text-center">
    <a class="navbar-brand" href="chat.php"> <!-- AQUI MERO VA LA IMAGEN -->
      <img src="imagenes/TOGETHER.png"   class="navbar-logo img-fluid">
    </a>
  </div>
  <ul class="nav flex-column">
    <li class="nav-item">
           <a class="nav-link" href="chat.php" data-bs-toggle="tooltip" title="INICIO">
        <i class="bi bi-house-door"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="crear-grupo.php" data-bs-toggle="tooltip" title="CREAR GRUPO">
        <i class="bi bi-plus-circle"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="busqueda.php" data-bs-toggle="tooltip" title="BUSCAR">
        <i class="bi bi-search"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="equipo.php" data-bs-toggle="tooltip" title="EQUIPO">
        <i class="bi bi-people"></i>  
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="crear-equipo.php" data-bs-toggle="tooltip" title="CREAR EQUIPO">
        <i class="bi  bi-plus-square"></i>  
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="perfil.php" data-bs-toggle="tooltip" title="PERFIL">
        <i class="bi bi-person"></i>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" href="recompensas.php" data-bs-toggle="tooltip" title="RECOMPENSAS">
        <i class="bi bi-trophy"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="notificaciones.php" data-bs-toggle="tooltip" title="NOTIFICACIONES">
        <i class="bi bi-bell"></i>
      </a>
    </li>
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
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nombre_usuario = htmlspecialchars($row['nombre_usuario']);
            $foto = !empty($row['foto']) ? $row['foto'] : 'imagenes/USER.png';
            
            // Obtener el ID del chat entre estos dos usuarios
            $sql_chat = "SELECT id_chat FROM Chat_Privado 
                        WHERE (id_remitente = ? AND id_emisor = ?)
                        OR (id_remitente = ? AND id_emisor = ?)";
            
            $stmt_chat = $conn->prepare($sql_chat);
            $stmt_chat->bind_param("iiii", $id_usuario, $row['id_usuario'], $row['id_usuario'], $id_usuario);
            $stmt_chat->execute();
            $chat_result = $stmt_chat->get_result();
            $chat_data = $chat_result->fetch_assoc();
            $id_chat = $chat_data['id_chat'];
            $stmt_chat->close();
            
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
    $stmt->close();
    $conn->close();
    ?>
    </ul>
</nav>
<!-- CONTENEDOR DE CHAT -->
<div class="main-content">
<div class="chat-header">
<div class="user-info">
  <img src="imagenes/USER.png" class="user-avatar">
  <span class="user-name"></span>
</div>


  <!-- BOTONES DE ACCION -->
  <div class="chat-actions">
    <div class="chat-actions">
      <button class="btn btn-call" data-bs-toggle="tooltip" title="Llamada" onclick="location.href='llamada.php'">
        <i class="bi bi-telephone"></i>
      </button>
      <button class="btn btn-video-call" data-bs-toggle="tooltip" title="Videollamada" onclick="location.href='videollamada.php'">
        <i class="bi bi-camera-video"></i>
      </button>
    </div>    
    <button class="btn btn-options" data-bs-toggle="tooltip" title="Opciones">
      <i class="bi bi-three-dots-vertical"></i> 
    </button>
    
  </div>
</div>

<!-- LISTA DE TAREAS -->
<div id="taskModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <ul id="taskList"></ul>
    <div class="task-input-container">
      <input type="text" id="taskInput" placeholder="Ingresa tarea">
      <select id="taskAssignee">
          <option value="Clark">Clark</option>
          <option value="Veck">Veck</option>
          <option value="Sol">Sol</option>
          <option value="Hermy">Hermy</option>
          <option value="brenda">brenda</option>
      </select>
      <button id="addTaskBtn">Añadir</button>
  </div>
  
  </div>
</div>


  <div class="chat-container">
      <!-- MENSAJES 
      <div class="chat-message incoming">
          <div class="message-content">
              <p>Hola, ¿cómo estás?</p>
              <span class="message-time">10:00 AM</span>
          </div>
      </div>
      <div class="chat-message outgoing">
          <div class="message-content">
              <p>¡Hola! Estoy bien, ¿y tú?</p>
              <span class="message-time">10:01 AM</span>
          </div>
      </div>-->
  </div>

<!-- BARRA PARA ESCRIBIR -->
<div class="chat-input">
    <input type="text" id="mensajeInput" class="form-control" placeholder="Escribe un mensaje...">
    <button id="btnEnviar" class="btn btn-primary">Enviar</button>
</div>

  <!-- SCRIPTS JS -->
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
  <!--<script src="js/chat.js"></script>-->
  <!--<script src="js/mensajes.js"></script>-->

  <script>
    // ES PARA LOS TOOLTIPS
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
  </script>


 <script src="js/cambiar-chat.js"></script>
 <script src=js/ChatFunc.js>
 </script>
</body>
</html>
