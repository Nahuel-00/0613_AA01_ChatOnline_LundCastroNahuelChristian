<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../Login/Login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ChatOnline</title>
    <link rel="stylesheet" href="../Estilos/style.css?v=4">
</head>
<body class="pagina-chat">
    <div class="mensaje-chat">
        <h1>Este es el chat</h1>
    </div>
</body>
</html>