-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-06-2026 a las 00:17:21
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
-- Base de datos: `barberia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_Usuario`) VALUES
(6),
(7),
(8),
(9),
(10),
(29),
(31),
(32),
(34);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_usuario` int(11) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL,
  `especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_usuario`, `estado`, `especialidad`) VALUES
(1, 'ACTIVO', 1),
(2, 'ACTIVO', 2),
(3, 'ACTIVO', 3),
(4, 'ACTIVO', 1),
(5, 'ACTIVO', 5),
(30, 'ACTIVO', 1),
(33, 'INACTIVO', 1),
(35, 'ACTIVO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_servicios`
--

CREATE TABLE `empleado_servicios` (
  `idEmpleado` int(11) NOT NULL,
  `idServicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado_servicios`
--

INSERT INTO `empleado_servicios` (`idEmpleado`, `idServicio`) VALUES
(1, 1),
(1, 2),
(1, 4),
(2, 2),
(2, 4),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 4),
(5, 1),
(5, 5),
(30, 1),
(33, 1),
(35, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_empleado`
--

CREATE TABLE `horario_empleado` (
  `idHorario` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `horaIni` time NOT NULL,
  `horaFin` time NOT NULL,
  `horaDescansoIni` time DEFAULT NULL,
  `horaDescansoFin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario_empleado`
--

INSERT INTO `horario_empleado` (`idHorario`, `idEmpleado`, `horaIni`, `horaFin`, `horaDescansoIni`, `horaDescansoFin`) VALUES
(1, 1, '09:00:00', '13:00:00', '00:00:00', '00:00:00'),
(2, 1, '16:00:00', '20:00:00', '00:00:00', '00:00:00'),
(3, 2, '10:00:00', '14:00:00', '00:00:00', '00:00:00'),
(4, 2, '17:00:00', '21:00:00', '00:00:00', '00:00:00'),
(5, 3, '09:00:00', '17:00:00', '12:30:00', '13:00:00'),
(6, 4, '11:00:00', '19:00:00', '14:30:00', '15:00:00'),
(7, 5, '13:00:00', '21:00:00', '16:30:00', '17:00:00'),
(8, 35, '09:00:00', '17:00:00', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `idResena` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `fechaCreacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`idResena`, `idCliente`, `idEmpleado`, `puntuacion`, `comentario`, `fechaCreacion`) VALUES
(1, 32, 5, 2, 'malardo', '2026-06-25 19:02:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `idReserva` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idEmpleado` int(11) NOT NULL,
  `idServicio` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `estado` enum('PENDIENTE','CONFIRMADA','CANCELADA','COMPLETADA') NOT NULL DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`idReserva`, `idCliente`, `idEmpleado`, `idServicio`, `fecha`, `horaInicio`, `horaFin`, `estado`) VALUES
