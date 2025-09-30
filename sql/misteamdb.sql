-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-09-2025 a las 13:45:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `misteamdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bibliotecas`
--

CREATE TABLE `bibliotecas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `juego_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bibliotecas`
--

INSERT INTO `bibliotecas` (`id`, `usuario_id`, `juego_id`) VALUES
(21, 1, 21);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(6,2) DEFAULT 19.99,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juegos`
--

INSERT INTO `juegos` (`id`, `titulo`, `descripcion`, `precio`, `imagen_url`) VALUES
(21, 'The Witcher 3: Wild Hunt', 'Un épico RPG de mundo abierto donde juegas como Geralt de Rivia, un cazador de monstruos en busca de su hija adoptiva.', 39.99, 'https://imgs.search.brave.com/U7HXX0NII5pcwVNRfh_erT2DvGkVYC3o2StcsaPY224/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9waWNz/LmZpbG1hZmZpbml0/eS5jb20vd2llZHpt/aW5fM19kemlraV9n/b24tMzU4NDk0NTQ3/LW1tZWQuanBn'),
(22, 'Red Dead Redemption 2', 'Una aventura de acción en el salvaje oeste con una historia profunda y un mundo abierto impresionante.', 59.99, 'https://imgs.search.brave.com/Vtg5ceu8xxVhdClvzhXBjwJvfM2YJMgFAmmIdFWx6SQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLmJs/b2dzLmVzL2NlZWRl/NC9ibG9iL29yaWdp/bmFsLmpwZWc'),
(23, 'Cyberpunk 2077', 'Un RPG futurista en Night City, donde tus decisiones afectan la historia y el mundo.', 49.99, 'https://imgs.search.brave.com/lMIOieYZW2t0YIMNyPDlxj5dib84-vURwzdDS01rGAE/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zdGF0/aWMwLmdhbWVyYW50/aW1hZ2VzLmNvbS93/b3JkcHJlc3Mvd3At/Y29udGVudC91cGxv/YWRzLzIwMjQvMTIv/bWl4Y29sbGFnZS0w/OC1kZWMtMjAyNC0w/Mi0zOC1wbS0zMTE2/LmpwZw'),
(24, 'Grand Theft Auto V', 'Explora Los Santos en este juego de acción y mundo abierto, con modo historia y multijugador.', 29.99, 'https://imgs.search.brave.com/QhNppADLP_9weCtPB0jBuZXMxBKRNchoj7ISnb-y7fY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZS5hcGkucGxheXN0/YXRpb24uY29tL2Nk/bi9VUDEwMDQvQ1VT/QTAwNDE5XzAwL2JU/TlNlN29rOGVGVkdl/UUJ5QTVxU3pCUW9L/QUFZMzJSLnBuZw'),
(25, 'Elden Ring', 'Un RPG de acción en un mundo abierto creado por FromSoftware y George R. R. Martin.', 59.99, 'https://assets.xboxservices.com/assets/7b/54/7b54f5e4-0857-4ce3-8a18-2b8c431e8a9e.jpg?n=Elden-Ring_GLP-Page-Hero-0_1083x1222_01.jpg'),
(26, 'Hollow Knight', 'Un metroidvania de acción y aventura en un mundo subterráneo lleno de insectos y misterios.', 14.99, 'https://gaming-cdn.com/images/products/2198/616x353/hollow-knight-pc-mac-juego-steam-cover.jpg?v=1705490619'),
(27, 'Celeste', 'Un desafiante juego de plataformas sobre escalar una montaña y superar tus propios límites.', 19.99, 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bd/Celeste_video_game_logo.png/250px-Celeste_video_game_logo.png'),
(28, 'Stardew Valley', 'Simulador de granja donde puedes cultivar, criar animales, pescar y explorar cuevas.', 13.99, 'https://imgs.search.brave.com/DJqAJFbpHocH7w1Qk6dwIyyO0TDPCPQe_L1dWTNoMdU/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWFn/ZXMubmV4dXNtb2Rz/LmNvbS9pbWFnZXMv/Z2FtZXMvdjIvMTMw/My90aWxlLmpwZw'),
(29, 'Undertale', 'Un RPG único donde puedes elegir no matar a ningún enemigo y tus decisiones importan.', 9.99, 'https://imgs.search.brave.com/_1Cbq-sG1XFc0BPH2NXfqIRdZGOY_bO2CMgDCVMykUI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tLm1l/ZGlhLWFtYXpvbi5j/b20vaW1hZ2VzL00v/TVY1Qk1XSTNaVGt4/WmprdFlXVTNOQzAw/T0dRMUxXRmxOemd0/WXpJd01XSTRORGcy/WVRVMFhrRXlYa0Zx/Y0djQC5qcGc'),
(30, 'Cuphead', 'Un juego de plataformas y disparos con estética de dibujos animados de los años 30 y dificultad elevada.', 19.99, 'https://imgs.search.brave.com/Tavux6EMJShe1ZTkhrlhNtcVV-z62b3tv9PwxgOpTsQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLjNk/anVlZ29zLmNvbS9q/dWVnb3MvMTA1ODMv/Y3VwaGVhZC9mb3Rv/cy9maWNoYS9jdXBo/ZWFkLTM4NDE5MzIu/d2VicA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `juego_id` int(11) DEFAULT NULL,
  `contenido` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `clave` varchar(50) NOT NULL,
  `rol` enum('user','admin') DEFAULT 'user',
  `saldo` decimal(10,2) DEFAULT 100.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `clave`, `rol`, `saldo`) VALUES
(1, 'usuario1', 'usuario1@steamhrg.com', 'clave1', 'user', 60.01),
(2, 'admin1', 'admin1@steamhrg.com', 'adminpass', 'admin', 100.00),
(3, 'usuario3', 'usuario3@gmail.com', 'clave3', 'user', 100.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `juego_id` (`juego_id`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `juego_id` (`juego_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bibliotecas`
--
ALTER TABLE `bibliotecas`
  ADD CONSTRAINT `bibliotecas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `bibliotecas_ibfk_2` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`);

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
