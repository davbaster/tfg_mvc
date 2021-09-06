-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Sep 02, 2021 at 05:25 PM
-- Server version: 10.4.18-MariaDB-1:10.4.18+maria~focal
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int(20) NOT NULL,
  `estado_pago` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `peticion_pago_id` int(7) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `fecha_pago` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cedula` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `detalles` varchar(1024) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `estado_pago`, `peticion_pago_id`, `amount`, `fecha_creacion`, `fecha_pago`, `cedula`, `detalles`) VALUES
(1, 'pagado', 4, 250000.00, '2021-08-17', '2021-08-17', '7', 'terminacion del corredor'),
(2, 'pagado', 5, 120000.00, '2021-08-18', '2021-08-18', '7', '5 horas'),
(3, 'pagado', 5, 115000.00, '2021-08-16', '2021-08-16', '7', '30 horas'),
(4, 'pagado', 6, 2000.00, '2021-08-21', '2021-08-21', '7', 'pago una hora'),
(5, 'pagado', 6, 25000.00, '2021-08-21', '2021-08-21', '4', '5 horas'),
(6, 'open', 8, 12000.00, '2021-08-21', '', '11', '8 horas'),
(7, 'pagado', 7, 1000.00, '2021-08-24', '2021-08-24', '9', '1 hora'),
(8, 'pagado', 7, 250000.00, '2021-08-24', '2021-08-24', '7', '2 horas'),
(9, 'pagado', 9, 25000.00, '2021-08-24', '2021-08-24', '7', '2 h'),
(10, 'open', 10, 12000.00, '2021-08-24', '', '7', '2 horas'),
(11, 'pagado', 13, 10000.00, '2021-09-01', '2021-09-01', '4', '1 metro pintado'),
(12, 'pagado', 13, 10000.00, '2021-09-01', '2021-09-01', '11', '1 metros'),
(13, 'pagado', 13, 1000.00, '2021-09-01', '2021-09-01', '7', '12');

-- --------------------------------------------------------

--
-- Table structure for table `peticiones_pago`
--

CREATE TABLE `peticiones_pago` (
  `id` int(7) NOT NULL,
  `nombre` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `cedula` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `id_contrato` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `monto` int(10) NOT NULL,
  `estado` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `detalles` varchar(1024) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `peticiones_pago`
--

INSERT INTO `peticiones_pago` (`id`, `nombre`, `cedula`, `fecha_creacion`, `id_contrato`, `monto`, `estado`, `detalles`) VALUES
(4, 'contrato corredor', '7', '2021-08-17', '', 250000, 'autorizado', '10 metros en cemento lujado'),
(5, 'tapia San Luis', '7', '2021-08-17', '', 600500, 'autorizado', '50 horas de trabajo en tapia'),
(6, 'soldadura techo', '7', '2021-08-18', '', 63123, 'autorizado', 'pago soldadura techo'),
(7, 'Repello pared', '7', '2021-07-16', '', 1000000, 'autorizado', '40 metros de repello'),
(8, 'test', '7', '2021-08-20', '', 123, 'open', 'test'),
(9, 'Repello piso', '7', '2021-08-24', '', 250000, 'autorizado', '20 horas'),
(10, 'Malla perimetral', '4', '2021-08-24', '', 300555, 'open', '200 horas'),
(11, 'pegada block', '4', '2021-08-24', '', 300123, 'autorizado', '10 horas'),
(12, 'mezcla asfalto', '4', '2021-08-25', '', 150000, 'open', '10 metros asfaltado'),
(13, 'pintura pared', '7', '2021-09-01', '', 20000, 'autorizado', '2 metros pintura');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(20) NOT NULL,
  `cedula` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apellido1` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apellido2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cuentaBancaria` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contrasena` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `foto` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `cedula`, `nombre`, `apellido1`, `apellido2`, `telefono`, `direccion`, `cuentaBancaria`, `email`, `contrasena`, `rol`, `foto`, `estado`) VALUES
(24, '7', 'Jonathan Josue', 'Lope', 'c', '2716', 'heredia', '200', '', '$2y$10$EQVhOU5p1k055AnJzuwcTuDp8IeT/ioiQqNfWs3N85F6FfW.kgSza', 'administrador', '', ''),
(25, '4', 'David Jose', 'C', 'M', '', '', '', '', '$2y$10$bUvaM.IpC3cTnE7zXmfVJusLODshk.q/NpzLKm691n9MxqbN3xoUW', 'administrador', '', ''),
(29, '9', 'Juana', 'C', 'R', '', '', '', '', '$2y$10$008afllh3S14RXU0jWbs0eVy742QZcnEg8OaltOz/pUaDg3unFY4u', 'administrador', '', 'activo'),
(33, '11', 'Daniel', 'Perez', 'Perez', '', '', '', '', '', 'construccion', '', 'activo'),
(35, '12', 'Jose', 'Cucanan', 'Cuca', '', '', '', '', '$2y$10$FkSwBEbjNM6UaPJRQoYRXuslUGirxqwUKGHp2G5cwt/jD8yCPCOSm', 'contratista', '', 'activo'),
(36, '7123456', 'Pablo', 'Cordoba', 'Cordoba', '71234567', 'Heredia', '200-01-200-1234567', '', '', 'construccion', '', 'activo'),
(38, '6', 'tania', 'v', 'm', '', '', '', '', '', 'construccion', '', 'activo');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `peticion_pago_id` (`peticion_pago_id`),
  ADD KEY `cedula` (`cedula`);

--
-- Indexes for table `peticiones_pago`
--
ALTER TABLE `peticiones_pago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cedula` (`cedula`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `peticiones_pago`
--
ALTER TABLE `peticiones_pago`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`peticion_pago_id`) REFERENCES `peticiones_pago` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
