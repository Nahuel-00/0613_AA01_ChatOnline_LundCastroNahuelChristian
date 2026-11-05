<?php

// Busca las solicitudes pendientes recibidas por el usuario conectado y quién las envió.
$consulta = $conn->prepare(
    "SELECT amigos.id_amistad, usuarios.username
     FROM amigos
     JOIN usuarios ON amigos.solicitante_id = usuarios.id_usuario
     JOIN usuarios AS destinatario ON amigos.usuario_id_2 = destinatario.id_usuario
     WHERE destinatario.username = :username AND amigos.estado = 'pendiente'"
);
$consulta->execute(['username' => $_SESSION['username']]);
$solicitudes = $consulta->fetchAll(PDO::FETCH_ASSOC);
