<?php

include '../Validaciones/validacion.php';
//validacion de usuario, nombre real, correo y contraseña
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    validarUsername($_POST['username'] ?? null) === '' &&
    validarNombre($_POST['nombre_real'] ?? null) === '' &&
    validarCorreo($_POST['correo'] ?? null) === '' &&
    validarPassword($_POST['password'] ?? null) === ''
) {

// Conexión a la base de datos para guardar los datos del usuario en la base de datos al registrarse
    include '../base de datos/conexion.php';
    $consulta = $conn->prepare(
        'INSERT INTO usuarios (username, nombre_real, correo, password_hash)
         VALUES (:username, :nombre_real, :correo, :password_hash)'
    );
    $consulta->execute([
        'username' => $_POST['username'],
        'nombre_real' => $_POST['nombre_real'],
        'correo' => $_POST['correo'],
        'password_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT),
    ]);
//variable de sesion para guardar el username del usuario en el index
    session_start();
    $_SESSION['username'] = $_POST['username'];
    header('Location: ../index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="../Estilos/style.css">
</head>
<body class="pagina-formulario">
    <div id="registro-contenedor" class="contenedor-formulario">
        <div class="titulo-formulario">
            <h1>Crear cuenta</h1>
            <p>Rellena tus datos para registrarte</p>
        </div>

        <form id="formulario-registro" class="formulario" method="post">
            <div class="campo-formulario">
                <label for="username">Username</label>
                <input id="username" name="username" type="text">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarUsername($_POST['username'] ?? null); ?></small>
            </div>

            <div class="campo-formulario">
                <label for="nombre-real">Nombre real</label>
                <input id="nombre-real" name="nombre_real" type="text">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarNombre($_POST['nombre_real'] ?? null); ?></small>
            </div>

            <div class="campo-formulario">
                <label for="correo">Correo electrónico</label>
                <input id="correo" name="correo" type="text">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarCorreo($_POST['correo'] ?? null); ?></small>
            </div>

            <div class="campo-formulario">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password">
                <small class="mensaje-validacion"><?php if ($_SERVER['REQUEST_METHOD'] === 'POST') echo validarPassword($_POST['password'] ?? null); ?></small>
            </div>

            <input id="boton-registro" class="boton-formulario" type="submit" name="crear_cuenta" value="Crear cuenta">
        </form>

        <p class="enlace-formulario">¿Ya tienes cuenta? <a href="../Login/Login.php">Inicia sesión</a></p>
    </div>
</body>
</html>