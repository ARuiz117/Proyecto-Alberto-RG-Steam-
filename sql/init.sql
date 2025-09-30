CREATE DATABASE IF NOT EXISTS miSteamDB;
USE miSteamDB;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    clave VARCHAR(50) NOT NULL,
    rol ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE juegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    descripcion TEXT
);

CREATE TABLE bibliotecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    juego_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (juego_id) REFERENCES juegos(id)
);

CREATE TABLE resenas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    juego_id INT,
    contenido TEXT,
    calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (juego_id) REFERENCES juegos(id)
);