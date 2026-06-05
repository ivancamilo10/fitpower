-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2026 a las 04:38:10
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fitpower`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT curdate(),
  `tipo_pago` enum('Día','Mensualidad') NOT NULL,
  `nombre_dia` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistencias`
--

INSERT INTO `asistencias` (`id`, `usuario_id`, `fecha`, `tipo_pago`, `nombre_dia`) VALUES
(7, NULL, '2025-06-27', 'Día', 'jordan'),
(9, NULL, '2025-07-30', 'Día', 'fulanito'),
(10, NULL, '2025-11-03', 'Día', 'vale'),
(11, 14, '2025-11-03', 'Mensualidad', NULL),
(12, NULL, '2025-11-05', 'Día', 'Ever'),
(13, 14, '2025-11-05', 'Mensualidad', NULL),
(14, 14, '2026-04-19', 'Mensualidad', NULL),
(16, 20, '2026-04-19', 'Mensualidad', NULL),
(17, 20, '2026-04-19', 'Mensualidad', NULL),
(18, NULL, '2026-04-25', 'Día', 'janer');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `definicion`
--

CREATE TABLE `definicion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `estatura` int(11) DEFAULT NULL,
  `peso_objetivo` decimal(5,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `definicion`
--

INSERT INTO `definicion` (`id`, `usuario_id`, `peso`, `estatura`, `peso_objetivo`, `fecha`) VALUES
(3, 16, '72.00', 163, '65.00', '2025-11-03 17:27:40'),
(4, 20, '90.00', 190, '80.00', '2026-04-19 02:01:46'),
(5, 20, '90.00', 190, '80.00', '2026-04-19 02:03:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `masa_muscular`
--

CREATE TABLE `masa_muscular` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `estatura` int(11) DEFAULT NULL,
  `meta_peso` decimal(5,2) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `masa_muscular`
--

INSERT INTO `masa_muscular` (`id`, `usuario_id`, `peso`, `estatura`, `meta_peso`, `fecha`) VALUES
(9, 15, '67.00', 173, '75.00', '2025-08-01 02:11:17'),
(10, 17, '70.00', 172, '75.00', '2025-11-05 01:42:15'),
(11, 18, '73.00', 173, '80.00', '2026-04-18 23:58:55'),
(12, 18, '73.00', 173, '80.00', '2026-04-18 23:59:58'),
(13, 20, '100.00', 190, '90.00', '2026-04-19 02:05:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT 'efectivo',
  `observaciones` text DEFAULT NULL,
  `fecha_pago` date NOT NULL DEFAULT curdate(),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `usuario_id`, `monto`, `metodo_pago`, `observaciones`, `fecha_pago`, `creado_en`) VALUES
(7, 14, '60000.00', 'efectivo', NULL, '2025-11-03', '2025-11-03 15:52:55'),
(8, 16, '60000.00', 'efectivo', NULL, '2025-11-03', '2025-11-03 17:30:41'),
(9, 17, '70000.00', 'efectivo', NULL, '2025-11-05', '2025-11-05 01:45:07'),
(10, 14, '55000.00', 'efectivo', NULL, '2026-04-19', '2026-04-19 00:22:16'),
(11, 20, '50000.00', 'efectivo', NULL, '2026-04-19', '2026-04-19 02:07:27'),
(12, 22, '180000.00', 'efectivo', NULL, '2026-04-25', '2026-04-25 02:32:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expira_en` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `correo`, `token`, `expira_en`, `created_at`) VALUES
(6, 'astablack80@gmail.com', '0592566fc20474d1e8c680759637a70f68bf1d8257166a8867a6db1d58e4202e', '2026-04-19 23:36:22', '2026-04-19 20:36:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perder_peso`
--

CREATE TABLE `perder_peso` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `peso` float NOT NULL,
  `estatura` int(11) NOT NULL,
  `meta_peso` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutinas_usuario`
--

CREATE TABLE `rutinas_usuario` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_rutina` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `progreso` int(11) NOT NULL DEFAULT 0,
  `dia_actual` int(11) NOT NULL DEFAULT 1,
  `total_dias` int(11) NOT NULL DEFAULT 5,
  `estado` varchar(50) NOT NULL DEFAULT 'activa',
  `fecha_inicio` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rutinas_usuario`
--

INSERT INTO `rutinas_usuario` (`id`, `usuario_id`, `nombre_rutina`, `descripcion`, `progreso`, `dia_actual`, `total_dias`, `estado`, `fecha_inicio`, `fecha_actualizacion`) VALUES
(1, 20, 'Pecho y Tríceps', 'Rutina enfocada en empujes pesados, hipertrofia y fuerza del tren superior.', 100, 5, 5, 'completada', '2026-04-19 19:33:07', '2026-04-19 19:33:17'),
(2, 20, 'Pecho y Tríceps', 'Rutina enfocada en empujes pesados, hipertrofia y fuerza del tren superior.', 0, 1, 5, 'archivada', '2026-04-19 19:33:19', '2026-04-19 19:33:28'),
(3, 20, 'Espalda y Bíceps', 'Rutina centrada en dominadas, remos y trabajo completo de tirón.', 0, 1, 5, 'activa', '2026-04-19 19:33:28', '2026-04-19 19:33:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `objetivo` varchar(100) DEFAULT NULL,
  `peso` float DEFAULT NULL,
  `banca` int(11) DEFAULT NULL,
  `sentadilla` int(11) DEFAULT NULL,
  `peso_muerto` int(11) DEFAULT NULL,
  `fecha_fin_mensualidad` date DEFAULT NULL,
  `rol` varchar(20) DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `correo`, `token`, `objetivo`, `peso`, `banca`, `sentadilla`, `peso_muerto`, `fecha_fin_mensualidad`, `rol`) VALUES
(10, 'Administrador', 'adminfit', '$2y$10$.xULotGOP5ns.LUyixaPyePVpZ7ORjt9FWZyE4a2WTwyAukxZhMpW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2099-12-31', 'admin'),
(14, 'ivan', 'ivan46', '$2y$10$gk.EOpho3Oq/nFSz2zM/d.NZd1lNrtiDjtvNpDWN.gnX8MGrQvyxe', 'ivan@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-19', 'cliente'),
(15, 'ivan mejiaa', 'ivan02', '$2y$10$DNyZhbWI3ygofYtNu10AOuIQJhGpx3dDw4MlfO9T9RFTXUh5Pu98K', 'ivantrian5@gmail.com', NULL, NULL, 67, NULL, NULL, NULL, '2025-12-11', 'cliente'),
(16, 'Valentina', 'vale123', '$2y$10$NFIxIpxJuevmCIRYHwSUQ.1uZ/eruPatMlyXajeOC8ZkCKacz3oRe', 'vale@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-03', 'cliente'),
(17, 'Ever Lozano', 'ever12345', '$2y$10$V5WK8rpv5GNurxT4xH0iFOAZjtzuFHHEQWRmCj.9Cc8Ia41XJrekq', 'ever@gmail.com', NULL, NULL, 70, NULL, NULL, NULL, '2025-12-05', 'cliente'),
(18, 'Camilo Mejia', 'camilo12345', '$2y$10$6OQM7YHk/UnY27aQBjJA8OVa6osdw.zx3W5/ELSVkvApSo4lgGqJm', '2005@gmail.com', NULL, 'ganar masa', 73, 100, 80, 100, NULL, 'cliente'),
(19, 'Ivan', 'ivanmejia12345', '$2y$10$4.ybIC/Bq5bOYsvaxORBa.uhDHeMnG4bk8xBQduNE5ddzXRu.TGma', 'ivantriana2@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cliente'),
(20, 'fulatino acosta', 'fulanito123', '$2y$10$rkypLd1j5sCRSdZsyFcKReSyGMVI3roas1AvEAqMqu1tyDWfDpzze', 'fulanito123@gmail.com', NULL, 'perder peso', 100, 100, 100, 100, '2026-05-19', 'cliente'),
(21, 'Juan Perez', 'juan12345', '$2y$10$sBAzti9CHlgoJyijiBFWj.zKVUy94ATTyf8tl7d3n0qxeOzgrYM3e', 'astablack80@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cliente'),
(22, 'janer chamorro ', 'janer123', '$2y$10$HFFVpy7xwSEP5X4sqp3N1.AWwy3moKE2FoDeWjJ87kUs0Jkv//9yW', 'janer@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-24', 'cliente');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `definicion`
--
ALTER TABLE `definicion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `definicion_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `masa_muscular`
--
ALTER TABLE `masa_muscular`
  ADD PRIMARY KEY (`id`),
  ADD KEY `masa_muscular_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagos_ibfk_1` (`usuario_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `perder_peso`
--
ALTER TABLE `perder_peso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `rutinas_usuario`
--
ALTER TABLE `rutinas_usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `definicion`
--
ALTER TABLE `definicion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `masa_muscular`
--
ALTER TABLE `masa_muscular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `perder_peso`
--
ALTER TABLE `perder_peso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rutinas_usuario`
--
ALTER TABLE `rutinas_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `definicion`
--
ALTER TABLE `definicion`
  ADD CONSTRAINT `definicion_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `masa_muscular`
--
ALTER TABLE `masa_muscular`
  ADD CONSTRAINT `masa_muscular_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perder_peso`
--
ALTER TABLE `perder_peso`
  ADD CONSTRAINT `perder_peso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
