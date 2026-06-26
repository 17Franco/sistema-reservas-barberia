-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql309.infinityfree.com
-- Tiempo de generación: 26-06-2026 a las 19:14:53
-- Versión del servidor: 11.4.12-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_42255858_barberia`
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
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43);

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
(2, 'ACTIVO', 1),
(3, 'ACTIVO', 2),
(4, 'ACTIVO', 3),
(5, 'ACTIVO', 1),
(6, 'ACTIVO', 5),
(7, 'ACTIVO', 3),
(8, 'ACTIVO', 8);

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
(2, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(2, 2),
(3, 2),
(4, 2),
(7, 2),
(4, 3),
(7, 3),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(7, 4),
(8, 4),
(6, 5),
(2, 6),
(4, 6),
(5, 6),
(7, 6),
(6, 7),
(8, 8),
(3, 9),
(7, 10);

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
(1, 2, '09:00:00', '14:00:00', '00:00:00', '00:00:00'),
(2, 3, '14:00:00', '20:00:00', '00:00:00', '00:00:00'),
(3, 4, '09:00:00', '18:00:00', '13:00:00', '14:00:00'),
(4, 5, '09:00:00', '18:00:00', '13:00:00', '14:00:00'),
(5, 6, '10:00:00', '19:00:00', '14:00:00', '15:00:00'),
(6, 7, '09:00:00', '18:00:00', '13:00:00', '14:00:00'),
(7, 8, '09:00:00', '15:00:00', '00:00:00', '00:00:00');

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
(1, 18, 2, 1, '2026-04-26', '09:00:00', '09:30:00', 'COMPLETADA'),
(2, 19, 4, 3, '2026-04-26', '09:00:00', '10:00:00', 'COMPLETADA'),
(3, 20, 5, 1, '2026-04-26', '09:00:00', '09:30:00', 'COMPLETADA'),
(4, 21, 6, 5, '2026-04-26', '10:00:00', '10:40:00', 'COMPLETADA'),
(5, 22, 7, 2, '2026-04-26', '09:00:00', '09:45:00', 'COMPLETADA'),
(6, 23, 8, 8, '2026-04-26', '09:00:00', '10:30:00', 'COMPLETADA'),
(7, 24, 2, 6, '2026-04-28', '09:30:00', '10:00:00', 'COMPLETADA'),
(8, 25, 4, 1, '2026-04-28', '10:00:00', '10:30:00', 'COMPLETADA'),
(9, 26, 5, 4, '2026-04-28', '10:00:00', '10:20:00', 'COMPLETADA'),
(10, 27, 6, 7, '2026-04-28', '11:00:00', '11:45:00', 'COMPLETADA'),
(11, 28, 7, 3, '2026-04-28', '10:00:00', '11:00:00', 'COMPLETADA'),
(12, 29, 2, 2, '2026-04-30', '09:00:00', '09:45:00', 'COMPLETADA'),
(13, 30, 4, 6, '2026-04-30', '11:00:00', '11:30:00', 'COMPLETADA'),
(14, 31, 5, 1, '2026-04-30', '11:00:00', '11:30:00', 'COMPLETADA'),
(15, 32, 7, 10, '2026-04-30', '11:00:00', '11:45:00', 'COMPLETADA'),
(16, 33, 4, 3, '2026-05-16', '14:00:00', '15:00:00', 'COMPLETADA'),
(17, 34, 5, 6, '2026-05-16', '14:00:00', '14:30:00', 'COMPLETADA'),
(18, 35, 6, 5, '2026-05-16', '15:00:00', '15:40:00', 'COMPLETADA'),
(19, 36, 7, 2, '2026-05-16', '14:00:00', '14:45:00', 'COMPLETADA'),
(20, 37, 2, 1, '2026-05-18', '10:00:00', '10:30:00', 'COMPLETADA'),
(21, 38, 4, 4, '2026-05-18', '15:00:00', '15:20:00', 'COMPLETADA'),
(22, 39, 5, 1, '2026-05-18', '15:00:00', '15:30:00', 'COMPLETADA'),
(23, 40, 7, 3, '2026-05-18', '15:00:00', '16:00:00', 'COMPLETADA'),
(24, 41, 8, 8, '2026-05-18', '10:30:00', '12:00:00', 'COMPLETADA'),
(25, 42, 2, 6, '2026-05-20', '11:00:00', '11:30:00', 'COMPLETADA'),
(26, 18, 4, 1, '2026-05-20', '16:00:00', '16:30:00', 'COMPLETADA'),
(27, 19, 5, 4, '2026-05-20', '16:00:00', '16:20:00', 'COMPLETADA'),
(28, 20, 6, 7, '2026-05-20', '16:00:00', '16:45:00', 'COMPLETADA'),
(29, 21, 7, 1, '2026-05-20', '16:00:00', '16:30:00', 'COMPLETADA'),
(30, 22, 4, 2, '2026-05-21', '09:30:00', '10:15:00', 'CANCELADA'),
(31, 23, 2, 1, '2026-05-22', '09:00:00', '09:30:00', 'CANCELADA'),
(32, 24, 6, 5, '2026-05-23', '10:00:00', '10:40:00', 'CANCELADA'),
(33, 25, 7, 3, '2026-05-24', '10:00:00', '11:00:00', 'CANCELADA'),
(34, 26, 5, 6, '2026-05-24', '11:00:00', '11:30:00', 'CANCELADA'),
(35, 18, 2, 1, '2026-06-29', '09:00:00', '09:30:00', 'PENDIENTE'),
(36, 19, 2, 1, '2026-06-29', '09:30:00', '10:00:00', 'PENDIENTE'),
(37, 20, 2, 6, '2026-06-29', '10:00:00', '10:30:00', 'PENDIENTE'),
(38, 21, 2, 6, '2026-06-29', '10:30:00', '11:00:00', 'PENDIENTE'),
(39, 22, 2, 1, '2026-06-29', '11:00:00', '11:30:00', 'PENDIENTE'),
(40, 23, 2, 6, '2026-06-29', '11:30:00', '12:00:00', 'PENDIENTE'),
(41, 24, 2, 1, '2026-06-29', '12:00:00', '12:30:00', 'PENDIENTE'),
(42, 25, 2, 6, '2026-06-29', '12:30:00', '13:00:00', 'PENDIENTE'),
(43, 26, 2, 1, '2026-06-29', '13:00:00', '13:30:00', 'PENDIENTE'),
(44, 27, 2, 6, '2026-06-29', '13:30:00', '14:00:00', 'PENDIENTE'),
(45, 28, 3, 9, '2026-06-29', '14:00:00', '14:15:00', 'PENDIENTE'),
(46, 29, 3, 9, '2026-06-29', '14:15:00', '14:30:00', 'PENDIENTE'),
(47, 30, 3, 4, '2026-06-29', '14:30:00', '14:50:00', 'PENDIENTE'),
(48, 31, 3, 9, '2026-06-29', '14:50:00', '15:05:00', 'PENDIENTE'),
(49, 32, 3, 9, '2026-06-29', '15:05:00', '15:20:00', 'PENDIENTE'),
(50, 33, 3, 4, '2026-06-29', '15:20:00', '15:40:00', 'PENDIENTE'),
(51, 34, 3, 2, '2026-06-29', '15:40:00', '16:25:00', 'PENDIENTE'),
(52, 35, 3, 9, '2026-06-29', '16:25:00', '16:40:00', 'PENDIENTE'),
(53, 36, 3, 4, '2026-06-29', '16:40:00', '17:00:00', 'PENDIENTE'),
(54, 37, 3, 2, '2026-06-29', '17:00:00', '17:45:00', 'PENDIENTE'),
(55, 38, 3, 9, '2026-06-29', '17:45:00', '18:00:00', 'PENDIENTE'),
(56, 39, 3, 4, '2026-06-29', '18:00:00', '18:20:00', 'PENDIENTE'),
(57, 40, 3, 2, '2026-06-29', '18:20:00', '19:05:00', 'PENDIENTE'),
(58, 41, 3, 9, '2026-06-29', '19:05:00', '19:20:00', 'PENDIENTE'),
(59, 42, 3, 4, '2026-06-29', '19:20:00', '19:40:00', 'PENDIENTE'),
(60, 9, 8, 8, '2026-06-29', '09:00:00', '10:30:00', 'PENDIENTE'),
(61, 10, 8, 8, '2026-06-29', '10:30:00', '12:00:00', 'PENDIENTE'),
(62, 11, 8, 8, '2026-06-29', '12:00:00', '13:30:00', 'PENDIENTE'),
(63, 12, 8, 8, '2026-06-29', '13:30:00', '15:00:00', 'PENDIENTE'),
(64, 13, 4, 3, '2026-06-29', '09:00:00', '10:00:00', 'PENDIENTE'),
(65, 14, 4, 1, '2026-06-29', '10:00:00', '10:30:00', 'PENDIENTE'),
(66, 15, 4, 2, '2026-06-29', '10:30:00', '11:15:00', 'PENDIENTE'),
(67, 16, 4, 6, '2026-06-29', '11:15:00', '11:45:00', 'PENDIENTE'),
(68, 17, 4, 1, '2026-06-29', '11:45:00', '12:15:00', 'PENDIENTE'),
(69, 18, 4, 4, '2026-06-29', '12:15:00', '12:35:00', 'PENDIENTE'),
(70, 19, 4, 4, '2026-06-29', '12:35:00', '12:55:00', 'PENDIENTE'),
(71, 20, 4, 3, '2026-06-29', '14:00:00', '15:00:00', 'PENDIENTE'),
(72, 21, 4, 2, '2026-06-29', '15:00:00', '15:45:00', 'PENDIENTE'),
(73, 22, 4, 6, '2026-06-29', '15:45:00', '16:15:00', 'PENDIENTE'),
(74, 23, 4, 1, '2026-06-29', '16:15:00', '16:45:00', 'PENDIENTE'),
(75, 24, 4, 1, '2026-06-29', '16:45:00', '17:15:00', 'PENDIENTE'),
(76, 25, 4, 6, '2026-06-29', '17:15:00', '17:45:00', 'PENDIENTE'),
(77, 26, 5, 1, '2026-06-29', '09:00:00', '09:30:00', 'PENDIENTE'),
(78, 27, 5, 6, '2026-06-29', '09:30:00', '10:00:00', 'PENDIENTE'),
(79, 28, 5, 4, '2026-06-29', '10:00:00', '10:20:00', 'PENDIENTE'),
(80, 29, 5, 1, '2026-06-29', '10:20:00', '10:50:00', 'PENDIENTE'),
(81, 30, 5, 6, '2026-06-29', '10:50:00', '11:20:00', 'PENDIENTE'),
(82, 31, 5, 4, '2026-06-29', '11:20:00', '11:40:00', 'PENDIENTE'),
(83, 32, 5, 1, '2026-06-29', '11:40:00', '12:10:00', 'PENDIENTE'),
(84, 33, 5, 6, '2026-06-29', '12:10:00', '12:40:00', 'PENDIENTE'),
(85, 34, 5, 1, '2026-06-29', '14:00:00', '14:30:00', 'PENDIENTE'),
(86, 35, 5, 6, '2026-06-29', '14:30:00', '15:00:00', 'PENDIENTE'),
(87, 36, 5, 4, '2026-06-29', '15:00:00', '15:20:00', 'PENDIENTE'),
(88, 37, 5, 1, '2026-06-29', '15:20:00', '15:50:00', 'PENDIENTE'),
(89, 38, 5, 6, '2026-06-29', '15:50:00', '16:20:00', 'PENDIENTE'),
(90, 39, 5, 4, '2026-06-29', '16:20:00', '16:40:00', 'PENDIENTE'),
(91, 40, 5, 1, '2026-06-29', '16:40:00', '17:10:00', 'PENDIENTE'),
(92, 41, 5, 6, '2026-06-29', '17:10:00', '17:40:00', 'PENDIENTE'),
(93, 42, 6, 5, '2026-06-29', '10:00:00', '10:40:00', 'PENDIENTE'),
(94, 18, 6, 1, '2026-06-29', '10:40:00', '11:10:00', 'PENDIENTE'),
(95, 19, 6, 7, '2026-06-29', '11:10:00', '11:55:00', 'PENDIENTE'),
(96, 20, 6, 1, '2026-06-29', '11:55:00', '12:25:00', 'PENDIENTE'),
(97, 21, 6, 5, '2026-06-29', '12:25:00', '13:05:00', 'PENDIENTE'),
(98, 22, 6, 1, '2026-06-29', '13:05:00', '13:35:00', 'PENDIENTE'),
(99, 23, 6, 7, '2026-06-29', '15:00:00', '15:45:00', 'PENDIENTE'),
(100, 24, 6, 1, '2026-06-29', '15:45:00', '16:15:00', 'PENDIENTE'),
(101, 25, 6, 5, '2026-06-29', '16:15:00', '16:55:00', 'PENDIENTE'),
(102, 26, 6, 1, '2026-06-29', '16:55:00', '17:25:00', 'PENDIENTE'),
(103, 27, 6, 7, '2026-06-29', '17:25:00', '18:10:00', 'PENDIENTE'),
(104, 28, 6, 1, '2026-06-29', '18:10:00', '18:40:00', 'PENDIENTE'),
(105, 29, 7, 3, '2026-06-29', '09:00:00', '10:00:00', 'PENDIENTE'),
(106, 30, 7, 2, '2026-06-29', '10:00:00', '10:45:00', 'PENDIENTE'),
(107, 31, 7, 1, '2026-06-29', '10:45:00', '11:15:00', 'PENDIENTE'),
(108, 32, 7, 6, '2026-06-29', '11:15:00', '11:45:00', 'PENDIENTE'),
(109, 33, 7, 10, '2026-06-29', '11:45:00', '12:30:00', 'PENDIENTE'),
(110, 34, 7, 1, '2026-06-29', '12:30:00', '13:00:00', 'PENDIENTE'),
(111, 35, 7, 3, '2026-06-29', '14:00:00', '15:00:00', 'PENDIENTE'),
(112, 36, 7, 2, '2026-06-29', '15:00:00', '15:45:00', 'PENDIENTE'),
(113, 37, 7, 1, '2026-06-29', '15:45:00', '16:15:00', 'PENDIENTE'),
(114, 38, 7, 6, '2026-06-29', '16:15:00', '16:45:00', 'PENDIENTE'),
(115, 39, 7, 10, '2026-06-29', '16:45:00', '17:30:00', 'PENDIENTE'),
(116, 40, 7, 1, '2026-06-29', '17:30:00', '18:00:00', 'PENDIENTE'),
(117, 41, 3, 4, '2026-06-29', '19:40:00', '20:00:00', 'PENDIENTE'),
(118, 42, 5, 4, '2026-06-29', '12:40:00', '13:00:00', 'PENDIENTE'),
(119, 18, 5, 4, '2026-06-29', '17:40:00', '18:00:00', 'PENDIENTE'),
(120, 18, 2, 1, '2026-07-02', '09:00:00', '09:30:00', 'PENDIENTE'),
(121, 19, 2, 6, '2026-07-02', '09:30:00', '10:00:00', 'PENDIENTE'),
(122, 20, 2, 2, '2026-07-02', '10:00:00', '10:45:00', 'PENDIENTE'),
(123, 21, 2, 4, '2026-07-02', '10:45:00', '11:05:00', 'PENDIENTE'),
(124, 22, 2, 6, '2026-07-02', '11:05:00', '11:35:00', 'PENDIENTE'),
(125, 23, 2, 1, '2026-07-02', '11:35:00', '12:05:00', 'PENDIENTE'),
(126, 24, 2, 4, '2026-07-02', '12:05:00', '12:25:00', 'PENDIENTE'),
(127, 25, 2, 2, '2026-07-02', '12:25:00', '13:10:00', 'PENDIENTE'),
(128, 26, 2, 6, '2026-07-02', '13:10:00', '13:40:00', 'PENDIENTE'),
(129, 27, 2, 4, '2026-07-02', '13:40:00', '14:00:00', 'PENDIENTE'),
(130, 28, 3, 9, '2026-07-02', '14:00:00', '14:15:00', 'PENDIENTE'),
(131, 29, 3, 4, '2026-07-02', '14:15:00', '14:35:00', 'PENDIENTE'),
(132, 30, 3, 2, '2026-07-02', '14:35:00', '15:20:00', 'PENDIENTE'),
(133, 31, 3, 9, '2026-07-02', '15:20:00', '15:35:00', 'PENDIENTE'),
(134, 32, 3, 4, '2026-07-02', '15:35:00', '15:55:00', 'PENDIENTE'),
(135, 33, 3, 2, '2026-07-02', '15:55:00', '16:40:00', 'PENDIENTE'),
(136, 34, 3, 9, '2026-07-02', '16:40:00', '16:55:00', 'PENDIENTE'),
(137, 35, 3, 4, '2026-07-02', '16:55:00', '17:15:00', 'PENDIENTE'),
(138, 36, 3, 2, '2026-07-02', '17:15:00', '18:00:00', 'PENDIENTE'),
(139, 37, 3, 9, '2026-07-02', '18:00:00', '18:15:00', 'PENDIENTE'),
(140, 38, 3, 4, '2026-07-02', '18:15:00', '18:35:00', 'PENDIENTE'),
(141, 39, 3, 2, '2026-07-02', '18:35:00', '19:20:00', 'PENDIENTE'),
(142, 40, 3, 9, '2026-07-02', '19:20:00', '19:35:00', 'PENDIENTE'),
(143, 41, 3, 4, '2026-07-02', '19:35:00', '19:55:00', 'PENDIENTE'),
(144, 42, 8, 8, '2026-07-02', '09:00:00', '10:30:00', 'PENDIENTE'),
(145, 18, 8, 8, '2026-07-02', '10:30:00', '12:00:00', 'PENDIENTE'),
(146, 19, 8, 8, '2026-07-02', '12:00:00', '13:30:00', 'PENDIENTE'),
(147, 20, 8, 8, '2026-07-02', '13:30:00', '15:00:00', 'PENDIENTE'),
(148, 21, 4, 3, '2026-07-02', '09:00:00', '10:00:00', 'PENDIENTE'),
(149, 22, 4, 2, '2026-07-02', '10:00:00', '10:45:00', 'PENDIENTE'),
(150, 23, 4, 6, '2026-07-02', '10:45:00', '11:15:00', 'PENDIENTE'),
(151, 24, 4, 1, '2026-07-02', '11:15:00', '11:45:00', 'PENDIENTE'),
(152, 25, 4, 4, '2026-07-02', '11:45:00', '12:05:00', 'PENDIENTE'),
(153, 26, 4, 6, '2026-07-02', '12:05:00', '12:35:00', 'PENDIENTE'),
(154, 27, 4, 4, '2026-07-02', '12:35:00', '12:55:00', 'PENDIENTE'),
(155, 28, 4, 3, '2026-07-02', '14:00:00', '15:00:00', 'PENDIENTE'),
(156, 29, 4, 2, '2026-07-02', '15:00:00', '15:45:00', 'PENDIENTE'),
(157, 30, 4, 6, '2026-07-02', '15:45:00', '16:15:00', 'PENDIENTE'),
(158, 31, 4, 1, '2026-07-02', '16:15:00', '16:45:00', 'PENDIENTE'),
(159, 32, 4, 4, '2026-07-02', '16:45:00', '17:05:00', 'PENDIENTE'),
(160, 33, 4, 6, '2026-07-02', '17:05:00', '17:35:00', 'PENDIENTE'),
(161, 34, 4, 4, '2026-07-02', '17:35:00', '17:55:00', 'PENDIENTE'),
(162, 35, 5, 1, '2026-07-02', '09:00:00', '09:30:00', 'PENDIENTE'),
(163, 36, 5, 6, '2026-07-02', '09:30:00', '10:00:00', 'PENDIENTE'),
(164, 37, 5, 4, '2026-07-02', '10:00:00', '10:20:00', 'PENDIENTE'),
(165, 38, 5, 1, '2026-07-02', '10:20:00', '10:50:00', 'PENDIENTE'),
(166, 39, 5, 6, '2026-07-02', '10:50:00', '11:20:00', 'PENDIENTE'),
(167, 40, 5, 4, '2026-07-02', '11:20:00', '11:40:00', 'PENDIENTE'),
(168, 41, 5, 1, '2026-07-02', '11:40:00', '12:10:00', 'PENDIENTE'),
(169, 42, 5, 6, '2026-07-02', '12:10:00', '12:40:00', 'PENDIENTE'),
(170, 18, 5, 4, '2026-07-02', '12:40:00', '13:00:00', 'PENDIENTE'),
(171, 19, 5, 1, '2026-07-02', '14:00:00', '14:30:00', 'PENDIENTE'),
(172, 20, 5, 6, '2026-07-02', '14:30:00', '15:00:00', 'PENDIENTE'),
(173, 21, 5, 4, '2026-07-02', '15:00:00', '15:20:00', 'PENDIENTE'),
(174, 22, 5, 1, '2026-07-02', '15:20:00', '15:50:00', 'PENDIENTE'),
(175, 23, 5, 6, '2026-07-02', '15:50:00', '16:20:00', 'PENDIENTE'),
(176, 24, 5, 4, '2026-07-02', '16:20:00', '16:40:00', 'PENDIENTE'),
(177, 25, 5, 1, '2026-07-02', '16:40:00', '17:10:00', 'PENDIENTE'),
(178, 26, 5, 6, '2026-07-02', '17:10:00', '17:40:00', 'PENDIENTE'),
(179, 27, 5, 4, '2026-07-02', '17:40:00', '18:00:00', 'PENDIENTE'),
(180, 28, 6, 5, '2026-07-02', '10:00:00', '10:40:00', 'PENDIENTE'),
(181, 29, 6, 1, '2026-07-02', '10:40:00', '11:10:00', 'PENDIENTE'),
(182, 30, 6, 7, '2026-07-02', '11:10:00', '11:55:00', 'PENDIENTE'),
(183, 31, 6, 5, '2026-07-02', '11:55:00', '12:35:00', 'PENDIENTE'),
(184, 32, 6, 1, '2026-07-02', '12:35:00', '13:05:00', 'PENDIENTE'),
(185, 33, 6, 7, '2026-07-02', '13:05:00', '13:50:00', 'PENDIENTE'),
(186, 34, 6, 5, '2026-07-02', '15:00:00', '15:40:00', 'PENDIENTE'),
(187, 35, 6, 1, '2026-07-02', '15:40:00', '16:10:00', 'PENDIENTE'),
(188, 36, 6, 7, '2026-07-02', '16:10:00', '16:55:00', 'PENDIENTE'),
(189, 37, 6, 5, '2026-07-02', '16:55:00', '17:35:00', 'PENDIENTE'),
(190, 38, 6, 1, '2026-07-02', '17:35:00', '18:05:00', 'PENDIENTE'),
(191, 39, 6, 7, '2026-07-02', '18:05:00', '18:50:00', 'PENDIENTE'),
(192, 40, 7, 3, '2026-07-02', '09:00:00', '10:00:00', 'PENDIENTE'),
(193, 41, 7, 2, '2026-07-02', '10:00:00', '10:45:00', 'PENDIENTE'),
(194, 42, 7, 10, '2026-07-02', '10:45:00', '11:30:00', 'PENDIENTE'),
(195, 18, 7, 6, '2026-07-02', '11:30:00', '12:00:00', 'PENDIENTE'),
(196, 19, 7, 4, '2026-07-02', '12:00:00', '12:20:00', 'PENDIENTE'),
(197, 20, 7, 6, '2026-07-02', '12:20:00', '12:50:00', 'PENDIENTE'),
(198, 21, 7, 3, '2026-07-02', '14:00:00', '15:00:00', 'PENDIENTE'),
(199, 22, 7, 2, '2026-07-02', '15:00:00', '15:45:00', 'PENDIENTE'),
(200, 23, 7, 10, '2026-07-02', '15:45:00', '16:30:00', 'PENDIENTE'),
(201, 24, 7, 6, '2026-07-02', '16:30:00', '17:00:00', 'PENDIENTE'),
(202, 25, 7, 4, '2026-07-02', '17:00:00', '17:20:00', 'PENDIENTE'),
(203, 26, 7, 6, '2026-07-02', '17:20:00', '17:50:00', 'PENDIENTE'),
(204, 9, 8, 8, '2026-06-30', '09:00:00', '10:30:00', 'PENDIENTE'),
(205, 10, 8, 8, '2026-06-30', '10:30:00', '12:00:00', 'PENDIENTE'),
(206, 11, 8, 8, '2026-06-30', '12:00:00', '13:30:00', 'PENDIENTE'),
(207, 12, 8, 8, '2026-06-30', '13:30:00', '15:00:00', 'PENDIENTE'),
(208, 13, 5, 1, '2026-07-01', '09:00:00', '09:30:00', 'PENDIENTE'),
(209, 14, 5, 6, '2026-07-01', '09:30:00', '10:00:00', 'PENDIENTE'),
(210, 15, 5, 4, '2026-07-01', '10:00:00', '10:20:00', 'PENDIENTE'),
(211, 16, 5, 1, '2026-07-01', '10:20:00', '10:50:00', 'PENDIENTE'),
(212, 17, 5, 6, '2026-07-01', '10:50:00', '11:20:00', 'PENDIENTE'),
(213, 18, 5, 4, '2026-07-01', '11:20:00', '11:40:00', 'PENDIENTE'),
(214, 19, 5, 1, '2026-07-01', '11:40:00', '12:10:00', 'PENDIENTE'),
(215, 20, 5, 6, '2026-07-01', '12:10:00', '12:40:00', 'PENDIENTE'),
(216, 21, 5, 1, '2026-07-01', '14:00:00', '14:30:00', 'PENDIENTE'),
(217, 22, 5, 6, '2026-07-01', '14:30:00', '15:00:00', 'PENDIENTE'),
(218, 23, 5, 4, '2026-07-01', '15:00:00', '15:20:00', 'PENDIENTE'),
(219, 24, 5, 1, '2026-07-01', '15:20:00', '15:50:00', 'PENDIENTE'),
(220, 25, 5, 6, '2026-07-01', '15:50:00', '16:20:00', 'PENDIENTE'),
(221, 26, 5, 4, '2026-07-01', '16:20:00', '16:40:00', 'PENDIENTE'),
(222, 27, 5, 1, '2026-07-01', '16:40:00', '17:10:00', 'PENDIENTE'),
(223, 28, 5, 6, '2026-07-01', '17:10:00', '17:40:00', 'PENDIENTE'),
(224, 29, 5, 4, '2026-07-01', '12:40:00', '13:00:00', 'PENDIENTE'),
(225, 30, 5, 4, '2026-07-01', '17:40:00', '18:00:00', 'PENDIENTE');

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
(5, 'Coloración / Tintura', 'Tinte completo para cabello o barba.', 40, 600),
(6, 'Corte Infantil', 'Corte de cabello adaptado para niños menores de 12 años.', 30, 350),
(7, 'Tratamiento Capilar Anticaída', 'Masaje capilar con ampollas y lavado purificante.', 45, 800),
(8, 'Alisado Progresivo', 'Tratamiento de alisado con productos libres de formol.', 90, 1500),
(9, 'Diseño de Cejas', 'Perfilado y limpieza de cejas con navaja o pinza.', 15, 150),
(10, 'Afeitado de Cabeza Premium', 'Afeitado completo a navaja con espuma caliente.', 45, 400);

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
(1, '12345678', 'Admin', 'Admin', '1990-01-01', 'admin', 'admin@barberia.com', '/uploads/Admin.png', '099123456', 'ADMIN', '2026-06-19', NULL),
(2, '40000001', 'Carlos', 'Gómez', '1990-05-12', '1234', 'carlos@barberia.com', '/uploads/PerfilPorDefecto.png', '099000002', 'EMPLEADO', '2026-06-14', NULL),
(3, '40000002', 'María', 'Rodríguez', '1995-08-22', '1234', 'maria@barberia.com', '/uploads/PerfilPorDefecto.png', '099000003', 'EMPLEADO', '2026-06-14', NULL),
(4, '40000003', 'Juan', 'Pérez', '1988-01-30', '1234', 'juan@barberia.com', '/uploads/PerfilPorDefecto.png', '099000004', 'EMPLEADO', '2026-06-14', NULL),
(5, '40000004', 'Diego', 'Fernández', '1993-11-15', '1234', 'diego@barberia.com', '/uploads/PerfilPorDefecto.png', '099000005', 'EMPLEADO', '2026-06-14', NULL),
(6, '40000005', 'Ana', 'Martínez', '1997-03-05', '1234', 'ana@barberia.com', '/uploads/PerfilPorDefecto.png', '099000006', 'EMPLEADO', '2026-06-14', NULL),
(7, '40000006', 'Luis', 'Herrera', '1989-02-14', '1234', 'luis@barberia.com', '/uploads/PerfilPorDefecto.png', '099000007', 'EMPLEADO', '2026-06-25', NULL),
(8, '40000007', 'Javier', 'López', '1994-07-19', '1234', 'javier@barberia.com', '/uploads/PerfilPorDefecto.png', '099000008', 'EMPLEADO', '2026-06-25', NULL),
(9, '50000001', 'Lucas', 'Silva', '1998-07-14', '1234', 'lucas@gmail.com', '/uploads/PerfilPorDefecto.png', '094111222', 'CLIENTE', '2026-06-14', NULL),
(10, '50000002', 'Mateo', 'Álvarez', '1992-03-22', '1234', 'mateo@gmail.com', '/uploads/PerfilPorDefecto.png', '093444555', 'CLIENTE', '2026-06-14', NULL),
(11, '50000003', 'Nicolás', 'Pereira', '2000-11-05', '1234', 'nico@gmail.com', '/uploads/PerfilPorDefecto.png', '092777888', 'CLIENTE', '2026-06-14', NULL),
(12, '50000004', 'Santiago', 'Acosta', '1985-05-19', '1234', 'santi@gmail.com', '/uploads/PerfilPorDefecto.png', '091999000', 'CLIENTE', '2026-06-14', NULL),
(13, '50000005', 'Mathias', 'Díaz', '1996-09-11', '1234', 'mathi@gmail.com', '/uploads/PerfilPorDefecto.png', '095222333', 'CLIENTE', '2026-06-14', NULL),
(14, '50000006', 'German', 'Echaide', '2000-06-20', '123', 'germanechaide@gmail.com', '/uploads/PerfilPorDefecto.png', '098267299', 'CLIENTE', '2026-06-20', NULL),
(15, '50000007', 'Franco', 'Echaide', '1997-06-22', '1234', 'germanechaide2@gmail.com', '/uploads/PerfilPorDefecto.png', '098282892', 'CLIENTE', '2026-06-22', NULL),
(16, '50000008', 'Bruno', 'Echaide', '1999-01-15', '12345', 'echaidebruno@gmail.com', '/uploads/PerfilPorDefecto.png', '098267291', 'CLIENTE', '2026-06-22', 'Playa hermosa'),
(17, '50000009', 'Alejandro', 'Pruebas', '2001-03-12', '1234', 'alejandro@gmail.com', '/uploads/PerfilPorDefecto.png', '092726272', 'CLIENTE', '2026-06-22', NULL),
(18, '50000010', 'Agustín', 'Suárez', '1998-02-14', '1234', 'agustin1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111110', 'CLIENTE', '2026-06-26', NULL),
(19, '50000011', 'Martín', 'Silva', '1994-06-12', '1234', 'martin1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111111', 'CLIENTE', '2026-06-26', NULL),
(20, '50000012', 'Rodrigo', 'Fernández', '1996-01-08', '1234', 'rodrigo1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111112', 'CLIENTE', '2026-06-26', NULL),
(21, '50000013', 'Facundo', 'Pérez', '1999-09-18', '1234', 'facundo1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111113', 'CLIENTE', '2026-06-26', NULL),
(22, '50000014', 'Gonzalo', 'Sosa', '1997-05-20', '1234', 'gonzalo1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111114', 'CLIENTE', '2026-06-26', NULL),
(23, '50000015', 'Federico', 'Ramos', '1995-07-30', '1234', 'fede1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111115', 'CLIENTE', '2026-06-26', NULL),
(24, '50000016', 'Matías', 'Torres', '1998-10-05', '1234', 'matias1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111116', 'CLIENTE', '2026-06-26', NULL),
(25, '50000017', 'Sebastián', 'Acuña', '1993-12-11', '1234', 'sebastian1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111117', 'CLIENTE', '2026-06-26', NULL),
(26, '50000018', 'Andrés', 'García', '1991-03-02', '1234', 'andres1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111118', 'CLIENTE', '2026-06-26', NULL),
(27, '50000019', 'Joaquín', 'Méndez', '1996-11-21', '1234', 'joaquin1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111119', 'CLIENTE', '2026-06-26', NULL),
(28, '50000020', 'Pablo', 'Castro', '1998-08-13', '1234', 'pablo1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111120', 'CLIENTE', '2026-06-26', NULL),
(29, '50000021', 'Kevin', 'Ruiz', '1997-01-25', '1234', 'kevin1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111121', 'CLIENTE', '2026-06-26', NULL),
(30, '50000022', 'Leonardo', 'Viera', '1992-02-09', '1234', 'leo1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111122', 'CLIENTE', '2026-06-26', NULL),
(31, '50000023', 'Nahuel', 'Morales', '1999-05-18', '1234', 'nahuel1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111123', 'CLIENTE', '2026-06-26', NULL),
(32, '50000024', 'Brian', 'Pintos', '1995-09-27', '1234', 'brian1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111124', 'CLIENTE', '2026-06-26', NULL),
(33, '50000025', 'Emiliano', 'Benítez', '1994-12-07', '1234', 'emi1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111125', 'CLIENTE', '2026-06-26', NULL),
(34, '50000026', 'Cristian', 'Luna', '1998-03-11', '1234', 'cristian1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111126', 'CLIENTE', '2026-06-26', NULL),
(35, '50000027', 'Damián', 'Reyes', '1996-04-14', '1234', 'damian1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111127', 'CLIENTE', '2026-06-26', NULL),
(36, '50000028', 'Álvaro', 'Cabrera', '1997-07-07', '1234', 'alvaro1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111128', 'CLIENTE', '2026-06-26', NULL),
(37, '50000029', 'Jonathan', 'Núñez', '1995-11-19', '1234', 'jonathan1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111129', 'CLIENTE', '2026-06-26', NULL),
(38, '50000030', 'Diego', 'Olivera', '1993-06-09', '1234', 'diego2@gmail.com', '/uploads/PerfilPorDefecto.png', '091111130', 'CLIENTE', '2026-06-26', NULL),
(39, '50000031', 'Mauricio', 'Giménez', '1992-10-17', '1234', 'mauricio1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111131', 'CLIENTE', '2026-06-26', NULL),
(40, '50000032', 'Santiago', 'Domínguez', '1999-01-28', '1234', 'santi2@gmail.com', '/uploads/PerfilPorDefecto.png', '091111132', 'CLIENTE', '2026-06-26', NULL),
(41, '50000033', 'Gabriel', 'Pardo', '1996-08-22', '1234', 'gabriel1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111133', 'CLIENTE', '2026-06-26', NULL),
(42, '50000034', 'Alexis', 'Navarro', '1994-05-10', '1234', 'alexis1@gmail.com', '/uploads/PerfilPorDefecto.png', '091111134', 'CLIENTE', '2026-06-26', NULL),
(43, '53507227', 'Franco', 'Echaide', '2026-06-26', '1234', 'echaidefranco@gmail.com', '/uploads/53507227/fotoPerfil_1782448168.png', '099123456', 'CLIENTE', '2026-06-26', NULL);

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
  MODIFY `idHorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `idResena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `idReserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `idServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
