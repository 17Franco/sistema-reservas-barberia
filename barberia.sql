-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2026 a las 21:09:31
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
  `ci` varchar(11) NOT NULL,
  `idHistorial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ci`, `idHistorial`) VALUES
('53507223', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `ci` varchar(11) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFin` time NOT NULL,
  `estado` varchar(10) NOT NULL,
  `especialidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado_servicios`
--

CREATE TABLE `empleado_servicios` (
  `idEmpleado` varchar(11) NOT NULL,
  `idServicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mf`
--

CREATE TABLE `mf` (
  `idUsuario` varchar(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `fechaExpiracion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `idServicio` int(11) NOT NULL,
  `idEmpleado` varchar(11) NOT NULL,
  `idUsuario` varchar(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `idServicio` int(11) NOT NULL,
  `nombre` int(25) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `img` blob NOT NULL,
  `precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ci` varchar(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `fechaNac` date NOT NULL,
  `contraseña` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `celular` varchar(11) NOT NULL,
  `tipoUsuario` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ci`, `nombre`, `apellido`, `fechaNac`, `contraseña`, `email`, `foto`, `celular`, `tipoUsuario`) VALUES
('53507223', 'Franco', 'Echaide', '2026-06-01', '1234', 'franco@gmail.com', '/uploads/53507223/fotoPerfil.png', '099123456', 'CLIENTE');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ci`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`ci`),
  ADD KEY `fk_especialidad_servicio` (`especialidad`);

--
-- Indices de la tabla `empleado_servicios`
--
ALTER TABLE `empleado_servicios`
  ADD KEY `fk_empleadoS_empleado` (`idEmpleado`),
  ADD KEY `fk_servicioS_servicio` (`idServicio`);

--
-- Indices de la tabla `mf`
--
ALTER TABLE `mf`
  ADD KEY `fk_mf_usuario` (`idUsuario`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`idUsuario`,`fecha`,`hora`),
  ADD KEY `fk_reserva_empleado` (`idEmpleado`),
  ADD KEY `fk_reserva_servicio` (`idServicio`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ci`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`ci`) REFERENCES `usuarios` (`ci`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_empleado_usuario` FOREIGN KEY (`ci`) REFERENCES `usuarios` (`ci`),
  ADD CONSTRAINT `fk_especialidad_servicio` FOREIGN KEY (`especialidad`) REFERENCES `servicios` (`idServicio`);

--
-- Filtros para la tabla `empleado_servicios`
--
ALTER TABLE `empleado_servicios`
  ADD CONSTRAINT `fk_empleadoS_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`ci`),
  ADD CONSTRAINT `fk_servicioS_servicio` FOREIGN KEY (`idServicio`) REFERENCES `servicios` (`idServicio`);

--
-- Filtros para la tabla `mf`
--
ALTER TABLE `mf`
  ADD CONSTRAINT `fk_mf_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`ci`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_empleado` FOREIGN KEY (`idEmpleado`) REFERENCES `empleado` (`ci`),
  ADD CONSTRAINT `fk_reserva_servicio` FOREIGN KEY (`idServicio`) REFERENCES `servicios` (`idServicio`),
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`ci`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
