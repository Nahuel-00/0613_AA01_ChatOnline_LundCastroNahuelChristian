<?php

$servername = 'db';
$dbname = 'whatsapp';
$dbusername = 'nahuel';
$dbpassword = '95f97h0c';

try {
    $conn = new PDO(
        "mysql:host={$servername};dbname={$dbname};charset=utf8mb4",
        $dbusername,
        $dbpassword,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $error) {
    http_response_code(500);
    error_log('Error de conexión a MySQL: ' . $error->getMessage());
    exit('No se pudo conectar a la base de datos. Comprueba la configuración de MySQL.');
}