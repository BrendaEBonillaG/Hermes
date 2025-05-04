<?php
// Iniciar sesión para acceder al usuario actual
session_start();

require '../config.php'; 

$usuario_id = $_SESSION['usuario']['id']; 



// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar a la base de datos
   
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE Usuarios SET estado = 0 WHERE id = ?");
        $stmt->execute([$usuario_id]);
    
        echo "<script>alert('Usuario desactivado correctamente');</script>";
        header('Location: index.php');  
        exit;
}
?>


        
