-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Aug 02, 2021 at 04:21 AM
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
  `peticion_pago_id` int(5) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `cedula` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `estado_pago`, `peticion_pago_id`, `amount`, `date`, `cedula`) VALUES
(1, 'pending', 2, 5000.00, '2021-08-02', 7);

-- --------------------------------------------------------

--
-- Table structure for table `peticiones_pago`
--

CREATE TABLE `peticiones_pago` (
  `id` int(7) NOT NULL,
  `cedula` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_creacion` date NOT NULL DEFAULT current_timestamp(),
  `id_contrato` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `monto` int(10) NOT NULL,
  `estado_pago` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `detalles` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `peticiones_pago`
--

INSERT INTO `peticiones_pago` (`id`, `cedula`, `fecha_creacion`, `id_contrato`, `monto`, `estado_pago`, `estado`, `detalles`) VALUES
(1, '7', '2021-08-01', '', 50000, 'open', 'denegado', 'aqui van detalles'),
(2, '7', '2021-08-01', '', 20000, 'pending', 'aprobado', 'aqui van detalles'),
(3, '2', '2021-08-01', '', 30000, 'pending', 'aprobado', 'aqui van detalles'),
(4, '7', '2021-08-01', '', 25000, 'pending', 'open', 'aqui van detalles'),
(5, '2', '2021-08-02', '', 35000, 'pending', 'open', 'aqui van detalles');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUsuario` int(20) NOT NULL,
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
  `foto` varchar(300) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUsuario`, `cedula`, `nombre`, `apellido1`, `apellido2`, `telefono`, `direccion`, `cuentaBancaria`, `email`, `contrasena`, `rol`, `foto`) VALUES
(24, '7', 'Jeff', 'c', 'c', '', '', '', '', '$2y$10$F1eZNJbDLkSruYhG7TEa5usfHBmmEY6dQRDIfWSLRtmYlQ51cYoLG', 'admin', '1549430c118220cccef9d6114f8a6409.jpg'),
(25, '4', 'David', 'c', 'm', '', '', '', '', '$2y$10$3rl0w6VlZlG96yqLcZGgPO2hVQC2MEEpJjTt.kgjGVdcn0UTJa.Q2', 'user', ''),
(26, '2', 'Oscar', 'c', 'v', '', '', '', '', '$2y$10$Y5US5pjRNBogpsgkB7MIKOYWUcnFYh.3jiJAQwL5ie9zApZtMms4C', 'admin', '35ec5ee8a57764aced019610057d1c70.jpg'),
(27, '3', 'tania', 'v', 'c', '', '', '', '', '$2y$10$NdSMk5uTSnspgWpU3fdkhueUDMbMDFtYkk8xYAa6u.UtfnzgxFZcC', 'user', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peticiones_pago`
--
ALTER TABLE `peticiones_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUsuario` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
