<?php

session_start();
include '../Validaciones/validacion.php';
//validacion de usuario y contraseña
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    validarUsername($_POST['username'] ?? null) === '' &&
    validarPassword($_POST['password'] ?? null) === ''
) {

// Conexión a la base de datos para iniciar sesion con los datos que se guardaron en la base de datos
    include '../base de datos/conexion.php';

    $consulta = $conn->prepare(
        'SELECT password_hash FROM usuarios WHERE username = :username'
    );
    $consulta->execute(['username' => $_POST['username']]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($_POST['password'], $usuario['password_hash'])) {
        //variable de sesion para guardar el username del usuario en el index
        $_SESSION['username'] = $_POST['username'];
        header('Location: ../index.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../Estilos/style.css">
</head>
<body class="pagina-formulario">
    <div id="login-contenedor" class="contenedor-formulario">
        <div class="titulo-formulario">
            <h1>Iniciar sesión</h1>
            <p>Accede con tu username y tu contraseña</p>
        </div>

        <form id="formulario-login" class="formulario" method="post">
            <div class="campo-formulario">
                <label for="username">Username</label>
                <input id="username" name="username" type="text">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarUsername($_POST['username'] ?? null); ?></small>
            </div>
            <div class="campo-formulario">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarPassword($_POST['password'] ?? null); ?></small>
            </div>
            <input id="boton-login" class="boton-formulario" type="submit" name="entrar" value="Entrar">
        </form>

        <p class="enlace-formulario">¿No tienes cuenta? <a href="../Registrarse/Registrarse.php">Regístrate</a></p>
    </div>
</body>
</html>
