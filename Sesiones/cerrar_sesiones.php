<?php 

session_start(); 

// Destruimos todas las variables de sesión 
session_unset(); 

// Destruimos la sesión
session_destroy(); 

// Redirigir al usuario al inicio de sesión.
header('Location: ../Login/Login.php');

exit(); 