-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2025 a las 23:18:43
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
-- Base de datos: `acoeemprendedores`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `documento_identidad` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `estado_familiar` enum('Soltero','Casado','Divorciado','Viudo') NOT NULL,
  `profesion` varchar(50) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `lugar_trabajo` varchar(100) DEFAULT NULL,
  `direccion_trabajo` text DEFAULT NULL,
  `salario_mensual` decimal(10,2) DEFAULT NULL,
  `otros_ingresos` decimal(10,2) DEFAULT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id_cuenta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `numero_cuenta` varchar(20) NOT NULL,
  `monto_apertura` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id_empleado` int(11) NOT NULL,
  `codigo_empleado` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `estado_familiar` enum('Soltero','Casado','Divorciado','Viudo') NOT NULL,
  `documento_identidad` varchar(20) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `edad` int(11) NOT NULL,
  `direccion` text NOT NULL,
  `puesto` varchar(50) NOT NULL,
  `departamento` enum('Finanzas','Atención al cliente','Gerencia','Servicios varios','Seguridad') NOT NULL,
  `sueldo` decimal(10,2) NOT NULL,
  `profesion` varchar(50) DEFAULT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `leida` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamo` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `numero_referencia` varchar(20) NOT NULL,
  `monto_otorgado` decimal(12,2) NOT NULL,
  `plazo_pago` int(11) NOT NULL,
  `cuota` decimal(12,2) NOT NULL,
  `tasa_interes` decimal(5,2) NOT NULL,
  `cuota_seguro` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_financieros`
--

CREATE TABLE `productos_financieros` (
  `id_producto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `tipo_producto` enum('Cuenta','Tarjeta','Prestamo','Seguro') NOT NULL,
  `detalle_producto` text NOT NULL,
  `fecha_adquisicion` date NOT NULL,
  `fecha_cierre` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguros`
--

CREATE TABLE `seguros` (
  `id_seguro` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `categoria` enum('Vida','Salud','Asistencia') NOT NULL,
  `monto_asegurado` decimal(12,2) NOT NULL,
  `plazo_pago` enum('Mensual','Trimestral','Semestral','Anual') NOT NULL,
  `cuota` decimal(10,2) NOT NULL,
  `renta_hospitalizacion` decimal(10,2) DEFAULT NULL,
  `causas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_productos`
--

CREATE TABLE `solicitudes_productos` (
  `id_solicitud` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `tipo_producto` enum('Cuenta','Tarjeta','Prestamo','Seguro') NOT NULL,
  `detalle_solicitud` text NOT NULL,
  `estado` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_empleado_respuesta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `id_tarjeta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `numero_tarjeta` varchar(20) NOT NULL,
  `limite_monto` decimal(12,2) NOT NULL,
  `tipo_red` enum('Visa','MasterCard') NOT NULL,
  `categoria` enum('Clasica','Infinite','Oro','Platinum','Empresarial') NOT NULL,
  `tasa_interes` decimal(5,2) NOT NULL,
  `costo_membresia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id_transaccion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha_transaccion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','cajero','gerente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id_cuenta`),
  ADD UNIQUE KEY `id_producto` (`id_producto`),
  ADD UNIQUE KEY `numero_cuenta` (`numero_cuenta`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `codigo_empleado` (`codigo_empleado`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD UNIQUE KEY `id_producto` (`id_producto`),
  ADD UNIQUE KEY `numero_referencia` (`numero_referencia`);

--
-- Indices de la tabla `productos_financieros`
--
ALTER TABLE `productos_financieros`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD PRIMARY KEY (`id_seguro`),
  ADD UNIQUE KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_empleado_respuesta` (`id_empleado_respuesta`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`id_tarjeta`),
  ADD UNIQUE KEY `id_producto` (`id_producto`),
  ADD UNIQUE KEY `numero_tarjeta` (`numero_tarjeta`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id_transaccion`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_empleado` (`id_empleado`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos_financieros`
--
ALTER TABLE `productos_financieros`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguros`
--
ALTER TABLE `seguros`
  MODIFY `id_seguro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `id_tarjeta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD CONSTRAINT `cuentas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos_financieros` (`id_producto`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos_financieros` (`id_producto`);

--
-- Filtros para la tabla `productos_financieros`
--
ALTER TABLE `productos_financieros`
  ADD CONSTRAINT `productos_financieros_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);

--
-- Filtros para la tabla `seguros`
--
ALTER TABLE `seguros`
  ADD CONSTRAINT `seguros_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos_financieros` (`id_producto`);

--
-- Filtros para la tabla `solicitudes_productos`
--
ALTER TABLE `solicitudes_productos`
  ADD CONSTRAINT `solicitudes_productos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`),
  ADD CONSTRAINT `solicitudes_productos_ibfk_2` FOREIGN KEY (`id_empleado_respuesta`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD CONSTRAINT `tarjetas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos_financieros` (`id_producto`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos_financieros` (`id_producto`),
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id_empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
