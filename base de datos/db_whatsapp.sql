CREATE DATABASE whatsapp;

USE whatsapp;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nombre_real VARCHAR(120) NOT NULL,
    correo VARCHAR(254) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE amigos (
    id_amistad INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id_1 INT NOT NULL,
    usuario_id_2 INT NOT NULL,
    solicitante_id INT NOT NULL,
    estado ENUM('pendiente', 'aceptado') NOT NULL DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id_1) REFERENCES usuarios (id_usuario),
    FOREIGN KEY (usuario_id_2) REFERENCES usuarios (id_usuario),
    FOREIGN KEY (solicitante_id) REFERENCES usuarios (id_usuario)
);

CREATE TABLE chats (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    amistad_id INT NOT NULL,
    emisor_id INT NOT NULL,
    tipo ENUM('texto', 'imagen', 'archivo') NOT NULL DEFAULT 'texto',
    contenido VARCHAR(250) NULL,
    archivo VARCHAR(255) NULL,
    FOREIGN KEY (amistad_id) REFERENCES amigos (id_amistad),
    FOREIGN KEY (emisor_id) REFERENCES usuarios (id_usuario)
);


CREATE TABLE ADMINISTRADORES (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id_usuario)
);


SELECT id_usuario FROM usuarios WHERE username = 'admin';

INSERT INTO usuarios (username, nombre_real, correo, password_hash)
VALUES ('admin', 'Administrador', 'admin@chatonline.local', '$2y$10$oASGI1G6nZna21WTRpYoBuQMcNc2M6LTuE5qUzOBorwDcbAet4b8.');


