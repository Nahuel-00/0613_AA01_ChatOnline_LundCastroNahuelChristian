<?php

function campoVacio($valor)
{
    if (empty($valor)) {
        return 'El campo no puede estar vacío.';
    }

    return '';
}

function validarUsername($username)
{
    $error = campoVacio($username);
    if ($error !== '') {
        return $error;
    }
    if (!is_string($username)) {
        return 'Username no válido.';
    }

    for ($i = 0; $i < strlen($username); $i++) {
        if ($username[$i] >= '0' && $username[$i] <= '9') {
            return 'El username no puede contener números.';
        }
    }

    return '';
}

function validarNombre($nombre)
{
    $error = campoVacio($nombre);
    if ($error !== '') {
        return $error;
    }
    if (!is_string($nombre) || strlen($nombre) <= 2) {
        return 'El nombre debe tener más de 2 caracteres.';
    }

    return '';
}

function validarCorreo($correo)
{
    $error = campoVacio($correo);
    if ($error !== '') {
        return $error;
    }
    if (!is_string($correo) || filter_var($correo, FILTER_VALIDATE_EMAIL) === false) {
        return 'Introduce un correo válido.';
    }

    return '';
}

function validarPassword($password)
{
    $error = campoVacio($password);
    if ($error !== '') {
        return $error;
    }
    if (!is_string($password)) {
        return 'Contraseña no válida.';
    }

    $numeros = $letras = $mayusculas = 0;
    for ($i = 0; $i < strlen($password); $i++) {
        $caracter = $password[$i];
        if ($caracter >= '0' && $caracter <= '9') {
            $numeros++;
        } elseif (($caracter >= 'a' && $caracter <= 'z') || ($caracter >= 'A' && $caracter <= 'Z')) {
            $letras++;
            if ($caracter >= 'A' && $caracter <= 'Z') {
                $mayusculas++;
            }
        }
    }

    if ($numeros < 3 || $letras < 2 || $mayusculas === 0) {
        return 'Usa al menos 3 números, 2 letras y 1 mayúscula.';
    }

    return '';
}
