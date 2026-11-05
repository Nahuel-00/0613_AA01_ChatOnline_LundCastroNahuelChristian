<?php
session_start();
// Verificar si el usuario ha iniciado sesión antes de porder acceder al index
if (!isset($_SESSION['username'])) {
    header('Location: Login/Login.php');
    exit;
}
include 'base de datos/conexion.php';
include 'Cargar amistades/acciones.php';

// Area de administradores.
$consultaAdmin = $conn->prepare(
    'SELECT 1
     FROM ADMINISTRADORES
     JOIN usuarios ON usuarios.id_usuario = ADMINISTRADORES.usuario_id
     WHERE usuarios.username = :username'
);
$consultaAdmin->execute(['username' => $_SESSION['username']]);
$esAdministrador = $consultaAdmin->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChatOnline</title>
    <link rel="stylesheet" href="Estilos/style.css?v=3">
</head>
<body class="pagina-inicio">
    <header class="cabecera-inicio">
        <h1 class="titulo-principal">ChatOnline</h1>
        <div class="barra-usuario">
            <p>Usuario: <?= $_SESSION['username'] ?></p>
            <?php if ($esAdministrador) { ?>
                <a href="nueva_area/area.php">Administrar usuarios</a>
            <?php } ?>
            <a href="Sesiones/cerrar_sesiones.php" class="boton-cerrar-sesion">Cerrar sesión</a>
        </div>
    </header>

    <main class="contenido-inicio">
        <section class="panel panel-personas">
            <header class="encabezado-panel">
                <h2 class="titulo-seccion">Personas que quizá conozcas</h2>
                <p>Envía una solicitud de amistad</p>
            </header>

            <form class="formulario-busqueda" method="get">
                <input type="text" name="busqueda" placeholder="Buscar personas">
                <button class="boton" type="submit" name="buscar">Buscar</button>
            </form>

            <?php include 'Cargar amistades/personas.php'; ?>
            <div class="lista-personas">
                <?php
                // Muestra cada usuario y el estado de su relación con el usuario actual.
                foreach ($personas as $persona) {
                    if ($persona['estado_relacion'] !== 'aceptado') {
                ?>
                        <div class="tarjeta-persona">
                            <span class="persona-username"><?= $persona['username'] ?></span>
                            <form class="formulario-accion" method="post">
                                <input type="hidden" name="id_usuario" value="<?= $persona['id_usuario'] ?>">
                                <?php
                                if ($persona['estado_relacion'] === 'enviada') {
                                ?>
                                    <button class="boton boton-estado" type="button" disabled>Solicitud enviada</button>
                                <?php
                                } elseif ($persona['estado_relacion'] === 'recibida') {
                                ?>
                                    <button class="boton boton-estado" type="button" disabled>Solicitud recibida</button>
                                <?php
                                } else {
                                ?>
                                    <button class="boton" type="submit" name="enviar_solicitud">Enviar solicitud</button>
                                <?php } ?>
                            </form>
                        </div>
                <?php
                    }

                }
                ?>
            </div>
        </section>

        <section class="panel panel-solicitudes">
            <header class="encabezado-panel">
                <h2 class="titulo-seccion">Solicitudes recibidas</h2>
                <p>Acepta o rechaza las solicitudes de amistad</p>
            </header>

            <?php include 'Cargar amistades/solicitudes.php'; ?>
            <div class="lista-solicitudes">
                <?php
                // Muestra las solicitudes recibidas con opciones para aceptarlas o rechazarlas.
                foreach ($solicitudes as $solicitud) {
                ?>
                    <div class="tarjeta-solicitud">
                        <span class="solicitud-username"><?= $solicitud['username'] ?></span>
                        <form class="formulario-accion" method="post">
                            <input type="hidden" name="id_amistad" value="<?= $solicitud['id_amistad'] ?>">
                            <button class="boton" type="submit" name="aceptar_solicitud">Aceptar</button>
                            <button class="boton boton-secundario" type="submit" name="rechazar_solicitud">Rechazar</button>
                        </form>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>

        <section class="panel panel-amigos">
            <header class="encabezado-panel">
                <h2 class="titulo-seccion">Amigos</h2>
            </header>
            <?php include 'Cargar amistades/amigos.php'; ?>
            <!-- Muestra los amigos en filas de tres tarjetas. -->
            <div class="lista-amigos">
                <?php
                // Muestra los usuarios que ya son amigos.
                foreach ($amigos as $amigo) {
                ?>
                    <div class="tarjeta-amigo">
                        <span class="amigo-username"><?= $amigo['username'] ?></span>
                        <div class="acciones-amigo">
                            <a class="boton enlace-chat" href="chat/chat.php">Chatear</a>
                            <!-- Permite eliminar esta amistad aceptada. -->
                            <form class="formulario-accion" method="post">
                                <input type="hidden" name="id_amistad" value="<?= $amigo['id_amistad'] ?>">
                                <button class="boton boton-eliminar" type="submit" name="eliminar_amigo">Eliminar amigo</button>
                            </form>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>

