<?php

// Gestiona solicitudes y amistades del usuario actual.
if (isset($_POST['enviar_solicitud']) || isset($_POST['aceptar_solicitud']) || isset($_POST['rechazar_solicitud']) || isset($_POST['eliminar_amigo'])) {
    // Obtiene el ID del usuario conectado a partir de su username.
    $consulta = $conn->prepare('SELECT id_usuario FROM usuarios WHERE username = :username');
    $consulta->execute(['username' => $_SESSION['username']]);
    $idUsuario = $consulta->fetchColumn();

    // Crea una solicitud pendiente si el destinatario existe, no es el usuario actual y no hay relación previa.
    if (isset($_POST['enviar_solicitud'])) {
        $consulta = $conn->prepare(
            "INSERT INTO amigos (usuario_id_1, usuario_id_2, solicitante_id)
             SELECT :solicitante, id_usuario, :solicitante_dos
             FROM usuarios
             WHERE id_usuario = :destinatario AND id_usuario <> :solicitante_tres
             AND NOT EXISTS (
                 SELECT 1 FROM amigos
                 WHERE (usuario_id_1 = :solicitante_cuatro AND usuario_id_2 = :destinatario_dos)
                    OR (usuario_id_1 = :destinatario_tres AND usuario_id_2 = :solicitante_cinco)
             )"
        );
        $consulta->execute([
            'solicitante' => $idUsuario,
            'solicitante_dos' => $idUsuario,
            'destinatario' => (int) $_POST['id_usuario'],
            'solicitante_tres' => $idUsuario,
            'solicitante_cuatro' => $idUsuario,
            'destinatario_dos' => (int) $_POST['id_usuario'],
            'destinatario_tres' => (int) $_POST['id_usuario'],
            'solicitante_cinco' => $idUsuario,
        ]);
    // Acepta la solicitud pendiente indicada, solo si el usuario conectado es su destinatario.
    } elseif (isset($_POST['aceptar_solicitud'])) {
        $consulta = $conn->prepare(
            "UPDATE amigos SET estado = 'aceptado'
             WHERE id_amistad = :id AND usuario_id_2 = :destinatario AND estado = 'pendiente'"
        );
        $consulta->execute([
            'id' => (int) $_POST['id_amistad'],
            'destinatario' => $idUsuario,
        ]);
    // Elimina la solicitud pendiente indicada, solo si el usuario conectado es su destinatario.
    } elseif (isset($_POST['rechazar_solicitud'])) {
        $consulta = $conn->prepare(
            "DELETE FROM amigos
             WHERE id_amistad = :id AND usuario_id_2 = :destinatario AND estado = 'pendiente'"
        );
        $consulta->execute([
            'id' => (int) $_POST['id_amistad'],
            'destinatario' => $idUsuario,
        ]);
    } else {
        // Elimina la amistad aceptada indicada, solo si pertenece al usuario conectado.
        $consulta = $conn->prepare("DELETE FROM amigos WHERE id_amistad = :id AND estado = 'aceptado' AND (usuario_id_1 = :usuario_uno OR usuario_id_2 = :usuario_dos)");
        $consulta->execute(['id' => (int) $_POST['id_amistad'], 'usuario_uno' => $idUsuario, 'usuario_dos' => $idUsuario]);
    }

    // Recarga el index para actualizar las listas.
    header('Location: index.php');
    exit;
}
