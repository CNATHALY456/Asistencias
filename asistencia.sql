-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2024 a las 03:37:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `asistencia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `apellido` varchar(50) NOT NULL,
  `grado` enum('Primer año','Segundo año','Tercer año') NOT NULL,
  `seccion` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `docente_id`, `apellido`, `grado`, `seccion`) VALUES
(1, 'Juan Diego', 1, 'Marcos Medina', 'Primer año', 'C'),
(2, 'Juan Manuel', 2, 'Reyes Castro', 'Primer año', 'B'),
(3, 'Pedro Luis', 2, 'Torres Ramirez', 'Tercer año', 'A'),
(4, 'Marta Alicia', 2, 'Espinoza Paz', 'Primer año', 'B'),
(5, 'Daniel Javier', 2, 'Ramos Pineda', 'Primer año', 'B'),
(6, 'Douglas Kir', 2, 'Contreras Almendares', 'Segundo año', 'B');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `alumno_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` enum('presente','ausente','Permiso') DEFAULT NULL,
  `hora` time NOT NULL,
  `comentario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `alumno_id`, `fecha`, `estado`, `hora`, `comentario`) VALUES
(1, 3, '2024-10-16', 'presente', '07:30:00', ''),
(2, 4, '2024-10-17', 'ausente', '08:00:00', ''),
(3, 4, '2024-10-18', 'presente', '06:12:00', ''),
(4, 2, '2024-10-13', 'ausente', '19:17:00', ''),
(5, 4, '2024-10-13', 'ausente', '19:17:00', ''),
(6, 5, '2024-10-13', 'Permiso', '19:17:00', ''),
(7, 2, '2024-10-15', 'ausente', '06:23:00', 'Sin Justificante'),
(8, 4, '2024-10-15', 'presente', '06:23:00', ''),
(9, 5, '2024-10-15', 'Permiso', '06:23:00', 'Enferma'),
(10, 3, '2024-10-19', 'ausente', '08:42:00', 'sin justificante'),
(11, 6, '2024-10-20', 'Permiso', '04:20:00', 'Enfermo'),
(12, 2, '2024-10-20', 'presente', '03:50:00', 'Sin Justificante'),
(13, 4, '2024-10-20', 'ausente', '03:50:00', 'Se Encuentra Enferma'),
(14, 5, '2024-10-20', 'Permiso', '03:50:00', 'Enferma'),
(15, 2, '2024-10-19', 'presente', '20:50:00', ''),
(16, 4, '2024-10-19', 'presente', '20:50:00', ''),
(17, 5, '2024-10-19', 'presente', '20:50:00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id`, `nombre`, `correo`, `contrasena`) VALUES
(1, 'Nathaly', 'cnathaly456@gmail.com', '$2y$10$QpILn6PSYGTyC9GazldX..ImcgDK1C1SpIYFvnHk2zoEXDPC1v3Je'),
(2, 'Nahomy', 'nahomy@gmail.com', '$2y$10$U6QKtQHl.d1P95kkZQAlVOJ2DRjC9tx//tC2TkqC8BFjfPiVn3V8u');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `docente_id` (`docente_id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`);

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
