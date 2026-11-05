<?php

$busqueda = $_GET['busqueda'] ?? '';
$limite = $busqueda === '' ? ' LIMIT 5' : '';

// Busca otros usuarios por username o nombre y calcula el estado de su relación con el usuario conectado.
$consulta = $conn->prepare(
    "SELECT u.id_usuario, u.username,
        (SELECT CASE
             WHEN a.estado = 'pendiente' AND a.solicitante_id = actual.id_usuario THEN 'enviada'
             WHEN a.estado = 'pendiente' THEN 'recibida' ELSE a.estado
         END
         FROM amigos a
         WHERE (a.usuario_id_1 = actual.id_usuario AND a.usuario_id_2 = u.id_usuario)
            OR (a.usuario_id_2 = actual.id_usuario AND a.usuario_id_1 = u.id_usuario)
         LIMIT 1) AS estado_relacion
     FROM usuarios u
     JOIN usuarios actual ON actual.username = :actual
     WHERE u.id_usuario <> actual.id_usuario
       AND (:sin_busqueda = 1 OR LOCATE(:username, u.username) > 0 OR LOCATE(:nombre, u.nombre_real) > 0)" . $limite
);
$consulta->execute([
    'actual' => $_SESSION['username'],
    'sin_busqueda' => !isset($_GET['buscar']) || $busqueda === '',
    'username' => $busqueda,
    'nombre' => $busqueda,
]);

$personas = $consulta->fetchAll(PDO::FETCH_ASSOC);

?>
