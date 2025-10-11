-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2025 a las 03:50:35
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_reservas_citas_peluqueria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','confirmada','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id`, `usuario_id`, `servicio_id`, `fecha`, `hora`, `estado`) VALUES
(4, 1, 4, '2025-10-04', '10:40:00', 'confirmada'),
(6, 1, 9, '2025-10-12', '10:02:00', 'cancelada'),
(7, 1, 10, '2025-10-07', '15:03:00', 'confirmada'),
(8, 1, 1, '2025-10-18', '14:30:00', 'pendiente'),
(9, 5, 2, '2025-10-17', '16:12:00', 'confirmada'),
(10, 6, 10, '2025-10-31', '12:53:00', 'pendiente'),
(11, 6, 5, '2025-10-17', '18:30:00', 'pendiente'),
(12, 1, 8, '2025-10-25', '15:17:00', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `duracion`, `precio`) VALUES
(1, 'Corte de Cabello Dama', 'Incluye lavado, corte y secado básico', 60, 15.00),
(2, 'Corte de Cabello Caballero', 'Corte clásico o moderno con acabado profesional', 40, 10.00),
(3, 'Peinado', 'Peinado para ocasión especial o casual', 45, 12.00),
(4, 'Tinte Completo', 'Aplicación de color en todo el cabello', 120, 30.00),
(5, 'Mechas', 'Mechas con técnica profesional', 150, 45.00),
(6, 'Manicure', 'Limpieza, corte, limado y esmalte', 40, 8.00),
(7, 'Pedicure', 'Limpieza, corte, limado y exfoliación', 50, 12.00),
(8, 'Alisado Permanente', 'Tratamiento de alisado de larga duración', 180, 60.00),
(9, 'Tratamiento Capilar', 'Tratamiento hidratante y reparador', 50, 20.00),
(10, 'Maquillaje', 'Maquillaje profesional para eventos', 60, 25.00),
(14, 'Maquillaje a Domicilio', 'Maquillaje a domicilio por profesionales', 35, 30.50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `github_id` varchar(50) DEFAULT NULL,
  `facebook_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `telefono`, `password`, `rol`, `github_id`, `facebook_id`) VALUES
(1, 'Dagmar Moya', 'dagmar@yahoo.com', '0984588822', '$2y$10$/I3FV66kmL0mldOo51s92.AFCRBP2sQXkGrT2iPOIFyEh449fEewa', 'cliente', NULL, NULL),
(2, 'Aaron Ortiz', 'aronortiz90@yahoo.com', '0984588822', '$2y$10$wzmBrZkOwKqu7LS2WdX4K.HPYx4oyULaxTe4Xr4OT7V9lhn76s6eq', 'admin', NULL, '24998845236432566'),
(5, 'Cristian Loor', 'cristian@gmail.com', '0984658243', '$2y$10$iyHrO.xabrJlDa17Dg99Nu6PyxQM8nJSefxRldW7Jez3ihOzTq8na', 'cliente', NULL, NULL),
(6, 'Rosario Elizabeth', 'ralbansilva@yahoo.com', '0984588899', '$2y$10$ff.fZiSpQrUA3NrBNXPOaOXI8poE3/7DCo83lT53swIzKMbgixeAe', 'cliente', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha` (`fecha`,`hora`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `servicio_id` (`servicio_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `github_id` (`github_id`),
  ADD UNIQUE KEY `facebook_id` (`facebook_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
