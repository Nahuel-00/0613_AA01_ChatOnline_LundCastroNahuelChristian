<?php

// Busca las amistades aceptadas del usuario conectado y obtiene el username del otro usuario.
$consulta = $conn->prepare(
    "SELECT amigos.id_amistad,
     IF(amigos.usuario_id_1 = actual.id_usuario, destino.username, origen.username) AS username
     FROM amigos
     JOIN usuarios AS actual ON actual.username = :username
     JOIN usuarios AS origen ON amigos.usuario_id_1 = origen.id_usuario
     JOIN usuarios AS destino ON amigos.usuario_id_2 = destino.id_usuario
     WHERE (amigos.usuario_id_1 = actual.id_usuario OR amigos.usuario_id_2 = actual.id_usuario)
       AND amigos.estado = 'aceptado'"
);
$consulta->execute(['username' => $_SESSION['username']]);
$amigos = $consulta->fetchAll(PDO::FETCH_ASSOC);