(141, 6, 1, 1, '2026-06-20', '09:00:00', '09:30:00', 'CANCELADA'),
(142, 7, 1, 2, '2026-06-20', '09:30:00', '10:15:00', 'COMPLETADA'),
(143, 8, 1, 4, '2026-06-20', '10:15:00', '10:35:00', 'PENDIENTE'),
(144, 9, 1, 1, '2026-06-20', '10:35:00', '11:05:00', 'PENDIENTE'),
(145, 10, 1, 2, '2026-06-20', '11:05:00', '11:50:00', 'COMPLETADA'),
(146, 6, 1, 4, '2026-06-20', '11:50:00', '12:10:00', 'PENDIENTE'),
(147, 7, 1, 1, '2026-06-20', '12:10:00', '12:40:00', 'PENDIENTE'),
(148, 8, 1, 4, '2026-06-20', '12:40:00', '13:00:00', 'PENDIENTE'),
(149, 9, 1, 2, '2026-06-20', '16:00:00', '16:45:00', 'PENDIENTE'),
(150, 10, 1, 1, '2026-06-20', '16:45:00', '17:15:00', 'PENDIENTE'),
(151, 6, 1, 4, '2026-06-20', '17:15:00', '17:35:00', 'PENDIENTE'),
(152, 7, 1, 2, '2026-06-20', '17:35:00', '18:20:00', 'PENDIENTE'),
(153, 8, 1, 1, '2026-06-20', '18:20:00', '18:50:00', 'PENDIENTE'),
(154, 9, 1, 4, '2026-06-20', '18:50:00', '19:10:00', 'PENDIENTE'),
(155, 10, 1, 1, '2026-06-20', '19:10:00', '19:40:00', 'PENDIENTE'),
(156, 6, 1, 4, '2026-06-20', '19:40:00', '20:00:00', 'PENDIENTE'),
(157, 7, 2, 2, '2026-06-20', '10:00:00', '10:45:00', 'CANCELADA'),
(158, 8, 2, 4, '2026-06-20', '10:45:00', '11:05:00', 'PENDIENTE'),
(159, 9, 2, 2, '2026-06-20', '11:05:00', '11:50:00', 'PENDIENTE'),
(160, 10, 2, 4, '2026-06-20', '11:50:00', '12:10:00', 'PENDIENTE'),
(161, 6, 2, 2, '2026-06-20', '12:10:00', '12:55:00', 'PENDIENTE'),
(162, 7, 2, 4, '2026-06-20', '12:55:00', '13:15:00', 'PENDIENTE'),
(163, 8, 2, 2, '2026-06-20', '13:15:00', '14:00:00', 'PENDIENTE'),
(164, 9, 2, 2, '2026-06-20', '17:00:00', '17:45:00', 'PENDIENTE'),
(165, 10, 2, 4, '2026-06-20', '17:45:00', '18:05:00', 'PENDIENTE'),
(166, 6, 2, 2, '2026-06-20', '18:05:00', '18:50:00', 'PENDIENTE'),
(167, 7, 2, 4, '2026-06-20', '18:50:00', '19:10:00', 'PENDIENTE'),
(168, 8, 2, 2, '2026-06-20', '19:10:00', '19:55:00', 'PENDIENTE'),
(169, 9, 2, 4, '2026-06-20', '19:55:00', '20:15:00', 'PENDIENTE'),
(170, 10, 2, 2, '2026-06-20', '20:15:00', '21:00:00', 'PENDIENTE'),
(171, 6, 3, 3, '2026-06-20', '09:00:00', '10:00:00', 'CANCELADA'),
(172, 7, 3, 2, '2026-06-20', '10:00:00', '10:45:00', 'CANCELADA'),
(173, 8, 3, 1, '2026-06-20', '10:45:00', '11:15:00', 'PENDIENTE'),
(174, 9, 3, 3, '2026-06-20', '11:15:00', '12:15:00', 'COMPLETADA'),
(175, 10, 3, 1, '2026-06-20', '12:00:00', '12:30:00', 'COMPLETADA'),
(176, 6, 3, 3, '2026-06-20', '13:00:00', '14:00:00', 'COMPLETADA'),
(177, 7, 3, 2, '2026-06-20', '14:00:00', '14:45:00', 'CONFIRMADA'),
(178, 8, 3, 3, '2026-06-20', '14:45:00', '15:45:00', 'CONFIRMADA'),
(179, 9, 3, 2, '2026-06-20', '15:45:00', '16:30:00', 'COMPLETADA'),
(180, 10, 3, 1, '2026-06-20', '16:30:00', '17:00:00', 'COMPLETADA'),
(181, 6, 4, 1, '2026-06-20', '11:00:00', '11:30:00', 'PENDIENTE'),
(182, 7, 4, 4, '2026-06-20', '11:30:00', '11:50:00', 'PENDIENTE'),
(183, 8, 4, 1, '2026-06-20', '11:50:00', '12:20:00', 'PENDIENTE'),
(184, 9, 4, 4, '2026-06-20', '12:20:00', '12:40:00', 'PENDIENTE'),
(185, 10, 4, 1, '2026-06-20', '12:40:00', '13:10:00', 'PENDIENTE'),
(186, 6, 4, 4, '2026-06-20', '13:10:00', '13:30:00', 'PENDIENTE'),
(187, 7, 4, 1, '2026-06-20', '13:30:00', '14:00:00', 'PENDIENTE'),
(188, 8, 4, 4, '2026-06-20', '14:00:00', '14:20:00', 'PENDIENTE'),
(189, 9, 4, 1, '2026-06-20', '15:00:00', '15:30:00', 'PENDIENTE'),
(190, 10, 4, 4, '2026-06-20', '15:30:00', '15:50:00', 'PENDIENTE'),
(191, 6, 4, 1, '2026-06-20', '15:50:00', '16:20:00', 'PENDIENTE'),
(192, 7, 4, 4, '2026-06-20', '16:20:00', '16:40:00', 'PENDIENTE'),
(193, 8, 4, 1, '2026-06-20', '16:40:00', '17:10:00', 'PENDIENTE'),
(194, 9, 4, 4, '2026-06-20', '17:10:00', '17:30:00', 'PENDIENTE'),
(195, 10, 4, 1, '2026-06-20', '17:30:00', '18:00:00', 'PENDIENTE'),
(196, 6, 4, 4, '2026-06-20', '18:00:00', '18:20:00', 'PENDIENTE'),
(197, 7, 4, 1, '2026-06-20', '18:20:00', '18:50:00', 'PENDIENTE'),
(198, 8, 5, 5, '2026-06-20', '13:00:00', '13:40:00', 'PENDIENTE'),
(199, 9, 5, 1, '2026-06-20', '13:40:00', '14:10:00', 'PENDIENTE'),
(200, 10, 5, 5, '2026-06-20', '14:10:00', '14:50:00', 'PENDIENTE'),
(201, 6, 5, 1, '2026-06-20', '14:50:00', '15:20:00', 'PENDIENTE'),
(202, 7, 5, 5, '2026-06-20', '15:20:00', '16:00:00', 'PENDIENTE'),
(203, 8, 5, 1, '2026-06-20', '16:00:00', '16:30:00', 'PENDIENTE'),
(204, 9, 5, 5, '2026-06-20', '17:00:00', '17:40:00', 'PENDIENTE'),
(205, 10, 5, 1, '2026-06-20', '17:40:00', '18:10:00', 'PENDIENTE'),
(206, 6, 5, 5, '2026-06-20', '18:10:00', '18:50:00', 'PENDIENTE'),
(207, 7, 5, 1, '2026-06-20', '18:50:00', '19:20:00', 'PENDIENTE'),
(208, 8, 5, 5, '2026-06-20', '19:20:00', '20:00:00', 'PENDIENTE'),
(209, 9, 5, 1, '2026-06-20', '20:00:00', '20:30:00', 'PENDIENTE'),
(210, 10, 5, 1, '2026-06-20', '20:30:00', '21:00:00', 'PENDIENTE'),
(235, 32, 3, 1, '2026-06-22', '13:00:00', '13:30:00', 'COMPLETADA'),
(236, 32, 1, 1, '2026-06-22', '16:30:00', '17:00:00', 'CANCELADA'),
(237, 32, 3, 2, '2026-06-22', '14:15:00', '15:00:00', 'CANCELADA'),
(238, 32, 4, 1, '2026-06-23', '12:30:00', '13:00:00', 'PENDIENTE'),
(239, 32, 35, 1, '2026-06-24', '10:30:00', '11:00:00', 'CANCELADA'),
(240, 32, 4, 1, '2026-06-25', '12:30:00', '13:00:00', 'CANCELADA'),
(241, 32, 3, 1, '2026-06-24', '15:50:00', '16:20:00', 'CANCELADA'),
(242, 32, 1, 1, '2026-06-25', '09:30:00', '10:00:00', 'COMPLETADA'),
(243, 32, 2, 2, '2026-06-25', '12:15:00', '13:00:00', 'CANCELADA'),
(244, 32, 3, 2, '2026-06-26', '11:15:00', '12:00:00', 'CANCELADA'),
(245, 32, 2, 2, '2026-06-27', '13:00:00', '13:45:00', 'CANCELADA'),
(246, 32, 2, 2, '2026-06-25', '13:00:00', '13:45:00', 'PENDIENTE'),
(247, 32, 3, 2, '2026-06-26', '13:45:00', '14:30:00', 'COMPLETADA'),
(248, 32, 2, 2, '2026-06-27', '17:00:00', '17:45:00', 'PENDIENTE'),
(249, 32, 4, 1, '2026-06-25', '11:30:00', '12:00:00', 'CANCELADA'),
(250, 32, 1, 1, '2026-06-25', '09:00:00', '09:30:00', 'CANCELADA'),
(251, 32, 1, 1, '2026-06-25', '09:00:00', '09:30:00', 'PENDIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idServicio` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`idServicio`, `nombre`, `descripcion`, `duracion`, `precio`) VALUES
(1, 'Corte de Pelo Tradicional', 'Corte clásico a tijera y máquina con lavado incluido.', 30, 450),
(2, 'Perfilado de Barba', 'Arreglo de barba con toalla premium y navaja.', 45, 350),
(3, 'Corte + Barba Combo', 'Servicio completo de corte de cabello y diseño de barba.', 60, 700),
(4, 'Lavado y Peinado', 'Lavado con productos premium y peinado con cera o pomada.', 20, 200),
(5, 'Coloración / Tintura', 'Tinte completo para cabello o barba.', 40, 600);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `ci` varchar(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fechaNac` date NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `celular` varchar(11) NOT NULL,
  `tipoUsuario` enum('CLIENTE','ADMIN','EMPLEADO','') NOT NULL,
  `fechaCreacion` date NOT NULL DEFAULT curdate(),
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `ci`, `nombre`, `apellido`, `fechaNac`, `password_hash`, `email`, `foto`, `celular`, `tipoUsuario`, `fechaCreacion`, `direccion`) VALUES
(1, '41234567', 'Carlos', 'Gómez', '1990-05-12', '1234', 'carlos@barberia.com', '/uploads/PerfilPorDefecto.png', '099123456', 'EMPLEADO', '2026-06-14', NULL),
(2, '47654321', 'María', 'Rodríguez', '1995-08-22', '1234', 'maria@barberia.com', '/uploads/PerfilPorDefecto.png', '098765432', 'EMPLEADO', '2026-06-14', NULL),
(3, '50123456', 'Juan', 'Pérez', '1988-01-30', '1234', 'juan@barberia.com', '/uploads/PerfilPorDefecto.png', '097111222', 'EMPLEADO', '2026-06-14', NULL),
(4, '39876543', 'Diego', 'Fernández', '1993-11-15', '1234', 'diego@barberia.com', '/uploads/PerfilPorDefecto.png', '096333444', 'EMPLEADO', '2026-06-14', NULL),
(5, '48521364', 'Ana', 'Martínez', '1997-03-05', '1234', 'ana@barberia.com', '/uploads/PerfilPorDefecto.png', '095555666', 'EMPLEADO', '2026-06-14', NULL),
(6, '51234567', 'Lucas', 'Silva', '1998-07-14', '1234', 'lucas@gmail.com', '/uploads/PerfilPorDefecto.png', '094111222', 'CLIENTE', '2026-06-14', NULL),
(7, '49876542', 'Mateo', 'Álvarez', '1992-03-22', '1234', 'mateo@gmail.com', '/uploads/PerfilPorDefecto.png', '093444555', 'CLIENTE', '2026-06-14', NULL),
(8, '41122334', 'Roberto', 'Pereira', '2000-11-05', '1234', 'nico@gmail.com', '/uploads/PerfilPorDefecto.png', '092777888', 'CLIENTE', '2026-06-14', NULL),
(9, '38527419', 'Santiago', 'Acosta', '1985-05-19', '1234', 'santi@gmail.com', '/uploads/PerfilPorDefecto.png', '091999000', 'CLIENTE', '2026-06-14', NULL),
(10, '52341234', 'Mathias', 'Díaz', '1996-09-11', '1234', 'mathi@gmail.com', '/uploads/PerfilPorDefecto.png', '095222333', 'CLIENTE', '2026-06-14', NULL),
(28, '12345678', 'Admin', 'Principal', '1990-01-01', 'admin', 'admin@barberia.com', NULL, '099123456', 'ADMIN', '2026-06-19', 'Barbería Central'),
(29, '12121212', 'german', 'Echaide', '2026-06-20', '123', 'germanechaide@gmail.com', NULL, '098267299', 'CLIENTE', '2026-06-20', NULL),
(30, '99999999', 'Federico', 'asdsad', '0000-00-00', 'empleado', 'federico@gmail.com', '/uploads/PerfilPorDefecto.png', '098267299', 'EMPLEADO', '2026-06-21', NULL),
(31, '11111111', 'Franco', 'Echaide', '2026-06-22', 'fra02nicol97', 'germanechaide2@gmail.com', '/uploads/11111111/fotoPerfil_1782100667.png', '098282892', 'CLIENTE', '2026-06-22', NULL),
(32, '53507227', 'Franco', 'Echaide', '2026-06-22', '12345', 'echaidefranco@gmail.com', '/uploads/53507227/fotoPerfil_1782131382.png', '098267299', 'CLIENTE', '2026-06-22', 'Playa hermosa'),
(33, '12111111', 'Carlitos', 'Portuano', '1997-05-17', '12345', 'carlitos@gmail.com', '/uploads/PerfilPorDefecto.png', '098267299', 'EMPLEADO', '2026-06-22', NULL),
(34, '88888888', 'Franco', 'asdsad', '2026-06-22', '1234', 'germ2anechaide@gmail.com', '/uploads/88888888/fotoPerfil_1782137833.png', '092726272', 'CLIENTE', '2026-06-22', NULL),
(35, '53507332', 'Juanma', 'Juanderson', '2002-05-17', '12345', 'jaunderson@gmail.com', '/uploads/PerfilPorDefecto.png', '098267299', 'EMPLEADO', '2026-06-23', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verificacion_usuario`
--

CREATE TABLE `verificacion_usuario` (
  `idUsuario` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `fechaExpiracion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_Usuario`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `fk_especialidad_servicio` (`especialidad`);

--
-- Indices de la tabla `empleado_servicios`
--
ALTER TABLE `empleado_servicios`
  ADD PRIMARY KEY (`idEmpleado`,`idServicio`),
  ADD KEY `idServicio` (`idServicio`);

--
-- Indices de la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  ADD PRIMARY KEY (`idHorario`),
  ADD KEY `fk_horario_empleado` (`idEmpleado`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`idResena`),
  ADD KEY `fk_Cliente` (`idCliente`),
  ADD KEY `fk_Empleado` (`idEmpleado`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`idReserva`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idServicio` (`idServicio`),
  ADD KEY `reservas_ibfk_2` (`idEmpleado`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `ci` (`ci`);

--
-- Indices de la tabla `verificacion_usuario`
--
ALTER TABLE `verificacion_usuario`
  ADD KEY `idUsuario` (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  MODIFY `idHorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `idResena` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `idReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_Usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_especialidad_servicio` FOREIGN KEY (`especialidad`) REFERENCES `servicios` (`idServicio`);

--
-- Filtros para la tabla `empleado_servicios`
--
ALTER TABLE `empleado_servicios`
  ADD CONSTRAINT `empleado_servicios_ibfk_1` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`id_usuario`),
  ADD CONSTRAINT `empleado_servicios_ibfk_2` FOREIGN KEY (`idServicio`) REFERENCES `servicios` (`idServicio`);

--
-- Filtros para la tabla `horario_empleado`
--
ALTER TABLE `horario_empleado`
  ADD CONSTRAINT `fk_horario_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`id_usuario`);

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `fk_Cliente` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`id_Usuario`),
  ADD CONSTRAINT `fk_Empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`id_usuario`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`id_Usuario`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`id_usuario`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`idServicio`) REFERENCES `servicios` (`idServicio`);

--
-- Filtros para la tabla `verificacion_usuario`
--
ALTER TABLE `verificacion_usuario`
  ADD CONSTRAINT `verificacion_usuario_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
