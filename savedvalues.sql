-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2024 at 09:25 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `savedvalues`
--

CREATE TABLE `savedvalues` (
  `patient_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `body_temp` int(11) DEFAULT NULL,
  `heart_rate` int(11) DEFAULT NULL,
  `oxygen_level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `savedvalues`
--

INSERT INTO `savedvalues` (`patient_id`, `time`, `body_temp`, `heart_rate`, `oxygen_level`) VALUES
(1, '2024-05-21 07:05:00', 36, 72, 97),
(1, '2024-05-21 08:00:00', 37, 68, 99),
(1, '2024-05-21 08:05:00', 34, 75, 98),
(1, '2024-05-21 08:10:00', 36, 72, 97),
(1, '2024-05-21 08:15:00', 37, 68, 99),
(1, '2024-05-21 08:20:00', 20, 40, 60),
(2, '2024-05-21 07:00:00', 35, 70, 98),
(2, '2024-05-21 07:05:00', 36, 72, 97),
(2, '2024-05-21 07:15:00', 37, 68, 99),
(2, '2024-05-21 17:48:48', 40, 110, 98);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `savedvalues`
--
ALTER TABLE `savedvalues`
  ADD KEY `patient_id` (`patient_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `savedvalues`
--
ALTER TABLE `savedvalues`
  ADD CONSTRAINT `savedvalues_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
