<?php

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../Login/Login.php');
    exit;
}

include '../base de datos/conexion.php';

// Comprueba que el usuario conectado está en la tabla de administradores.
$consulta = $conn->prepare(
    'SELECT usuarios.id_usuario
     FROM usuarios
     JOIN ADMINISTRADORES ON ADMINISTRADORES.usuario_id = usuarios.id_usuario
     WHERE usuarios.username = :username'
);
$consulta->execute(['username' => $_SESSION['username']]);
$idAdmin = $consulta->fetchColumn();

if (!$idAdmin) {
    exit('No tienes permiso para acceder a esta área.'); 
}

$mensaje = '';


if (isset($_POST['guardar'])) {
    try {
        if ($_POST['password'] === '') {
            // Si no se escribió una contraseña nueva, solo cambia el username y el nombre.
            $consulta = $conn->prepare(
                'UPDATE usuarios
                 SET username = :username, nombre_real = :nombre
                 WHERE id_usuario = :id'
            );
            $consulta->execute([
                'username' => $_POST['username'],
                'nombre' => $_POST['nombre_real'],
                'id' => $_POST['id_usuario'],
            ]);
        } else {
            // Si se escribió una contraseña, guarda su hash BCRYPT junto con los otros cambios.
            $consulta = $conn->prepare(
                'UPDATE usuarios
                 SET username = :username, nombre_real = :nombre, password_hash = :password
                 WHERE id_usuario = :id'
            );
            $consulta->execute([
                'username' => $_POST['username'],
                'nombre' => $_POST['nombre_real'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'id' => $_POST['id_usuario'],
            ]);
        }

        $mensaje = 'Guardado con éxito.';
    } catch (PDOException $error) {
        // Por ejemplo, puede fallar si el username ya pertenece a otro usuario.
        $mensaje = 'No se pudo guardar.';
    }
}

// Elimina al usuario seleccionado y los registros que dependen de su cuenta.
if (isset($_POST['eliminar'])) {
    try {
        $idUsuario = $_POST['id_usuario'];

        // Borra sus solicitudes de amistad y amistades aceptadas.
        $consulta = $conn->prepare(
            'DELETE FROM amigos WHERE usuario_id_1 = :id1 OR usuario_id_2 = :id2'
        );
        $consulta->execute(['id1' => $idUsuario, 'id2' => $idUsuario]);

        $consulta = $conn->prepare('DELETE FROM usuarios WHERE id_usuario = :id');
        $consulta->execute(['id' => $idUsuario]);
        $consulta->execute(['id' => $idUsuario]);

        // Si el administrador se eliminó a sí mismo, cierra su sesión.
        if ((int) $idUsuario === (int) $idAdmin) {
            header('Location: ../Sesiones/cerrar_sesiones.php');
            exit;
        }
        $mensaje = 'Usuario eliminado con éxito.';
    } catch (PDOException $error) {
        $mensaje = 'No se pudo eliminar.';
    }
}

// Carga los usuarios y los datos que se pueden editar en la página.
$usuarios = $conn->query(
    'SELECT id_usuario, username, nombre_real, correo FROM usuarios'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar usuarios</title>
</head>

<body>
    <h1>Administrar usuarios</h1>
    <a href="../index.php">Volver al inicio</a>
    <p><?= $mensaje ?></p>

    <!-- Muestra un formulario por cada usuario guardado en la base de datos. -->
    <?php foreach ($usuarios as $usuario) { ?>
        <h2><?= $usuario['username'] ?></h2>
        <p><?= $usuario['correo'] ?></p>
        

        <!-- Los campos permiten cambiar username, nombre y contraseña -->
        <form method="post">
            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
            <label>Username: <input name="username" value="<?= $usuario['username'] ?>"></label>
            <label>Nombre: <input name="nombre_real" value="<?= $usuario['nombre_real'] ?>"></label>
            <label>Nueva contraseña:<a href="mailto:"></a><input type="password" name="password"></label>
            <button type="submit" name="guardar">Guardar</button>
            <button type="submit" name="eliminar">Eliminar usuario</button>
        </form>
    <?php } ?>
</body>
</html>


$idAdmin = $consulta->fetchColumn();